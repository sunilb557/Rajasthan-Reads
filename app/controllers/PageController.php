<?php

class PageController extends Controller {
    
    public function about() {
        return $this->render('pages/about', [
            'title' => 'About Us - ' . SITE_NAME,
            'description' => 'Learn more about RajasthanReads, our mission, and our dedication to preserving the cultural heritage of Rajasthan.'
        ]);
    }

    public function contact() {
        if ($this->isPost()) {
            $data = [
                'name' => $this->getPost('name'),
                'email' => $this->getPost('email'),
                'subject' => $this->getPost('subject'),
                'message' => $this->getPost('message'),
                'ip_address' => $_SERVER['REMOTE_ADDR'],
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            ];

            $db = Database::getInstance();
            $db->insert('contact_messages', $data);

            $this->flash('success', 'Your message has been sent successfully. We will get back to you soon!');
            $this->redirect(BASE_URL . '/contact-us');
        }

        return $this->render('pages/contact', [
            'title' => 'Contact Us - ' . SITE_NAME,
            'description' => 'Get in touch with RajasthanReads. We would love to hear from you.',
            'settings' => $this->data['site_settings'] ?? []
        ]);
    }

    public function privacy() {
        return $this->render('pages/privacy', [
            'title' => 'Privacy Policy - ' . SITE_NAME,
            'description' => 'Privacy Policy for RajasthanReads. Learn how we handle your data.'
        ]);
    }

    public function terms() {
        return $this->render('pages/terms', [
            'title' => 'Terms & Conditions - ' . SITE_NAME,
            'description' => 'Terms and Conditions for using RajasthanReads.'
        ]);
    }

    public function disclaimer() {
        return $this->render('pages/disclaimer', [
            'title' => 'Disclaimer - ' . SITE_NAME,
            'description' => 'Disclaimer for RajasthanReads.'
        ]);
    }
}
