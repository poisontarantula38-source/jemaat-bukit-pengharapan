<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\AdminUserModel;
use App\Models\ContactMessageModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Inbox extends BaseController
{
    public function index(): string
    {
        helper(['form', 'url']);
        $model = new ContactMessageModel();
        $status = (string) $this->request->getGet('status');
        if (in_array($status, ['baru', 'diproses', 'selesai'], true)) {
            $model->where('status', $status);
        } else {
            $status = '';
        }

        return view('admin/inbox/index', [
            'pageTitle' => 'Kotak Pesan',
            'messages' => $model->orderBy('created_at', 'DESC')
                ->paginate(20),
            'pager' => $model->pager,
            'admins' => (new AdminUserModel())->orderBy('username', 'ASC')->findAll(),
            'selectedStatus' => $status,
            'adminSuccess' => session()->getFlashdata('adminSuccess'),
            'adminError' => session()->getFlashdata('adminError'),
        ]);
    }

    public function update(int $id)
    {
        helper('url');
        $model = new ContactMessageModel();
        $existingMessage = $model->find($id);
        if ($existingMessage === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $status = (string) $this->request->getPost('status');
        $assignee = trim((string) $this->request->getPost('assigned_admin_id'));
        if (! in_array($status, ['baru', 'diproses', 'selesai'], true)) {
            return redirect()->to(site_url('admin/pesan'))
                ->with('adminError', 'Status pesan tidak valid. Pilih status yang tersedia.');
        }

        $assignedAdminId = null;
        $assignedAdminUsername = $existingMessage['assigned_admin_id'] === null
            ? ($existingMessage['assigned_admin_username'] ?? null)
            : null;
        if ($assignee !== '') {
            if (! ctype_digit($assignee)) {
                return redirect()->to(site_url('admin/pesan'))
                    ->with('adminError', 'Penanggung jawab yang dipilih tidak valid.');
            }

            $admin = (new AdminUserModel())->where('is_active', 1)->find((int) $assignee);
            if ($admin === null) {
                return redirect()->to(site_url('admin/pesan'))
                    ->with('adminError', 'Penanggung jawab tidak ditemukan atau akunnya tidak aktif.');
            }
            $assignedAdminId = (int) $admin['id'];
            $assignedAdminUsername = (string) $admin['username'];
        }

        if (! $model->update($id, [
            'status' => $status,
            'assigned_admin_id' => $assignedAdminId,
            'assigned_admin_username' => $assignedAdminUsername,
        ])) {
            log_message('error', 'Unable to update contact message ID {id}: {errors}', [
                'id' => $id,
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            return redirect()->to(site_url('admin/pesan'))
                ->with('adminError', 'Perubahan pesan belum berhasil disimpan. Silakan coba lagi.');
        }

        return redirect()->to(site_url('admin/pesan'))
            ->with('adminSuccess', 'Status dan penanggung jawab pesan berhasil diperbarui.');
    }

    public function delete(int $id)
    {
        helper('url');
        $model = new ContactMessageModel();
        if ($model->find($id) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $model->delete($id)) {
            log_message('error', 'Unable to delete contact message ID {id}: {errors}', [
                'id' => $id,
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            return redirect()->to(site_url('admin/pesan'))
                ->with('adminError', 'Pesan belum berhasil dihapus. Silakan coba lagi.');
        }

        return redirect()->to(site_url('admin/pesan'))
            ->with('adminSuccess', 'Pesan berhasil dihapus secara permanen.');
    }
}
