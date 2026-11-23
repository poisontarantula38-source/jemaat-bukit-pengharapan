<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\ContactMessageModel;
use App\Models\WorshipScheduleModel;
use App\Models\WartaModel;

class Dashboard extends BaseController
{
    public function index(): string
    {
        helper(['form', 'url']);
        $newMessages = (new ContactMessageModel())
            ->where('status', 'baru')
            ->countAllResults();
        $upcomingSchedules = (new WorshipScheduleModel())
            ->where('service_date >=', date('Y-m-d'))
            ->countAllResults();
        $scheduleModel = new WorshipScheduleModel();
        $recentMessages = new ContactMessageModel();

        return view('admin/dashboard', [
            'pageTitle' => 'Beranda Admin',
            'newMessages' => $newMessages,
            'upcomingSchedules' => $upcomingSchedules,
            'jadwalMendatang' => $scheduleModel
                ->where('service_date >=', date('Y-m-d'))
                ->orderBy('service_date', 'ASC')
                ->orderBy('service_time', 'ASC')
                ->findAll(4),
            'pesanTerbaru' => $recentMessages
                ->orderBy('created_at', 'DESC')
                ->findAll(4),
            'wartaTerbaru' => (new WartaModel())
                ->orderBy('updated_at', 'DESC')
                ->findAll(5),
        ]);
    }
}
