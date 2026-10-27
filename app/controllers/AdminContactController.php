<?php

class AdminContactController extends Controller {
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
        $messages = $this->db->fetchAll("SELECT * FROM contact_messages ORDER BY created_at DESC");
        return $this->render('admin/contact/index', [
            'title' => 'Contact Messages - Admin',
            'messages' => $messages
        ], 'admin');
    }

    public function view($id) {
        $message = $this->db->fetch("SELECT * FROM contact_messages WHERE id = :id", ['id' => $id]);
        if (!$message) {
            $this->flash('error', 'Message not found.');
            $this->redirect(BASE_URL . '/admin/contact');
        }

        // Mark as read if unread
        if ($message['status'] === 'unread') {
            $this->db->update('contact_messages', ['status' => 'read'], 'id = :id', ['id' => $id]);
        }

        return $this->render('admin/contact/view', [
            'title' => 'View Message - Admin',
            'message' => $message
        ], 'admin');
    }

    public function delete($id) {
        if ($this->isPost()) {
            $this->db->delete('contact_messages', 'id = :id', ['id' => $id]);
            $this->flash('success', 'Message deleted successfully.');
        }
        $this->redirect(BASE_URL . '/admin/contact');
    }

    public function updateStatus($id) {
        if ($this->isPost()) {
            $status = $this->getPost('status');
            $this->db->update('contact_messages', ['status' => $status], 'id = :id', ['id' => $id]);
            $this->flash('success', 'Status updated successfully.');
        }
        $this->redirect(BASE_URL . '/admin/contact/' . $id);
    }
}
