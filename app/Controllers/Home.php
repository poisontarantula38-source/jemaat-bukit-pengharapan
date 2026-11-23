<?php

namespace App\Controllers;

use App\Models\WartaModel;
use App\Models\GalleryPhotoModel;
use App\Models\SiteContentModel;

class Home extends BaseController
{
    public function index(): string
    {
        helper('url');

        $contentModel = new SiteContentModel();
        $content = $contentModel->forPage('beranda');
        $content['hero_image_path'] = $contentModel->publicImagePath(
            'beranda.hero_image',
            'assets/images/gereja-bukit-pengharapan-udara.jpg',
        );
        $content['profile_image_path'] = $contentModel->publicImagePath(
            'beranda.profile_image',
            'assets/images/gereja-bukit-pengharapan-depan.jpg',
        );
        $contactContent = $contentModel->forPage('kontak');
        $content['shared_contact_heading'] = $contactContent['shared_contact_heading'];
        $content['shared_contact_intro'] = $contactContent['shared_contact_intro'];
        $sectionOrder = $contentModel->sectionOrder('beranda');

        return view('publik/beranda', [
            'beritaSorotan' => WartaModel::isDatabaseConfigured()
                ? (new WartaModel())->publishedForPublic(4)
                : [],
            'fotoGaleri' => GalleryPhotoModel::isDatabaseConfigured()
                ? (new GalleryPhotoModel())->latest(5)
                : [],
            'content' => $content,
            'sectionOrder' => $sectionOrder,
        ]);
    }
}
