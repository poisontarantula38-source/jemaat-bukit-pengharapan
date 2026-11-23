<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Libraries\PublicImageStorage;
use App\Models\WartaModel;
use CodeIgniter\Exceptions\PageNotFoundException;
use RuntimeException;

class Warta extends BaseController
{
    public function index(): string
    {
        helper(['form', 'url']);

        return view('admin/warta/index', [
            'pageTitle' => 'Warta & Berita',
            'warta' => (new WartaModel())->orderBy('updated_at', 'DESC')->findAll(),
            'adminSuccess' => session()->getFlashdata('adminSuccess'),
            'adminError' => session()->getFlashdata('adminError'),
        ]);
    }

    public function newArticle(): string
    {
        helper(['form', 'url']);

        return $this->renderForm();
    }

    public function create()
    {
        return $this->saveArticle();
    }

    public function edit(int $id): string
    {
        helper(['form', 'url']);

        $article = (new WartaModel())->find($id);
        if ($article === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->renderForm($article);
    }

    public function update(int $id)
    {
        return $this->saveArticle($id);
    }

    public function delete(int $id)
    {
        helper('url');

        $model = new WartaModel();
        $article = $model->find($id);
        if ($article === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $model->delete($id)) {
            log_message('error', 'Unable to delete Warta article ID {id}.', ['id' => $id]);

            return redirect()->to(site_url('admin/warta'))
                ->with('adminError', 'Warta belum berhasil dihapus. Silakan coba lagi.');
        }

        if (! empty($article['image_filename'])) {
            try {
                (new PublicImageStorage())->delete('warta', (string) $article['image_filename']);
            } catch (RuntimeException $exception) {
                log_message('error', 'Unable to remove the image for deleted Warta article ID {id}: {message}', [
                    'id' => $id,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return redirect()->to(site_url('admin/warta'))
            ->with('adminSuccess', 'Warta berhasil dihapus.');
    }

    private function renderForm(?array $article = null): string
    {
        return view('admin/warta/form', [
            'pageTitle' => $article === null ? 'Tambah Warta' : 'Ubah Warta',
            'article' => $article ?? [],
            'formErrors' => session()->getFlashdata('formErrors') ?? [],
            'adminError' => session()->getFlashdata('adminError'),
            'imageSource' => $this->articleImageSource($article),
        ]);
    }

    private function saveArticle(?int $id = null)
    {
        helper('url');

        $model = new WartaModel();
        $article = $id === null ? null : $model->find($id);
        if ($id !== null && $article === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'judul' => 'required|min_length[3]|max_length[180]',
            'kategori' => 'permit_empty|max_length[80]',
            'ringkasan' => 'permit_empty|max_length[600]',
            'isi' => 'required|min_length[10]|max_length[50000]',
            'status' => 'required|in_list[draft,published]',
            'foto_alt' => 'permit_empty|max_length[250]',
        ];
        $image = $this->request->getFile('foto');
        $hasNewImage = $image !== null && $image->getError() !== UPLOAD_ERR_NO_FILE;
        if ($hasNewImage) {
            $rules['foto'] = 'uploaded[foto]|max_size[foto,5120]|is_image[foto]|ext_in[foto,jpg,jpeg,png,webp]';
        }

        if (! $this->validate($rules)) {
            $redirect = $id === null
                ? site_url('admin/warta/tambah')
                : site_url('admin/warta/' . $id . '/ubah');

            return redirect()->to($redirect)
                ->withInput()
                ->with('formErrors', $this->validator->getErrors());
        }

        $title = trim((string) $this->request->getPost('judul'));
        helper('text');
        $baseSlug = url_title($title, '-', true);
        if ($baseSlug === '') {
            $baseSlug = 'warta';
        }

        $slug = $baseSlug;
        $suffix = 2;
        while ($model->slugExists($slug, $id)) {
            $slug = $baseSlug . '-' . $suffix;
            $suffix++;
        }

        $isPublished = $this->request->getPost('status') === 'published';
        $storedImage = null;
        if ($hasNewImage) {
            try {
                $storedImage = (new PublicImageStorage())->store($image, 'warta');
            } catch (RuntimeException $exception) {
                log_message('error', 'Unable to store Warta image: {message}', ['message' => $exception->getMessage()]);

                return redirect()->to($this->formUrl($id))
                    ->withInput()
                    ->with('adminError', 'Foto belum berhasil diunggah. Periksa file dan izin folder, lalu coba lagi.');
            }
        }

        $removeImage = $this->request->getPost('hapus_foto') === '1' && ! $hasNewImage;
        $imageFilename = $storedImage ?? ($removeImage ? null : ($article['image_filename'] ?? null));
        $imageAlt = $imageFilename === null
            ? null
            : (trim((string) $this->request->getPost('foto_alt')) ?: $title);
        $data = [
            'title' => $title,
            'slug' => $slug,
            'category' => trim((string) $this->request->getPost('kategori')) ?: null,
            'summary' => trim((string) $this->request->getPost('ringkasan')) ?: null,
            'image_filename' => $imageFilename,
            'image_alt' => $imageAlt,
            'content' => trim((string) $this->request->getPost('isi')),
            'is_published' => $isPublished ? 1 : 0,
            'published_at' => $isPublished
                ? ($article['published_at'] ?? date('Y-m-d H:i:s'))
                : null,
        ];

        $saved = $id === null
            ? $model->insert($data)
            : $model->update($id, $data);

        if (! $saved) {
            log_message('error', 'Unable to save Warta article: {errors}', [
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            $redirect = $id === null
                ? site_url('admin/warta/tambah')
                : site_url('admin/warta/' . $id . '/ubah');
            if ($storedImage !== null) {
                try {
                    (new PublicImageStorage())->delete('warta', $storedImage);
                } catch (RuntimeException $exception) {
                    log_message('error', 'Unable to remove unsaved Warta image {filename}: {message}', [
                        'filename' => $storedImage,
                        'message' => $exception->getMessage(),
                    ]);
                }
            }

            return redirect()->to($redirect)
                ->withInput()
                ->with('adminError', 'Warta belum berhasil disimpan. Periksa kembali datanya.');
        }

        $oldImage = (string) ($article['image_filename'] ?? '');
        if ($oldImage !== '' && $imageFilename !== $oldImage) {
            try {
                (new PublicImageStorage())->delete('warta', $oldImage);
            } catch (RuntimeException $exception) {
                log_message('error', 'Unable to remove replaced Warta image {filename}: {message}', [
                    'filename' => $oldImage,
                    'message' => $exception->getMessage(),
                ]);
            }
        }

        return redirect()->to(site_url('admin/warta'))
            ->with('adminSuccess', 'Warta berhasil disimpan.');
    }

    private function formUrl(?int $id): string
    {
        return $id === null
            ? site_url('admin/warta/tambah')
            : site_url('admin/warta/' . $id . '/ubah');
    }

    private function articleImageSource(?array $article): string
    {
        $filename = (string) ($article['image_filename'] ?? '');
        if (basename($filename) !== $filename
            || ! preg_match('/\A[a-z0-9._-]+\.(?:jpe?g|png|webp)\z/i', $filename)) {
            return '';
        }

        $path = FCPATH . 'uploads' . DIRECTORY_SEPARATOR . 'warta' . DIRECTORY_SEPARATOR . $filename;

        return is_file($path) ? base_url('uploads/warta/' . $filename) : '';
    }
}
