<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;
use App\Models\ContactMessageModel;

class Users extends BaseController
{
    public function index(): string
    {
        helper(['form', 'url']);

        return view('admin/users/index', [
            'pageTitle' => 'Akun Pengelola',
            'admins' => (new AdminUserModel())->orderBy('created_at', 'ASC')->findAll(),
            'adminSuccess' => session()->getFlashdata('adminSuccess'),
            'adminError' => session()->getFlashdata('adminError'),
            'formErrors' => session()->getFlashdata('formErrors') ?? [],
            'submittedUsername' => session()->getFlashdata('submittedUsername') ?? '',
        ]);
    }

    public function create()
    {
        helper('url');
        $username = trim((string) $this->request->getPost('username'));
        $rules = [
            'username' => 'required|min_length[3]|max_length[80]|regex_match[/\A[A-Za-z0-9._-]+\z/]|is_unique[admin_users.username]',
            'password' => 'required|min_length[12]|max_length[1024]',
            'password_confirm' => 'required|matches[password]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->to(site_url('admin/pengelola'))
                ->withInput()
                ->with('formErrors', $this->validator->getErrors())
                ->with('submittedUsername', $username);
        }

        $model = new AdminUserModel();
        if (! $model->insert([
            'username' => $username,
            'password_hash' => password_hash((string) $this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'editor',
            'is_active' => 1,
        ])) {
            log_message('error', 'Unable to create manager account: {errors}', [
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            return redirect()->to(site_url('admin/pengelola'))
                ->with('adminError', 'Akun pengelola belum berhasil dibuat. Silakan coba lagi.');
        }

        return redirect()->to(site_url('admin/pengelola'))
            ->with('adminSuccess', 'Akun pengelola berhasil dibuat. Sampaikan nama pengguna dan kata sandi awal secara pribadi.');
    }

    public function updateStatus(int $id)
    {
        helper('url');
        $model = new AdminUserModel();
        $admin = $model->find($id);
        if ($admin === null) {
            return redirect()->to(site_url('admin/pengelola'))
                ->with('adminError', 'Akun pengelola tidak ditemukan.');
        }
        if ((int) $admin['id'] === (int) session()->get('adminUserId')) {
            return redirect()->to(site_url('admin/pengelola'))
                ->with('adminError', 'Anda tidak dapat menonaktifkan akun yang sedang digunakan.');
        }

        $activate = $this->request->getPost('is_active') === '1';
        if (! $activate && $admin['role'] === 'superadmin') {
            $activeSuperadmins = $model->where('role', 'superadmin')
                ->where('is_active', 1)
                ->countAllResults();
            if ($activeSuperadmins <= 1) {
                return redirect()->to(site_url('admin/pengelola'))
                    ->with('adminError', 'Admin utama aktif terakhir tidak dapat dinonaktifkan.');
            }
        }

        if (! $model->update($id, ['is_active' => $activate ? 1 : 0])) {
            log_message('error', 'Unable to change account status for admin ID {id}.', ['id' => $id]);

            return redirect()->to(site_url('admin/pengelola'))
                ->with('adminError', 'Status akun belum berhasil diperbarui. Silakan coba lagi.');
        }

        return redirect()->to(site_url('admin/pengelola'))
            ->with('adminSuccess', $activate ? 'Akun pengelola berhasil diaktifkan.' : 'Akun pengelola berhasil dinonaktifkan.');
    }

    public function delete(int $id)
    {
        helper('url');
        $model = new AdminUserModel();
        $admin = $model->find($id);
        if ($admin === null) {
            return redirect()->to(site_url('admin/pengelola'))
                ->with('adminError', 'Akun pengelola tidak ditemukan.');
        }

        if ((int) $admin['id'] === (int) session()->get('adminUserId')) {
            return redirect()->to(site_url('admin/pengelola'))
                ->with('adminError', 'Anda tidak dapat menghapus akun yang sedang digunakan.');
        }

        if ($admin['role'] === 'superadmin' && (int) $admin['is_active'] === 1) {
            $activeSuperadmins = $model->where('role', 'superadmin')
                ->where('is_active', 1)
                ->countAllResults();
            if ($activeSuperadmins <= 1) {
                return redirect()->to(site_url('admin/pengelola'))
                    ->with('adminError', 'Admin utama aktif terakhir tidak dapat dihapus.');
            }
        }

        $db = db_connect();
        $db->transBegin();

        $messages = new ContactMessageModel();
        if (! $messages->preserveAssignmentsForDeletedAdmin($id, (string) $admin['username'])) {
            $db->transRollback();
            log_message('error', 'Unable to preserve contact assignments for admin ID {id}.', ['id' => $id]);

            return redirect()->to(site_url('admin/pengelola'))
                ->with('adminError', 'Akun belum dapat dihapus karena riwayat pesan gagal diperbarui. Silakan coba lagi.');
        }

        if (! $model->delete($id) || $db->transStatus() === false) {
            $db->transRollback();
            log_message('error', 'Unable to delete admin account ID {id}.', ['id' => $id]);

            return redirect()->to(site_url('admin/pengelola'))
                ->with('adminError', 'Akun belum berhasil dihapus. Silakan coba lagi.');
        }

        $db->transCommit();

        return redirect()->to(site_url('admin/pengelola'))
            ->with('adminSuccess', 'Akun pengelola berhasil dihapus. Riwayat penugasannya pada pesan tetap tersimpan.');
    }
}
