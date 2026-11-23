<?php

use App\Models\ContactMessageModel;
use App\Models\AdminUserModel;
use App\Models\WorshipScheduleModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class AdminFeaturesDatabaseTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $namespace = 'App';
    protected $refresh = true;

    public function testScheduleAndContactMessageAreStoredForAdminWorkflows(): void
    {
        $scheduleModel = new WorshipScheduleModel();
        $scheduleId = $scheduleModel->insert([
            'service_date' => date('Y-m-d', strtotime('+1 day')),
            'service_type' => 'Ibadah Minggu',
            'service_time' => '09:00:00',
            'location' => 'Gereja Elpido',
            'minister' => 'Pelayan jemaat',
        ]);

        $this->assertNotFalse($scheduleId);
        $this->assertSame((int) $scheduleId, (int) $scheduleModel->find($scheduleId)['id']);
        $this->assertCount(1, $scheduleModel->upcomingForPublic());

        $adminModel = new AdminUserModel();
        $adminId = $adminModel->insert([
            'username' => 'admin-utama',
            'password_hash' => password_hash('correct-horse-battery-staple', PASSWORD_DEFAULT),
            'role' => 'superadmin',
            'is_active' => 1,
        ]);
        $this->assertNotFalse($adminId);

        $messageModel = new ContactMessageModel();
        $messageId = $messageModel->insert([
            'sender_name' => 'Jemaat',
            'sender_email' => 'jemaat@example.com',
            'subject' => 'Informasi ibadah',
            'message' => 'Mohon informasi jadwal ibadah minggu ini.',
        ]);

        $this->assertNotFalse($messageId);
        $this->assertTrue($messageModel->update($messageId, [
            'status' => 'diproses',
            'assigned_admin_id' => $adminId,
        ]));
        $message = $messageModel->find($messageId);
        $this->assertSame('diproses', $message['status']);
        $this->assertSame((int) $adminId, (int) $message['assigned_admin_id']);
        $this->assertSame('Jemaat', $message['sender_name']);

        $this->assertTrue($messageModel->preserveAssignmentsForDeletedAdmin((int) $adminId, 'admin-utama'));
        $preservedMessage = $messageModel->find($messageId);
        $this->assertNull($preservedMessage['assigned_admin_id']);
        $this->assertSame('admin-utama', $preservedMessage['assigned_admin_username']);
        $this->assertTrue($messageModel->delete($messageId));
        $this->assertNull($messageModel->find($messageId));
    }
}
