<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateHomepageProfileIntroduction extends Migration
{
    private const OLD_INTRO = 'Kenali Jemaat GPM Bukit Pengharapan melalui sejarah, visi-misi, dan susunan majelis. Temukan juga informasi ibadah dan wadah pelayanan yang menjadi bagian dari kehidupan jemaat.';

    private const NEW_INTRO = "Jemaat GPM Bukit Pengharapan secara administratif berada di Negeri Latuhalat, Kecamatan Nusaniwe, Kota Ambon, dengan wilayah pelayanan seluas kurang lebih 0,85 km² yang terletak di bagian barat daya Kota Ambon. Wilayah pelayanan jemaat ini berbatasan langsung dengan Jemaat GPM Seilale di sebelah utara, Jemaat GPM Latuhalat di sebelah selatan, Jemaat GPM Waimahu di sebelah barat, serta Jemaat GPM Seilale dan Latuhalat di sebelah timur. Dengan kondisi topografi yang didominasi daerah bergelombang hingga terjal, jemaat ini resmi dilembagakan berdiri sendiri terlepas dari Jemaat GPM Latuhalat berdasarkan Surat Keputusan MPH Sinode GPM.\n\nBerdasarkan data per Januari 2026, Jemaat GPM Bukit Pengharapan Latuhalat memiliki sebanyak 172 Kepala Keluarga (KK) dengan total 712 jiwa warga jemaat. Seluruh warga jemaat tersebut terdistribusi secara merata ke dalam 5 sektor pelayanan dan 10 unit pelayanan, yaitu Sektor Moria, Sektor Horeb, Sektor Yabok, Sektor Hermon, dan Sektor Karmel. Penataan struktur wilayah ini dirancang untuk mengoptimalkan pelayanan pastoral, pembinaan kategorial, serta penggembalaan umat secara berkesinambungan.";

    public function up()
    {
        $this->db->table('site_content')
            ->where('content_key', 'beranda.profile_intro')
            ->where('content_value', self::OLD_INTRO)
            ->update(['content_value' => self::NEW_INTRO]);
    }

    public function down()
    {
        $this->db->table('site_content')
            ->where('content_key', 'beranda.profile_intro')
            ->where('content_value', self::NEW_INTRO)
            ->update(['content_value' => self::OLD_INTRO]);
    }
}
