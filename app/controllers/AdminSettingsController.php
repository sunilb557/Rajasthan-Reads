<?php

class AdminSettingsController extends Controller {
    private $db;

    public function __construct() {
        parent::__construct();
        // Check if admin
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            $this->flash('error', 'Unauthorized access.');
            $this->redirect(BASE_URL . '/login');
        }
        $this->db = Database::getInstance();
    }

    public function index() {
        $settingsRows = $this->db->fetchAll("SELECT * FROM settings ORDER BY setting_key ASC");
        $settings = [];
        foreach ($settingsRows as $row) {
            $settings[$row['setting_key']] = $row;
        }

        return $this->render('admin/settings/index', [
            'title' => 'Site Settings - Admin',
            'settings' => $settings
        ], 'admin');
    }

    public function update() {
        if ($this->isPost()) {
            $settings = $_POST['settings'] ?? [];
            foreach ($settings as $key => $value) {
                $this->db->update('settings', 
                    ['setting_value' => $value], 
                    'setting_key = :key', 
                    ['key' => $key]
                );
            }
            $this->flash('success', 'Settings updated successfully.');
            $this->redirect(BASE_URL . '/admin/settings');
        }
    }
}
