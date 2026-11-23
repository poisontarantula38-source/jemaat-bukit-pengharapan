<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;

class Guide extends BaseController
{
    public function index(): string
    {
        helper('url');

        return view('admin/guide', [
            'pageTitle' => 'Buku Panduan',
            'isSuperadmin' => session()->get('adminRole') === 'superadmin',
        ]);
    }
}
