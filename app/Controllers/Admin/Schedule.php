<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\WorshipScheduleModel;
use CodeIgniter\Exceptions\PageNotFoundException;

class Schedule extends BaseController
{
    public function index(): string
    {
        helper(['form', 'url']);
        $model = new WorshipScheduleModel();

        return view('admin/schedule/index', [
            'pageTitle' => 'Jadwal Ibadah',
            'schedules' => $model->orderBy('service_date', 'DESC')
                ->orderBy('service_time', 'ASC')
                ->paginate(20),
            'pager' => $model->pager,
            'adminSuccess' => session()->getFlashdata('adminSuccess'),
            'adminError' => session()->getFlashdata('adminError'),
        ]);
    }

    public function newSchedule(): string
    {
        helper(['form', 'url']);

        return $this->renderForm();
    }

    public function create()
    {
        return $this->saveSchedule();
    }

    public function edit(int $id): string
    {
        helper(['form', 'url']);
        $schedule = (new WorshipScheduleModel())->find($id);
        if ($schedule === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        return $this->renderForm($schedule);
    }

    public function update(int $id)
    {
        return $this->saveSchedule($id);
    }

    public function delete(int $id)
    {
        helper('url');
        $model = new WorshipScheduleModel();
        if ($model->find($id) === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        if (! $model->delete($id)) {
            log_message('error', 'Unable to delete worship schedule ID {id}.', ['id' => $id]);

            return redirect()->to(site_url('admin/jadwal'))
                ->with('adminError', 'Jadwal belum berhasil dihapus. Silakan coba lagi.');
        }

        return redirect()->to(site_url('admin/jadwal'))
            ->with('adminSuccess', 'Jadwal ibadah berhasil dihapus.');
    }

    private function renderForm(?array $schedule = null): string
    {
        return view('admin/schedule/form', [
            'pageTitle' => $schedule === null ? 'Tambah Jadwal' : 'Ubah Jadwal',
            'schedule' => $schedule ?? [],
            'formErrors' => session()->getFlashdata('formErrors') ?? [],
            'adminError' => session()->getFlashdata('adminError'),
        ]);
    }

    private function saveSchedule(?int $id = null)
    {
        helper('url');
        $model = new WorshipScheduleModel();
        $schedule = $id === null ? null : $model->find($id);
        if ($id !== null && $schedule === null) {
            throw PageNotFoundException::forPageNotFound();
        }

        $rules = [
            'service_date' => 'required|valid_date[Y-m-d]',
            'service_type' => 'required|min_length[3]|max_length[160]',
            'service_time' => 'required|regex_match[/\A(?:[01]\d|2[0-3]):[0-5]\d\z/]',
            'location' => 'required|min_length[2]|max_length[180]',
            'minister' => 'permit_empty|max_length[180]',
            'notes' => 'permit_empty|max_length[2000]',
        ];
        if (! $this->validate($rules)) {
            return redirect()->to($this->formUrl($id))
                ->withInput()
                ->with('formErrors', $this->validator->getErrors());
        }

        $data = [
            'service_date' => (string) $this->request->getPost('service_date'),
            'service_type' => trim((string) $this->request->getPost('service_type')),
            'service_time' => (string) $this->request->getPost('service_time') . ':00',
            'location' => trim((string) $this->request->getPost('location')),
            'minister' => trim((string) $this->request->getPost('minister')) ?: null,
            'notes' => trim((string) $this->request->getPost('notes')) ?: null,
        ];
        $saved = $id === null ? $model->insert($data) : $model->update($id, $data);
        if (! $saved) {
            log_message('error', 'Unable to save worship schedule: {errors}', [
                'errors' => json_encode($model->errors(), JSON_UNESCAPED_UNICODE),
            ]);

            return redirect()->to($this->formUrl($id))
                ->withInput()
                ->with('adminError', 'Jadwal belum berhasil disimpan. Periksa kembali isian lalu coba lagi.');
        }

        return redirect()->to(site_url('admin/jadwal'))
            ->with('adminSuccess', $id === null ? 'Jadwal ibadah berhasil ditambahkan.' : 'Jadwal ibadah berhasil diperbarui.');
    }

    private function formUrl(?int $id): string
    {
        return $id === null
            ? site_url('admin/jadwal/tambah')
            : site_url('admin/jadwal/' . $id . '/ubah');
    }
}
