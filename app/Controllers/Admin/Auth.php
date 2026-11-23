<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;
use App\Models\WartaModel;

class Auth extends BaseController
{
    public function loginForm()
    {
        helper(['form', 'url']);

        if (session()->has('adminUserId')) {
            return redirect()->to(site_url('admin'));
        }

        $databaseConfigured = WartaModel::isDatabaseConfigured();

        return view('admin/login', [
            'authError' => session()->getFlashdata('authError'),
            'loginUsername' => session()->getFlashdata('loginUsername') ?? '',
            'setupSuccess' => session()->getFlashdata('setupSuccess'),
            'setupError' => session()->getFlashdata('setupError'),
            'setupUsername' => session()->getFlashdata('setupUsername') ?? '',
            'databaseConfigured' => $databaseConfigured,
            'adminExists' => $databaseConfigured && $this->adminAccountExists(),
            'setupMode' => false,
        ]);
    }

    public function setupForm()
    {
        helper(['form', 'url']);

        if (session()->has('adminUserId')) {
            return redirect()->to(site_url('admin'));
        }

        $databaseConfigured = WartaModel::isDatabaseConfigured();
        $adminExists = $databaseConfigured && $this->adminAccountExists();

        if ($adminExists) {
            return redirect()->to(site_url('admin/login'))
                ->with('setupError', 'Akun admin pertama sudah dibuat. Pembuatan akun dari halaman ini sudah ditutup.');
        }

        return view('admin/login', [
            'authError' => null,
            'loginUsername' => '',
            'setupSuccess' => null,
            'setupError' => session()->getFlashdata('setupError'),
            'setupUsername' => session()->getFlashdata('setupUsername') ?? '',
            'databaseConfigured' => $databaseConfigured,
            'adminExists' => false,
            'setupMode' => true,
        ]);
    }

    public function createFirstAdmin()
    {
        helper('url');

        if (! WartaModel::isDatabaseConfigured()) {
            return redirect()->to(site_url('admin/setup'))
                ->with('setupError', 'Database belum dikonfigurasi. Hubungi pengelola situs untuk menyiapkan database.');
        }

        if ($this->adminAccountExists()) {
            return redirect()->to(site_url('admin/login'))
                ->with('setupError', 'Akun admin pertama sudah dibuat. Pembuatan akun dari halaman ini sudah ditutup.');
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');
        $rules = [
            'username' => 'required|min_length[3]|max_length[80]|regex_match[/\A[A-Za-z0-9._-]+\z/]|is_unique[admin_users.username]',
            'password' => 'required|min_length[12]|max_length[1024]',
            'password_confirm' => 'required|matches[password]',
        ];

        if (! $this->validate($rules)) {
            $errors = $this->validator->getErrors();

            return redirect()->to(site_url('admin/setup'))
                ->with('setupError', reset($errors) ?: 'Periksa kembali data akun yang dimasukkan.')
                ->with('setupUsername', $username);
        }

        $model = new AdminUserModel();
        if ($model->insert([
            'username' => $username,
            'password_hash' => password_hash($password, PASSWORD_DEFAULT),
            'role' => 'superadmin',
            'is_active' => 1,
        ]) === false) {
            log_message('error', 'Unable to create the first admin account: {errors}', [
                'errors' => implode(', ', array_values($model->errors())),
            ]);

            return redirect()->to(site_url('admin/setup'))
                ->with('setupError', 'Akun belum berhasil dibuat. Coba lagi atau hubungi pengelola situs.')
                ->with('setupUsername', $username);
        }

        return redirect()->to(site_url('admin/login'))
            ->with('setupSuccess', 'Akun admin berhasil dibuat. Silakan masuk dengan akun baru.');
    }

    public function login()
    {
        helper('url');

        if (! WartaModel::isDatabaseConfigured()) {
            return redirect()->to(site_url('admin/login'))
                ->with('authError', 'Database belum dikonfigurasi. Hubungi pengelola situs untuk menyiapkan akses admin.');
        }

        $username = trim((string) $this->request->getPost('username'));
        $password = (string) $this->request->getPost('password');
        $rules = [
            'username' => 'required|max_length[80]',
            'password' => 'required|max_length[1024]',
        ];

        if (! $this->validate($rules)) {
            return redirect()->to(site_url('admin/login'))
                ->with('authError', 'Masukkan nama pengguna dan kata sandi.')
                ->with('loginUsername', $username);
        }

        $admin = (new AdminUserModel())->where('username', $username)->first();
        if (
            $admin === null
            || (int) $admin['is_active'] !== 1
            || ! password_verify($password, (string) $admin['password_hash'])
        ) {
            return redirect()->to(site_url('admin/login'))
                ->with('authError', 'Nama pengguna atau kata sandi tidak sesuai.')
                ->with('loginUsername', $username);
        }

        session()->regenerate(true);
        session()->set([
            'adminUserId' => (int) $admin['id'],
            'adminUsername' => (string) $admin['username'],
            'adminRole' => (string) $admin['role'],
        ]);

        return redirect()->to(site_url('admin'));
    }

    public function logout()
    {
        helper('url');
        session()->destroy();

        return redirect()->to(site_url('admin/login'));
    }

    private function adminAccountExists(): bool
    {
        return (new AdminUserModel())->countAllResults() > 0;
    }
}
