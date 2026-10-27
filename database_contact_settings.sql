-- Create contact_messages table
CREATE TABLE IF NOT EXISTS contact_messages (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(255) NOT NULL,
    message TEXT NOT NULL,
    status ENUM('unread', 'read', 'replied', 'archived') DEFAULT 'unread',
    ip_address VARCHAR(45),
    user_agent TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX idx_email (email),
    INDEX idx_status (status)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Insert contact detail settings
INSERT IGNORE INTO settings (setting_key, setting_value, setting_type) VALUES
('contact_email', 'archive@rajasthanreads.org', 'string'),
('contact_phone', '+91 000 000 0000', 'string'),
('contact_address', 'Rajasthan, India', 'string'),
('contact_whatsapp', '+91 000 000 0000', 'string'),
('social_twitter', 'https://twitter.com/rajasthanreads', 'string'),
('social_facebook', 'https://facebook.com/rajasthanreads', 'string'),
('social_instagram', 'https://instagram.com/rajasthanreads', 'string');
