<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PublicImageStorage;
use App\Models\GalleryPhotoModel;
use App\Models\SiteContentModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use RuntimeException;

class Content extends BaseController
{
    public function index(string $page = 'beranda'): string
    {
        helper(['form', 'url']);

        $pages = SiteContentModel::editorPages();
        if ($page === 'profil' || $page === 'pelayanan') {
            $page = $page === 'profil' ? 'profil-sejarah' : 'pelayanan-wadah';
        }
        if (! array_key_exists($page, $pages)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $content = (new SiteContentModel())->forPage(SiteContentModel::editorContentPage($page));
        $galleryPhotos = (new GalleryPhotoModel())
            ->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->findAll(12);
        $sections = $pages[$page]['sections'];
        foreach ($sections as $sectionName => $fields) {
            foreach ($fields as $key => $field) {
                $input = 'content_' . $key;
                $sections[$sectionName][$key]['input'] = $input;
                $sections[$sectionName][$key]['value'] = $content[$key] ?? $field['default'];
            }
        }

        return view('admin/content/index', [
            'pageTitle' => 'Editor halaman',
            'pages' => $pages,
            'selectedPage' => $page,
            'selectedPageLabel' => $pages[$page]['label'],
            'previewRoute' => site_url($pages[$page]['previewRoute']),
            'sections' => $sections,
            'sectionOrder' => (new SiteContentModel())->sectionOrder($page),
            'movableSections' => SiteContentModel::movableSections($page),
            'adminSuccess' => session()->getFlashdata('adminSuccess'),
            'adminError' => session()->getFlashdata('adminError'),
            'formErrors' => session()->getFlashdata('formErrors') ?? [],
            'uploadErrors' => session()->getFlashdata('uploadErrors') ?? [],
            'heroImagePath' => (new SiteContentModel())->publicImagePath(
                'beranda.hero_image',
                'assets/images/gereja-bukit-pengharapan-udara.jpg',
            ),
            'profileImagePath' => (new SiteContentModel())->publicImagePath(
                'beranda.profile_image',
                'assets/images/gereja-bukit-pengharapan-depan.jpg',
            ),
            'galleryPhotos' => $galleryPhotos,
        ]);
    }

    public function updateImage()
    {
        helper('url');

        $slot = (string) $this->request->getPost('image_slot');
        $imageKeys = $this->homeImageKeys();
        if (! array_key_exists($slot, $imageKeys)) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $this->validate([
            'foto' => 'uploaded[foto]|max_size[foto,5120]|is_image[foto]|ext_in[foto,jpg,jpeg,png,webp]',
        ])) {
            return redirect()->to(site_url('admin/konten/beranda'))
                ->withInput()
                ->with('uploadErrors', $this->validator->getErrors());
        }

        try {
            $filename = (new PublicImageStorage())->store($this->request->getFile('foto'), 'site');
        } catch (RuntimeException $exception) {
            log_message('error', 'Unable to store home page image: {message}', ['message' => $exception->getMessage()]);

            return redirect()->to(site_url('admin/konten/beranda'))
                ->withInput()
                ->with('adminError', 'Foto belum berhasil diunggah. Periksa file dan izin folder, lalu coba lagi.');
        }

        $model = new SiteContentModel();
        $key = $imageKeys[$slot];
        $row = $model->find($key);
        $saved = $row === null
            ? $model->insert(['content_key' => $key, 'content_value' => $filename]) !== false
            : $model->update($key, ['content_value' => $filename]);
        if (! $saved) {
            log_message('error', 'Unable to save home page image {key}: {errors}', [
                'key' => $key,
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);
            $this->deleteStoredImage($filename);

            return redirect()->to(site_url('admin/konten/beranda'))
                ->with('adminError', 'Foto belum berhasil disimpan. Silakan coba lagi.');
        }

        $oldFilename = (string) ($row['content_value'] ?? '');
        $removedOldImage = $oldFilename === '' || $oldFilename === $filename || $this->deleteStoredImage($oldFilename);
        if (! $removedOldImage) {
            return redirect()->to(site_url('admin/konten/beranda'))
                ->with('adminError', 'Foto baru berhasil disimpan, tetapi foto lama belum dapat dihapus dari penyimpanan.');
        }

        return redirect()->to(site_url('admin/konten/beranda'))
            ->with('adminSuccess', 'Foto Beranda berhasil diperbarui.');
    }

    public function deleteImage()
    {
        helper('url');

        $slot = (string) $this->request->getPost('image_slot');
        $imageKeys = $this->homeImageKeys();
        if (! array_key_exists($slot, $imageKeys)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $model = new SiteContentModel();
        $key = $imageKeys[$slot];
        $row = $model->find($key);
        if ($row === null) {
            return redirect()->to(site_url('admin/konten/beranda'))
                ->with('adminSuccess', 'Foto bawaan sudah digunakan.');
        }

        if (! $model->delete($key)) {
            log_message('error', 'Unable to restore the default home page image for {key}: {errors}', [
                'key' => $key,
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            return redirect()->to(site_url('admin/konten/beranda'))
                ->with('adminError', 'Foto belum dapat dikembalikan ke gambar bawaan. Silakan coba lagi.');
        }

        if (! $this->deleteStoredImage((string) $row['content_value'])) {
            return redirect()->to(site_url('admin/konten/beranda'))
                ->with('adminError', 'Foto bawaan sudah dipulihkan, tetapi file foto lama belum dapat dihapus dari penyimpanan.');
        }

        return redirect()->to(site_url('admin/konten/beranda'))
            ->with('adminSuccess', 'Foto bawaan Beranda sudah dipulihkan.');
    }

    public function moveSection(string $page)
    {
        helper('url');

        $pages = SiteContentModel::editorPages();
        if (! array_key_exists($page, $pages)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $movableSections = SiteContentModel::movableSections($page);
        $move = explode(':', (string) $this->request->getPost('move'), 2);
        if (count($move) !== 2
            || ! array_key_exists($move[0], $movableSections)
            || ! in_array($move[1], ['up', 'down'], true)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $validationError = $this->pageContentValidationError($page);
        if ($validationError !== null) {
            $response = redirect()->to(site_url('admin/konten/' . $page))->withInput();
            foreach ($validationError as $key => $message) {
                $response->with($key, $message);
            }

            return $response;
        }

        if (! $this->persistPageContent($page)) {
            return redirect()->to(site_url('admin/konten/' . $page))
                ->withInput()
                ->with('adminError', 'Konten belum berhasil disimpan. Silakan coba lagi.');
        }

        $model = new SiteContentModel();
        $order = $model->sectionOrder($page);
        $index = array_search($move[0], $order, true);
        if ($index === false) {
            throw PageNotFoundException::forPageNotFound();
        }

        $nextIndex = $index + ($move[1] === 'up' ? -1 : 1);
        if (! array_key_exists($nextIndex, $order)) {
            return redirect()->to(site_url('admin/konten/' . $page));
        }

        [$order[$index], $order[$nextIndex]] = [$order[$nextIndex], $order[$index]];
        $key = $page . '.section_order';
        $row = $model->find($key);
        $data = [
            'content_key' => $key,
            'content_value' => json_encode($order, JSON_THROW_ON_ERROR),
        ];
        $saved = $row === null
            ? $model->insert($data) !== false
            : $model->update($key, $data);

        if (! $saved) {
            log_message('error', 'Unable to save section order for page {page}: {errors}', [
                'page' => $page,
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            return redirect()->to(site_url('admin/konten/' . $page))
                ->with('adminError', 'Urutan bagian belum berhasil disimpan. Silakan coba lagi.');
        }

        return redirect()->to(site_url('admin/konten/' . $page))
            ->with('adminSuccess', 'Urutan bagian halaman berhasil disimpan.');
    }

    public function save(string $page)
    {
        helper('url');

        $pages = SiteContentModel::editorPages();
        if (! array_key_exists($page, $pages)) {
            throw PageNotFoundException::forPageNotFound();
        }

        $validationError = $this->pageContentValidationError($page);
        if ($validationError !== null) {
            $response = redirect()->to(site_url('admin/konten/' . $page))->withInput();
            foreach ($validationError as $key => $message) {
                $response->with($key, $message);
            }

            return $response;
        }

        if (! $this->persistPageContent($page)) {
            return redirect()->to(site_url('admin/konten/' . $page))
                ->withInput()
                ->with('adminError', 'Konten belum berhasil disimpan. Silakan coba lagi.');
        }

        return redirect()->to(site_url('admin/konten/' . $page))
            ->with('adminSuccess', 'Konten halaman berhasil disimpan.');
    }

    private function pageContentValidationError(string $page): ?array
    {
        $rules = [];
        foreach (SiteContentModel::fieldsForPage($page) as $key => $field) {
            $canBeEmpty = $field['default'] === ''
                || in_array($key, ['board_rows', 'service_items', 'sector_items'], true);
            $rules['content_' . $key] = ($canBeEmpty ? 'permit_empty' : 'required') . '|max_length[2000]';
        }

        if (! $this->validate($rules)) {
            return ['formErrors' => $this->validator->getErrors()];
        }

        if (in_array($page, ['profil', 'profil-struktur-majelis'], true)
            && ! $this->hasValidMemberRows((string) $this->request->getPost('content_board_rows'))) {
            return ['adminError' => 'Setiap anggota majelis harus ditulis dalam format Nama | Jabatan.'];
        }

        return null;
    }

    private function persistPageContent(string $page): bool
    {
        $model = new SiteContentModel();
        $database = db_connect();
        $database->transBegin();

        foreach (SiteContentModel::fieldsForPage($page) as $key => $field) {
            $contentKey = SiteContentModel::editorContentPage($page) . '.' . $key;
            $contentValue = [
                'content_key' => $contentKey,
                'content_value' => trim((string) $this->request->getPost('content_' . $key)),
            ];
            $saved = $model->find($contentKey) === null
                ? $model->insert($contentValue) !== false
                : $model->update($contentKey, $contentValue);

            if (! $saved) {
                $database->transRollback();
                log_message('error', 'Unable to save site content {key}: {errors}', [
                    'key' => $contentKey,
                    'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
                ]);

                return false;
            }
        }

        if (! $database->transStatus()) {
            $database->transRollback();
            log_message('error', 'Unable to commit site content changes for page {page}.', ['page' => $page]);

            return false;
        }

        $database->transCommit();

        return true;
    }

    private function hasValidMemberRows(string $rows): bool
    {
        $lines = preg_split('/\R/u', $rows);
        foreach (is_array($lines) ? $lines : [] as $line) {
            $line = trim($line);
            if ($line === '') {
                continue;
            }

            $parts = array_map('trim', explode('|', $line, 2));
            if (count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
                return false;
            }
        }

        return true;
    }

    private function homeImageKeys(): array
    {
        return [
            'hero' => 'beranda.hero_image',
            'profile' => 'beranda.profile_image',
        ];
    }

    private function deleteStoredImage(string $filename): bool
    {
        if ($filename === '') {
            return true;
        }

        try {
            (new PublicImageStorage())->delete('site', $filename);
        } catch (RuntimeException $exception) {
            log_message('error', 'Unable to remove stored home page image {filename}: {message}', [
                'filename' => $filename,
                'message' => $exception->getMessage(),
            ]);

            return false;
        }

        return true;
    }
}
