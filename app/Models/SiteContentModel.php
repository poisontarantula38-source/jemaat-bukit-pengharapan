<?php

namespace App\Models;

use CodeIgniter\Model;
use Config\Database;

class SiteContentModel extends Model
{
    private const HOMEPAGE_SECTIONS = [
        'hero' => 'Bagian pembuka',
        'profile' => 'Profil singkat',
        'info' => 'Informasi dan warta',
        'gallery' => 'Galeri di beranda',
        'portal' => 'Portal dan kunjungan',
    ];

    protected $table = 'site_content';
    protected $primaryKey = 'content_key';
    protected $returnType = 'array';
    protected $allowedFields = ['content_key', 'content_value'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public static function pages(): array
    {
        return [
            'beranda' => [
                'label' => 'Beranda',
                'sections' => [
                    'Bagian pembuka' => [
                        'hero_title' => ['label' => 'Judul utama', 'default' => 'Jemaat GPM Bukit Pengharapan', 'rows' => 1],
                        'hero_intro' => ['label' => 'Pengantar', 'default' => 'Selamat datang. Temukan informasi ibadah, warta jemaat, dan informasi kunjungan di sini.', 'rows' => 3],
                    ],
                    'Profil singkat' => [
                        'profile_heading' => ['label' => 'Judul bagian', 'default' => 'Mengenal Jemaat', 'rows' => 1],
                        'profile_intro' => [
                            'label' => 'Teks pengantar',
                            'default' => "Jemaat GPM Bukit Pengharapan secara administratif berada di Negeri Latuhalat, Kecamatan Nusaniwe, Kota Ambon, dengan wilayah pelayanan seluas kurang lebih 0,85 km² yang terletak di bagian barat daya Kota Ambon. Wilayah pelayanan jemaat ini berbatasan langsung dengan Jemaat GPM Seilale di sebelah utara, Jemaat GPM Latuhalat di sebelah selatan, Jemaat GPM Waimahu di sebelah barat, serta Jemaat GPM Seilale dan Latuhalat di sebelah timur. Dengan kondisi topografi yang didominasi daerah bergelombang hingga terjal, jemaat ini resmi dilembagakan berdiri sendiri terlepas dari Jemaat GPM Latuhalat berdasarkan Surat Keputusan MPH Sinode GPM.\n\nBerdasarkan data per Januari 2026, Jemaat GPM Bukit Pengharapan Latuhalat memiliki sebanyak 172 Kepala Keluarga (KK) dengan total 712 jiwa warga jemaat. Seluruh warga jemaat tersebut terdistribusi secara merata ke dalam 5 sektor pelayanan dan 10 unit pelayanan, yaitu Sektor Moria, Sektor Horeb, Sektor Yabok, Sektor Hermon, dan Sektor Karmel. Penataan struktur wilayah ini dirancang untuk mengoptimalkan pelayanan pastoral, pembinaan kategorial, serta penggembalaan umat secara berkesinambungan.",
                            'rows' => 10,
                        ],
                    ],
                    'Informasi dan warta' => [
                        'info_heading' => ['label' => 'Judul bagian informasi', 'default' => 'Informasi jemaat', 'rows' => 1],
                        'info_intro' => ['label' => 'Pengantar informasi', 'default' => 'Jadwal ibadah dan kabar terbaru dalam satu tempat.', 'rows' => 2],
                        'schedule_heading' => ['label' => 'Judul jadwal', 'default' => 'Jadwal Ibadah', 'rows' => 1],
                        'schedule_empty_heading' => ['label' => 'Judul saat jadwal kosong', 'default' => 'Jadwal belum tersedia', 'rows' => 1],
                        'schedule_empty_text' => ['label' => 'Teks saat jadwal kosong', 'default' => 'Jadwal ibadah resmi belum dipublikasikan.', 'rows' => 2],
                        'warta_heading' => ['label' => 'Judul sorotan warta', 'default' => 'Sorotan Warta & Berita', 'rows' => 1],
                        'warta_intro' => ['label' => 'Pengantar sorotan warta', 'default' => 'Kabar terbaru dari kehidupan jemaat.', 'rows' => 2],
                        'warta_empty_heading' => ['label' => 'Judul saat belum ada warta', 'default' => 'Belum ada warta yang dipublikasikan', 'rows' => 1],
                        'warta_empty_text' => ['label' => 'Teks saat belum ada warta', 'default' => 'Sorotan berita akan muncul di sini setelah ada publikasi resmi.', 'rows' => 2],
                    ],
                    'Galeri di beranda' => [
                        'gallery_heading' => ['label' => 'Judul bagian galeri', 'default' => 'Galeri Jemaat', 'rows' => 1],
                        'gallery_intro' => ['label' => 'Pengantar galeri', 'default' => 'Dokumentasi kegiatan dan persekutuan Jemaat Bukit Pengharapan.', 'rows' => 2],
                        'gallery_empty_heading' => ['label' => 'Judul saat belum ada foto', 'default' => 'Belum ada foto galeri', 'rows' => 1],
                        'gallery_empty_text' => ['label' => 'Teks saat belum ada foto', 'default' => 'Foto kegiatan jemaat akan ditampilkan di sini setelah diunggah.', 'rows' => 2],
                    ],
                    'Portal dan kunjungan' => [
                        'portal_heading' => ['label' => 'Judul portal', 'default' => 'Jelajahi portal jemaat', 'rows' => 1],
                        'portal_intro' => ['label' => 'Pengantar portal', 'default' => 'Pilih informasi yang ingin Anda kunjungi.', 'rows' => 2],
                        'portal_profile' => ['label' => 'Deskripsi portal Profil', 'default' => 'Mengenal Jemaat Bukit Pengharapan dan pelayanannya.', 'rows' => 2],
                        'portal_schedule' => ['label' => 'Deskripsi portal Jadwal', 'default' => 'Informasi ibadah dan kegiatan pelayanan.', 'rows' => 2],
                        'portal_service' => ['label' => 'Deskripsi portal Pelayanan', 'default' => 'Informasi wadah pelayanan, sektor, dan unit jemaat.', 'rows' => 2],
                        'portal_gallery' => ['label' => 'Deskripsi portal Galeri', 'default' => 'Dokumentasi kegiatan dan persekutuan.', 'rows' => 2],
                        'portal_contact' => ['label' => 'Deskripsi portal Kontak', 'default' => 'Informasi untuk menghubungi dan mengunjungi gereja.', 'rows' => 2],
                        'visit_heading' => ['label' => 'Judul informasi kunjungan', 'default' => 'Informasi kunjungan', 'rows' => 1],
                        'visit_intro' => ['label' => 'Teks lokasi kunjungan', 'default' => 'Gereja Elpido, Latuhalat, Ambon', 'rows' => 2],
                        'shared_contact_button' => ['label' => 'Teks tombol menuju halaman Kontak', 'default' => 'Lihat peta & kirim pesan', 'rows' => 1],
                    ],
                ],
            ],
            'profil' => [
                'label' => 'Profil',
                'sections' => [
                    'Sejarah, visi, dan misi' => [
                        'history_heading' => ['label' => 'Judul sejarah', 'default' => 'Sejarah Jemaat', 'rows' => 1],
                        'history_text' => ['label' => 'Teks sejarah', 'default' => '', 'rows' => 5],
                        'history_empty_heading' => ['label' => 'Judul saat sejarah kosong', 'default' => 'Sejarah jemaat belum tersedia', 'rows' => 1],
                        'history_empty_text' => ['label' => 'Teks saat sejarah kosong', 'default' => 'Narasi sejarah resmi Jemaat Bukit Pengharapan belum dipublikasikan.', 'rows' => 2],
                        'vision_heading' => ['label' => 'Judul visi', 'default' => 'Visi', 'rows' => 1],
                        'vision_text' => ['label' => 'Teks visi', 'default' => '', 'rows' => 3],
                        'vision_empty_heading' => ['label' => 'Judul saat visi kosong', 'default' => 'Visi belum tersedia', 'rows' => 1],
                        'vision_empty_text' => ['label' => 'Teks saat visi kosong', 'default' => 'Visi resmi jemaat belum dicantumkan.', 'rows' => 2],
                        'mission_heading' => ['label' => 'Judul misi', 'default' => 'Misi', 'rows' => 1],
                        'mission_text' => ['label' => 'Teks misi', 'default' => '', 'rows' => 3],
                        'mission_empty_heading' => ['label' => 'Judul saat misi kosong', 'default' => 'Misi belum tersedia', 'rows' => 1],
                        'mission_empty_text' => ['label' => 'Teks saat misi kosong', 'default' => 'Misi resmi jemaat belum dicantumkan.', 'rows' => 2],
                    ],
                    'Struktur majelis' => [
                        'board_heading' => ['label' => 'Judul struktur majelis', 'default' => 'Struktur Majelis', 'rows' => 1],
                        'board_intro' => ['label' => 'Pengantar struktur majelis', 'default' => 'Nama dan susunan pelayanan majelis resmi belum dipublikasikan.', 'rows' => 2],
                        'board_empty_heading' => ['label' => 'Judul saat struktur belum diisi', 'default' => 'Struktur majelis belum tersedia', 'rows' => 1],
                        'board_rows' => [
                            'label' => 'Daftar anggota (Nama | Jabatan, satu orang per baris)',
                            'default' => "Pdt F. Lasetira | Ketua Majelis Jemaat\nPenatua Pieter Panus Risamena | Wakil KMJ\nPenatua Herlina Jolanda Lekatompessy | Sekretaris\nPenatua Reymond Lekatompessy | Wakil Sekretaris\nPenatua Johana Tuhumury | Bendahara\nDiaken Ivon Tuhumury | Wakil Bendahara\nPenatua Anna Tresye Salamor | Majelis Anggota\nPenatua Yana Pattipeilohy | Majelis Anggota\nPenatua Rocky Stenly Tuhumury | Majelis Anggota\nPenatua Ralff Joseph Lekatompessy | Majelis Anggota\nPenatua Deselina Soplantila | Majelis Anggota\nPenatua Marsello Stelin Lekatompessy | Majelis Anggota\nDiaken Anna Lusiana Benjamin | Majelis Anggota\nDiaken Linda Dahoklory | Majelis Anggota\nDiaken Josias Litamahuputty | Majelis Anggota\nDiaken Jondri Tuhumury | Majelis Anggota\nDiaken Jembris Tuhumury | Majelis Anggota\nDiaken Rizky Thomas Tuhumury | Majelis Anggota\nDiaken Joice Hana Imelda Tuhumury | Majelis Anggota\nDiaken Grasella Tuhumury | Majelis Anggota\nDiaken Farida Nanuru | Majelis Anggota",
                            'rows' => 16,
                            'help' => 'Gunakan tanda | untuk memisahkan nama dan jabatan. Baris kosong akan diabaikan.',
                        ],
                    ],
                ],
            ],
            'jadwal' => [
                'label' => 'Jadwal',
                'sections' => [
                    'Halaman jadwal' => [
                        'page_title' => ['label' => 'Judul halaman', 'default' => 'Jadwal Ibadah', 'rows' => 1],
                        'page_intro' => ['label' => 'Pengantar halaman', 'default' => 'Waktu dan lokasi ibadah Jemaat Bukit Pengharapan.', 'rows' => 2],
                        'schedule_heading' => ['label' => 'Judul daftar jadwal', 'default' => 'Jadwal mendatang', 'rows' => 1],
                        'schedule_empty_heading' => ['label' => 'Judul saat jadwal kosong', 'default' => 'Jadwal ibadah belum tersedia', 'rows' => 1],
                        'schedule_empty_text' => ['label' => 'Teks saat jadwal kosong', 'default' => 'Jadwal resmi akan ditampilkan setelah informasi hari, waktu, lokasi, dan pelayan dikonfirmasi.', 'rows' => 2],
                    ],
                ],
            ],
            'pelayanan' => [
                'label' => 'Pelayanan',
                'sections' => [
                    'Wadah pelayanan dan sektor' => [
                        'groups_heading' => ['label' => 'Judul wadah pelayanan', 'default' => 'Wadah Pelayanan', 'rows' => 1],
                        'groups_intro' => ['label' => 'Pengantar wadah pelayanan', 'default' => 'Pilih wadah untuk melihat informasi pelayanan.', 'rows' => 2],
                        'groups_empty_heading' => ['label' => 'Judul saat daftar wadah kosong', 'default' => 'Wadah pelayanan belum tersedia', 'rows' => 1],
                        'groups_empty_text' => ['label' => 'Teks saat daftar wadah kosong', 'default' => 'Informasi wadah pelayanan resmi belum dipublikasikan.', 'rows' => 2],
                        'service_items' => [
                            'label' => 'Daftar ibadah dan kegiatan (satu per baris)',
                            'default' => "Ibadah Hari Minggu (IHM)\nIbadah Kemitraan (Laki-laki/Suami dan Perempuan/Istri)\nIbadah Wadah Pelayanan Anak (SMTPI)\nIbadah Angkatan Muda GPM (AMGPM)\nSidang Jemaat (agenda tahunan)",
                            'rows' => 5,
                            'help' => 'Masukkan satu nama ibadah atau agenda pada setiap baris.',
                        ],
                        'sectors_heading' => ['label' => 'Judul sektor dan unit', 'default' => 'Sektor & Unit Pelayanan', 'rows' => 1],
                        'sector_items' => [
                            'label' => 'Daftar sektor dan unit (satu sektor per baris)',
                            'default' => "Moria — Unit 1 Moria & Unit 2 Moria (144 jiwa)\nHoreb — Unit 1 Horeb & Unit 2 Horeb (123 jiwa)\nYabok — Unit 1 Yabok & Unit 2 Yabok (117 jiwa)\nHermon — Unit 1 Hermon & Unit 2 Hermon (141 jiwa)\nKarmel — Unit 1 Karmel & Unit 2 Karmel (141 jiwa)",
                            'rows' => 5,
                            'help' => 'Masukkan satu baris per sektor. Sesuaikan nama unit dan jumlah jiwa dengan data resmi terbaru.',
                        ],
                        'sectors_empty_heading' => ['label' => 'Judul saat daftar sektor kosong', 'default' => 'Informasi sektor dan unit belum tersedia', 'rows' => 1],
                        'sectors_empty_text' => ['label' => 'Teks saat daftar sektor kosong', 'default' => 'Daftar sektor dan unit pelayanan resmi belum dipublikasikan.', 'rows' => 2],
                    ],
                ],
            ],
            'warta' => [
                'label' => 'Warta',
                'sections' => [
                    'Halaman warta' => [
                        'page_title' => ['label' => 'Judul halaman', 'default' => 'Warta & Berita', 'rows' => 1],
                        'page_intro' => ['label' => 'Pengantar halaman', 'default' => 'Pengumuman dan kabar terbaru dari Jemaat Bukit Pengharapan.', 'rows' => 2],
                        'empty_heading' => ['label' => 'Judul saat belum ada warta', 'default' => 'Belum ada warta yang dipublikasikan', 'rows' => 1],
                        'empty_text' => ['label' => 'Teks saat belum ada warta', 'default' => 'Warta dan berita jemaat akan ditampilkan di halaman ini setelah tersedia.', 'rows' => 2],
                    ],
                ],
            ],
            'kontak' => [
                'label' => 'Kontak',
                'sections' => [
                    'Halaman kontak' => [
                        'page_title' => ['label' => 'Judul halaman', 'default' => 'Kontak Jemaat', 'rows' => 1],
                        'page_intro' => ['label' => 'Pengantar halaman', 'default' => 'Kirim pesan atau temukan lokasi Gereja Elpido, Latuhalat, Ambon.', 'rows' => 2],
                        'form_heading' => ['label' => 'Judul formulir pesan', 'default' => 'Kirim pesan', 'rows' => 1],
                        'location_heading' => ['label' => 'Judul lokasi', 'default' => 'Lokasi Jemaat', 'rows' => 1],
                        'location_text' => ['label' => 'Teks lokasi', 'default' => 'Gereja Elpido, Latuhalat, Ambon', 'rows' => 2],
                        'shared_contact_heading' => ['label' => 'Judul lokasi di bagian bawah situs', 'default' => 'Kontak Jemaat', 'rows' => 1],
                        'shared_contact_intro' => ['label' => 'Alamat di bagian bawah situs', 'default' => 'Gereja Elpido, Latuhalat, Ambon', 'rows' => 2],
                        'shared_contact_button' => ['label' => 'Tombol menuju halaman kontak', 'default' => 'Lihat peta & kirim pesan', 'rows' => 1],
                    ],
                ],
            ],
            'galeri' => [
                'label' => 'Galeri',
                'sections' => [
                    'Halaman galeri' => [
                        'page_title' => ['label' => 'Judul halaman', 'default' => 'Galeri Jemaat', 'rows' => 1],
                        'page_intro' => ['label' => 'Pengantar halaman', 'default' => 'Kumpulan foto dan momen pelayanan Jemaat Bukit Pengharapan.', 'rows' => 2],
                        'empty_heading' => ['label' => 'Judul saat belum ada foto', 'default' => 'Belum ada foto galeri', 'rows' => 1],
                        'empty_text' => ['label' => 'Teks saat belum ada foto', 'default' => 'Dokumentasi jemaat akan ditampilkan di sini setelah foto diunggah.', 'rows' => 2],
                    ],
                ],
            ],
        ];
    }

    public static function editorPages(): array
    {
        $pages = self::pages();
        $profileSections = $pages['profil']['sections'];
        $historyFields = $profileSections['Sejarah, visi, dan misi'];
        $boardFields = $profileSections['Struktur majelis'];
        $serviceFields = $pages['pelayanan']['sections']['Wadah pelayanan dan sektor'];

        return [
            'beranda' => array_merge($pages['beranda'], [
                'contentPage' => 'beranda',
                'previewRoute' => '/',
            ]),
            'profil-sejarah' => [
                'label' => 'Sejarah Jemaat',
                'contentPage' => 'profil',
                'previewRoute' => 'profil/sejarah',
                'sections' => [
                    'Sejarah Jemaat' => self::selectFields($historyFields, [
                        'history_heading',
                        'history_text',
                        'history_empty_heading',
                        'history_empty_text',
                    ]),
                ],
            ],
            'profil-visi-misi' => [
                'label' => 'Visi & Misi',
                'contentPage' => 'profil',
                'previewRoute' => 'profil/visi-misi',
                'sections' => [
                    'Visi' => self::selectFields($historyFields, [
                        'vision_heading',
                        'vision_text',
                        'vision_empty_heading',
                        'vision_empty_text',
                    ]),
                    'Misi' => self::selectFields($historyFields, [
                        'mission_heading',
                        'mission_text',
                        'mission_empty_heading',
                        'mission_empty_text',
                    ]),
                ],
            ],
            'profil-struktur-majelis' => [
                'label' => 'Struktur Majelis',
                'contentPage' => 'profil',
                'previewRoute' => 'profil/struktur-majelis',
                'sections' => [
                    'Struktur Majelis' => $boardFields,
                ],
            ],
            'jadwal' => array_merge($pages['jadwal'], [
                'contentPage' => 'jadwal',
                'previewRoute' => 'jadwal',
            ]),
            'pelayanan-wadah' => [
                'label' => 'Wadah Pelayanan',
                'contentPage' => 'pelayanan',
                'previewRoute' => 'pelayanan/wadah',
                'sections' => [
                    'Wadah Pelayanan' => self::selectFields($serviceFields, [
                        'groups_heading',
                        'groups_intro',
                        'groups_empty_heading',
                        'groups_empty_text',
                        'service_items',
                    ]),
                ],
            ],
            'pelayanan-sektor-unit' => [
                'label' => 'Sektor & Unit Pelayanan',
                'contentPage' => 'pelayanan',
                'previewRoute' => 'pelayanan/sektor-unit',
                'sections' => [
                    'Sektor & Unit Pelayanan' => self::selectFields($serviceFields, [
                        'sectors_heading',
                        'sectors_empty_heading',
                        'sectors_empty_text',
                        'sector_items',
                    ]),
                ],
            ],
            'warta' => array_merge($pages['warta'], [
                'contentPage' => 'warta',
                'previewRoute' => 'warta',
            ]),
            'galeri' => array_merge($pages['galeri'], [
                'contentPage' => 'galeri',
                'previewRoute' => 'galeri',
            ]),
            'kontak' => array_merge($pages['kontak'], [
                'contentPage' => 'kontak',
                'previewRoute' => 'kontak',
                'sections' => [
                    'Halaman kontak' => self::selectFields(
                        $pages['kontak']['sections']['Halaman kontak'],
                        [
                            'page_title',
                            'page_intro',
                            'form_heading',
                            'location_heading',
                            'location_text',
                            'shared_contact_heading',
                            'shared_contact_intro',
                        ],
                    ),
                ],
            ]),
        ];
    }

    public static function editorContentPage(string $page): string
    {
        return self::editorPages()[$page]['contentPage'] ?? $page;
    }

    public static function isDatabaseConfigured(): bool
    {
        $database = config(Database::class);
        $connection = $database->default;

        return trim((string) ($connection['hostname'] ?? '')) !== ''
            && trim((string) ($connection['database'] ?? '')) !== '';
    }

    public static function fieldsForPage(string $page): array
    {
        $fields = [];
        $definitions = self::editorPages()[$page] ?? self::pages()[$page] ?? [];
        foreach ($definitions['sections'] ?? [] as $section) {
            $fields = array_merge($fields, $section);
        }

        return $fields;
    }

    private static function selectFields(array $fields, array $keys): array
    {
        return array_intersect_key($fields, array_flip($keys));
    }

    public static function movableSections(string $page): array
    {
        return $page === 'beranda' ? self::HOMEPAGE_SECTIONS : [];
    }

    public function sectionOrder(string $page): array
    {
        $sections = self::movableSections($page);
        $defaultOrder = array_keys($sections);
        if ($defaultOrder === [] || ! self::isDatabaseConfigured()) {
            return $defaultOrder;
        }

        $row = $this->find($page . '.section_order');
        $storedOrder = $row === null ? null : json_decode((string) $row['content_value'], true);
        if (! is_array($storedOrder)) {
            return $defaultOrder;
        }

        $order = array_values(array_unique(array_filter(
            $storedOrder,
            static fn ($section): bool => is_string($section) && array_key_exists($section, $sections),
        )));

        return array_merge($order, array_values(array_diff($defaultOrder, $order)));
    }

    public function publicImagePath(string $key, string $fallback): string
    {
        if (! self::isDatabaseConfigured()) {
            return is_file(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $fallback)) ? $fallback : '';
        }

        $row = $this->find($key);
        $filename = (string) ($row['content_value'] ?? '');
        if ($filename !== '') {
            if (basename($filename) !== $filename
                || ! preg_match('/\A[a-z0-9._-]+\.(?:jpe?g|png|webp)\z/i', $filename)) {
                log_message('error', 'Ignoring invalid site image filename for {key}.', ['key' => $key]);
            } else {
                $path = 'uploads/site/' . $filename;
                if (is_file(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $path))) {
                    return $path;
                }

                log_message('error', 'Site image file is missing for {key}: {filename}', [
                    'key' => $key,
                    'filename' => $filename,
                ]);
            }
        }

        return is_file(FCPATH . str_replace('/', DIRECTORY_SEPARATOR, $fallback)) ? $fallback : '';
    }

    public function forPage(string $page): array
    {
        $page = self::editorContentPage($page);
        $fields = [];
        foreach (self::pages()[$page]['sections'] ?? [] as $section) {
            $fields = array_merge($fields, $section);
        }

        $values = [];
        foreach ($fields as $key => $field) {
            $values[$key] = $field['default'];
        }

        if ($fields === [] || ! self::isDatabaseConfigured()) {
            return $values;
        }

        $keys = array_map(static fn (string $key): string => $page . '.' . $key, array_keys($fields));
        $rows = $this->select('content_key, content_value')
            ->whereIn('content_key', $keys)
            ->findAll();

        $foundKeys = [];
        foreach ($rows as $row) {
            $prefix = $page . '.';
            $key = substr((string) $row['content_key'], strlen($prefix));
            if (array_key_exists($key, $values)) {
                $values[$key] = (string) $row['content_value'];
                $foundKeys[] = $key;
            }
        }

        if ($page === 'beranda'
            && ! in_array('shared_contact_button', $foundKeys, true)
            && isset($values['shared_contact_button'])) {
            $legacyButton = $this->find('kontak.shared_contact_button');
            if ($legacyButton !== null) {
                $values['shared_contact_button'] = (string) $legacyButton['content_value'];
            }
        }

        return $values;
    }
}
