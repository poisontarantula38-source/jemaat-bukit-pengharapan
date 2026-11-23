<?php

namespace App\Filters;

use App\Models\AdminUserModel;
use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

class AdminAuth implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $adminId = (int) session()->get('adminUserId');
        if ($adminId < 1) {
            helper('url');

            return redirect()->to(site_url('admin/login'));
        }

        $admin = (new AdminUserModel())->find($adminId);
        if ($admin === null || (int) $admin['is_active'] !== 1) {
            session()->destroy();
            helper('url');

            return redirect()->to(site_url('admin/login'))
                ->with('authError', 'Akun ini sudah tidak aktif. Hubungi admin utama jika Anda memerlukan akses.');
        }

        session()->set([
            'adminUsername' => (string) $admin['username'],
            'adminRole' => (string) $admin['role'],
        ]);
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
    }
}
