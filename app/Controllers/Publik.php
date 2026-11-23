<?php

namespace App\Controllers;

use App\Models\WartaModel;
use App\Models\GalleryPhotoModel;
use App\Models\SiteContentModel;
use App\Models\ContactMessageModel;
use App\Models\WorshipScheduleModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Publik extends BaseController
{
    public function sejarah(): string
    {
        $content = (new SiteContentModel())->forPage('profil');

        return $this->renderPage('publik/profil_sejarah', $content['history_heading'], 'profil', [
            'sejarah' => $content['history_text'],
            'activeSubpage' => 'sejarah',
        ], false);
    }

    public function visiMisi(): string
    {
        $content = (new SiteContentModel())->forPage('profil');

        return $this->renderPage('publik/profil_visi_misi', $content['vision_heading'] . ' & ' . $content['mission_heading'], 'profil', [
            'visi' => $content['vision_text'],
            'misi' => $content['mission_text'],
            'activeSubpage' => 'visi-misi',
        ], false);
    }

    public function strukturMajelis(): string
    {
        $content = (new SiteContentModel())->forPage('profil');

        return $this->renderPage('publik/profil_struktur_majelis', $content['board_heading'], 'profil', [
            'strukturMajelis' => $this->parseMemberRows($content['board_rows']),
            'activeSubpage' => 'struktur-majelis',
        ], false);
    }

    public function jadwal(): string
    {
        return $this->renderPage('publik/jadwal', 'Jadwal Ibadah', 'jadwal', [
            'jadwal' => WartaModel::isDatabaseConfigured()
                ? (new WorshipScheduleModel())->upcomingForPublic()
                : [],
        ]);
    }

    public function warta(): string
    {
        return $this->renderPage('publik/warta', 'Warta & Berita', 'warta', [
            'berita' => WartaModel::isDatabaseConfigured()
                ? (new WartaModel())->publishedForPublic()
                : [],
        ]);
    }

    public function galeri(): string
    {
        $galleryModel = new GalleryPhotoModel();
        $fotoGaleri = GalleryPhotoModel::isDatabaseConfigured()
            ? $galleryModel->orderBy('created_at', 'DESC')
                ->orderBy('id', 'DESC')
                ->paginate(21)
            : [];

        return $this->renderPage('publik/galeri', 'Galeri Jemaat', 'galeri', [
            'fotoGaleri' => $fotoGaleri,
            'pager' => GalleryPhotoModel::isDatabaseConfigured() ? $galleryModel->pager : null,
        ]);
    }

    public function wartaDetail(string $slug): string
    {
        if (! WartaModel::isDatabaseConfigured()) {
            throw PageNotFoundException::forPageNotFound();
        }

        $berita = (new WartaModel())->findPublishedBySlug($slug);
        if ($berita === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->renderPage('publik/warta_detail', $berita['judul'], 'warta', [
            'berita' => $berita,
        ]);
    }

    public function wadahPelayanan(): string
    {
        $content = (new SiteContentModel())->forPage('pelayanan');
        $serviceNames = $this->parseTextLines($content['service_items']);

        return $this->renderPage('publik/pelayanan_wadah', $content['groups_heading'], 'pelayanan', [
            'wadahPelayanan' => array_map(
                static fn (string $nama): array => ['nama' => $nama],
                $serviceNames,
            ),
            'activeSubpage' => 'wadah',
        ], false);
    }

    public function sektorUnit(): string
    {
        $content = (new SiteContentModel())->forPage('pelayanan');
        $sektorUnit = array_map(
            static fn (string $unit): array => ['nama' => $unit],
            $this->parseTextLines($content['sector_items']),
        );

        return $this->renderPage('publik/pelayanan_sektor_unit', $content['sectors_heading'], 'pelayanan', [
            'sektorUnit' => $sektorUnit,
            'activeSubpage' => 'sektor-unit',
        ], false);
    }

    public function kontak(): string
    {
        helper('form');

        return $this->renderPage('publik/kontak', 'Kontak Jemaat', 'kontak', [
            'contactEmail' => config('Email')->recipients,
            'contactInboxEnabled' => WartaModel::isDatabaseConfigured(),
            'formErrors' => session()->getFlashdata('formErrors') ?? [],
            'contactSuccess' => session()->getFlashdata('contactSuccess'),
            'contactError' => session()->getFlashdata('contactError'),
        ]);
    }

    public function kirimPesan()
    {
        $rules = [
            'nama' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|max_length[254]',
            'subjek' => 'required|min_length[3]|max_length[120]',
            'pesan' => 'required|min_length[10]|max_length[5000]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('kontak'))
                ->withInput()
                ->with('formErrors', $this->validator->getErrors());
        }

        $senderName = trim((string) $this->request->getPost('nama'));
        $senderEmail = trim((string) $this->request->getPost('email'));
        $subject = trim((string) $this->request->getPost('subjek'));
        $body = trim((string) $this->request->getPost('pesan'));

        if (WartaModel::isDatabaseConfigured()) {
            $model = new ContactMessageModel();
            if ($model->insert([
                'sender_name' => $senderName,
                'sender_email' => $senderEmail,
                'subject' => $subject,
                'message' => $body,
                'status' => 'baru',
            ]) === false) {
                log_message('error', 'Unable to save contact message: {errors}', [
                    'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
                ]);

                return redirect()->to(site_url('kontak'))
                    ->withInput()
                    ->with('contactError', 'Pesan belum berhasil disimpan. Silakan coba lagi atau kirim email langsung.');
            }

            return redirect()->to(site_url('kontak'))
                ->with('contactSuccess', 'Terima kasih. Pesan Anda sudah diterima dan akan ditindaklanjuti oleh pengelola jemaat.');
        }

        $emailConfig = config('Email');
        if ($emailConfig->recipients === '') {
            return redirect()->to(site_url('kontak'))
                ->withInput()
                ->with('contactError', 'Kotak pesan belum dapat digunakan karena database belum dikonfigurasi. Silakan kirim email langsung.');
        }

        $emailMessage = service('email');
        $emailMessage->clear(true);
        $emailMessage->setFrom(
            $emailConfig->fromEmail !== '' ? $emailConfig->fromEmail : $emailConfig->recipients,
            $emailConfig->fromName !== '' ? $emailConfig->fromName : 'Jemaat GPM Bukit Pengharapan',
        );
        $emailMessage->setTo($emailConfig->recipients);
        $emailMessage->setReplyTo($senderEmail, $senderName);
        $emailMessage->setSubject('[Kontak Jemaat] ' . $subject);
        $emailMessage->setMessage("Nama: {$senderName}\nEmail: {$senderEmail}\n\nPesan:\n{$body}");

        if ($emailMessage->send()) {
            return redirect()->to(site_url('kontak'))
                ->with('contactSuccess', 'Terima kasih. Pesan Anda berhasil dikirim.');
        }

        log_message('error', 'Contact email delivery failed; check the configured email transport.');

        return redirect()->to(site_url('kontak'))
            ->withInput()
            ->with('contactError', 'Pesan belum dapat dikirim. Silakan coba melalui email langsung.');
    }

    private function renderPage(
        string $viewName,
        string $pageTitle,
        string $activePage,
        array $data = [],
        bool $useConfiguredPageTitle = true,
    ): string
    {
        helper('url');

        $contentModel = new SiteContentModel();
        $content = $contentModel->forPage($activePage);
        $contactContent = $contentModel->forPage('kontak');
        $content['shared_contact_heading'] = $contactContent['shared_contact_heading'];
        $content['shared_contact_intro'] = $contactContent['shared_contact_intro'];
        $content['shared_contact_button'] = $contactContent['shared_contact_button'];

        if ($useConfiguredPageTitle && ! isset($data['berita']) && isset($content['page_title'])) {
            $pageTitle = $content['page_title'];
        }

        return view($viewName, array_merge([
            'pageTitle' => $pageTitle,
            'activePage' => $activePage,
            'content' => $content,
        ], $data));
    }

    private function parseMemberRows(string $rows): array
    {
        $members = [];
        foreach ($this->parseTextLines($rows) as $row) {
            $parts = array_map('trim', explode('|', $row, 2));
            if (count($parts) !== 2 || $parts[0] === '' || $parts[1] === '') {
                log_message('error', 'Ignoring malformed member row in site content.');
                continue;
            }

            $members[] = [
                'nama' => $parts[0],
                'jabatan' => $parts[1],
            ];
        }

        return $members;
    }

    private function parseTextLines(string $text): array
    {
        $lines = preg_split('/\R/u', $text);

        return array_values(array_filter(
            array_map('trim', is_array($lines) ? $lines : []),
            static fn (string $line): bool => $line !== '',
        ));
    }
}