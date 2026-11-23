<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;

class Profile extends BaseController
{
    public function index(): string
    {
        helper(['form', 'url']);

        return view('admin/profile', [
            'pageTitle' => 'Akun Saya',
            'passwordSuccess' => session()->getFlashdata('passwordSuccess'),
            'passwordError' => session()->getFlashdata('passwordError'),
            'formErrors' => session()->getFlashdata('formErrors') ?? [],
        ]);
    }

    public function updatePassword()
    {
        helper('url');
        $userId = (int) session()->get('adminUserId');
        $model = new AdminUserModel();
        $admin = $model->find($userId);
        if ($admin === null) {
            session()->destroy();

            return redirect()->to(site_url('admin/login'))
                ->with('authError', 'Akun tidak ditemukan. Silakan masuk kembali.');
        }

        if (! password_verify((string) $this->request->getPost('current_password'), (string) $admin['password_hash'])) {
            return redirect()->to(site_url('admin/akun'))
                ->with('passwordError', 'Kata sandi saat ini tidak sesuai.')
                ->withInput();
        }

        $rules = [
            'new_password' => 'required|min_length[12]|max_length[1024]',
            'password_confirm' => 'required|matches[new_password]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->to(site_url('admin/akun'))
                ->withInput()
                ->with('formErrors', $this->validator->getErrors());
        }

        if (! $model->update($userId, [
            'password_hash' => password_hash((string) $this->request->getPost('new_password'), PASSWORD_DEFAULT),
        ])) {
            log_message('error', 'Unable to update password for admin ID {id}.', ['id' => $userId]);

            return redirect()->to(site_url('admin/akun'))
                ->with('passwordError', 'Kata sandi belum berhasil diperbarui. Silakan coba lagi.');
        }

        session()->regenerate(true);

        return redirect()->to(site_url('admin/akun'))
            ->with('passwordSuccess', 'Kata sandi berhasil diperbarui.');
    }
}
