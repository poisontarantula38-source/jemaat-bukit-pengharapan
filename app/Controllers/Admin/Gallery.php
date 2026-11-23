<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PublicImageStorage;
use App\Models\GalleryPhotoModel;
use App\Models\SiteContentModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use RuntimeException;

class Gallery extends BaseController
{
    public function index(): string
    {
        helper(['form', 'url']);

        $model = new GalleryPhotoModel();
        $photos = $model->orderBy('created_at', 'DESC')
            ->orderBy('id', 'DESC')
            ->paginate(20);

        return view('admin/gallery/index', [
            'pageTitle' => 'Galeri Foto',
            'photos' => $photos,
            'pager' => $model->pager,
            'adminSuccess' => session()->getFlashdata('adminSuccess'),
            'adminError' => session()->getFlashdata('adminError'),
            'uploadErrors' => session()->getFlashdata('uploadErrors') ?? [],
        ]);
    }

    public function upload()
    {
        helper('url');
        $returnPage = $this->returnPage();
        $returnUrl = $this->galleryReturnUrl($returnPage);

        $rules = [
            'caption' => 'required|min_length[2]|max_length[180]',
            'alt_text' => 'permit_empty|max_length[250]',
            'foto' => 'uploaded[foto]|max_size[foto,5120]|is_image[foto]|ext_in[foto,jpg,jpeg,png,webp]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to($returnUrl)
                ->withInput()
                ->with('uploadErrors', $this->validator->getErrors());
        }

        $file = $this->request->getFile('foto');
        try {
            $filename = (new PublicImageStorage())->store($file, 'gallery');
        } catch (RuntimeException $exception) {
            log_message('error', 'Gallery upload failed: {message}', ['message' => $exception->getMessage()]);

            return redirect()->to($returnUrl)
                ->withInput()
                ->with('adminError', 'Foto belum berhasil diunggah. Periksa file dan izin folder, lalu coba lagi.');
        }

        $caption = trim((string) $this->request->getPost('caption'));
        $altText = trim((string) $this->request->getPost('alt_text')) ?: $caption;
        $model = new GalleryPhotoModel();
        if (! $model->insert([
            'filename' => $filename,
            'caption' => $caption,
            'alt_text' => $altText,
        ])) {
            log_message('error', 'Unable to save gallery photo metadata: {errors}', [
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            try {
                (new PublicImageStorage())->delete('gallery', $filename);
            } catch (RuntimeException $exception) {
                log_message('error', 'Unable to remove unsaved gallery image {filename}: {message}', [
                    'filename' => $filename,
                    'message' => $exception->getMessage(),
                ]);
            }

            return redirect()->to($returnUrl)
                ->withInput()
                ->with('adminError', 'Foto belum berhasil disimpan ke galeri. Silakan coba lagi.');
        }

        return redirect()->to($returnUrl)
            ->with('adminSuccess', 'Foto berhasil ditambahkan ke galeri.');
    }

    public function edit(int $id): string
    {
        helper(['form', 'url']);

        $photo = (new GalleryPhotoModel())->find($id);
        if ($photo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return view('admin/gallery/edit', [
            'pageTitle' => 'Ubah Foto',
            'photo' => $photo,
            'imageSource' => $this->imageSource($photo),
            'returnPage' => $this->returnPage(),
            'formErrors' => session()->getFlashdata('uploadErrors') ?? [],
            'adminError' => session()->getFlashdata('adminError'),
        ]);
    }

    public function update(int $id)
    {
        helper('url');
        $returnPage = $this->returnPage();
        $editUrl = $this->galleryEditUrl($id, $returnPage);

        $model = new GalleryPhotoModel();
        $photo = $model->find($id);
        if ($photo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'caption' => 'required|min_length[2]|max_length[180]',
            'alt_text' => 'permit_empty|max_length[250]',
        ];
        $file = $this->request->getFile('foto');
        $hasNewImage = $file !== null && $file->getError() !== UPLOAD_ERR_NO_FILE;
        if ($hasNewImage) {
            $rules['foto'] = 'uploaded[foto]|max_size[foto,5120]|is_image[foto]|ext_in[foto,jpg,jpeg,png,webp]';
        }

        if (! $this->validate($rules)) {
            return redirect()->to($editUrl)
                ->withInput()
                ->with('uploadErrors', $this->validator->getErrors());
        }

        $newFilename = null;
        if ($hasNewImage) {
            try {
                $newFilename = (new PublicImageStorage())->store($file, 'gallery');
            } catch (RuntimeException $exception) {
                log_message('error', 'Unable to store replacement gallery image: {message}', ['message' => $exception->getMessage()]);

                return redirect()->to($editUrl)
                    ->withInput()
                    ->with('adminError', 'Foto belum berhasil diunggah. Periksa file dan izin folder, lalu coba lagi.');
            }
        }

        $data = [
            'caption' => trim((string) $this->request->getPost('caption')),
            'alt_text' => trim((string) $this->request->getPost('alt_text')) ?: trim((string) $this->request->getPost('caption')),
        ];
        if ($newFilename !== null) {
            $data['filename'] = $newFilename;
        }

        if (! $model->update($id, $data)) {
            log_message('error', 'Unable to update gallery photo ID {id}: {errors}', [
                'id' => $id,
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);
            if ($newFilename !== null) {
                try {
                    (new PublicImageStorage())->delete('gallery', $newFilename);
                } catch (RuntimeException $exception) {
                    log_message('error', 'Unable to remove unsaved gallery image {filename}: {message}', [
                        'filename' => $newFilename,
                        'message' => $exception->getMessage(),
                    ]);
                }
            }

            return redirect()->to($editUrl)
                ->withInput()
                ->with('adminError', 'Perubahan foto belum berhasil disimpan. Periksa kembali isian lalu coba lagi.');
        }

        $removedOldImage = true;
        if ($newFilename !== null) {
            try {
                (new PublicImageStorage())->delete('gallery', (string) $photo['filename']);
            } catch (RuntimeException $exception) {
                log_message('error', 'Unable to remove replaced gallery image for photo ID {id}: {message}', [
                    'id' => $id,
                    'message' => $exception->getMessage(),
                ]);
                $removedOldImage = false;
            }
        }

        if (! $removedOldImage) {
            return redirect()->to($this->galleryReturnUrl($returnPage))
                ->with('adminError', 'Foto baru berhasil disimpan, tetapi file foto lama belum dapat dihapus dari penyimpanan.');
        }

        return redirect()->to($this->galleryReturnUrl($returnPage))
            ->with('adminSuccess', 'Foto galeri berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        helper('url');
        $returnUrl = $this->galleryReturnUrl($this->returnPage());

        $model = new GalleryPhotoModel();
        $photo = $model->find($id);
        if ($photo === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $model->delete($id)) {
            log_message('error', 'Unable to delete gallery photo record ID {id}: {errors}', [
                'id' => $id,
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            return redirect()->to($returnUrl)
                ->with('adminError', 'Foto belum berhasil dihapus. Silakan coba lagi.');
        }

        try {
            (new PublicImageStorage())->delete('gallery', (string) $photo['filename']);
        } catch (RuntimeException $exception) {
            log_message('error', 'Unable to remove gallery image for photo ID {id}: {message}', [
                'id' => $id,
                'message' => $exception->getMessage(),
            ]);

            return redirect()->to($returnUrl)
                ->with('adminError', 'Foto sudah dihapus dari galeri, tetapi file lama belum dapat dibersihkan. Periksa izin folder.');
        }

        return redirect()->to($returnUrl)
            ->with('adminSuccess', 'Foto berhasil dihapus dari galeri.');
    }

    private function imageSource(array $photo): string
    {
        $filename = (string) ($photo['filename'] ?? '');
        if (basename($filename) !== $filename
            || ! preg_match('/\A[a-z0-9._-]+\.(?:jpe?g|png|webp)\z/i', $filename)) {
            return '';
        }

        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'gallery' . DIRECTORY_SEPARATOR . $filename;

        return is_file($path) ? base_url('uploads/gallery/' . $filename) : '';
    }

    private function returnPage(): string
    {
        $page = (string) ($this->request->getPost('return_page') ?? $this->request->getGet('return_page') ?? '');

        return array_key_exists($page, SiteContentModel::editorPages()) ? $page : '';
    }

    private function galleryReturnUrl(string $page): string
    {
        return site_url($page === '' ? 'admin/galeri' : 'admin/konten/' . $page);
    }

    private function galleryEditUrl(int $id, string $page): string
    {
        $url = site_url('admin/galeri/' . $id . '/ubah');

        return $page === '' ? $url : $url . '?return_page=' . rawurlencode($page);
    }
}
