-- Batak Wedding - MySQL schema (5 tables)
-- Import: mysql -u root -p < database/wedding_batak.sql

CREATE DATABASE IF NOT EXISTS wedding_batak
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_general_ci;

USE wedding_batak;

CREATE TABLE IF NOT EXISTS users (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','staff') NOT NULL DEFAULT 'staff',
  phone VARCHAR(50) DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS locations (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(200) NOT NULL,
  image VARCHAR(255) NULL,
  address TEXT NOT NULL,
  capacity INT UNSIGNED NOT NULL DEFAULT 0,
  type VARCHAR(80) NOT NULL DEFAULT '',
  facilities TEXT DEFAULT NULL,
  contact_phone VARCHAR(50) DEFAULT NULL,
  description TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS clients (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  bride_name VARCHAR(200) NOT NULL,
  groom_name VARCHAR(200) NOT NULL DEFAULT '',
  phone VARCHAR(50) DEFAULT NULL,
  notes TEXT DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_clients_phone (phone)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS events (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  name VARCHAR(200) NOT NULL,
  client_id INT UNSIGNED NOT NULL,
  location_id INT UNSIGNED NOT NULL,
  event_type VARCHAR(120) NOT NULL,
  event_date DATE NOT NULL,
  event_time TIME DEFAULT NULL,
  guests INT UNSIGNED NOT NULL DEFAULT 0,
  budget BIGINT UNSIGNED NOT NULL DEFAULT 0,
  description TEXT DEFAULT NULL,
  status ENUM('pending','active','completed','cancelled') NOT NULL DEFAULT 'pending',
  created_by INT UNSIGNED DEFAULT NULL,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_events_date (event_date),
  KEY idx_events_status (status),
  CONSTRAINT fk_events_client FOREIGN KEY (client_id) REFERENCES clients (id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_events_location FOREIGN KEY (location_id) REFERENCES locations (id)
    ON DELETE RESTRICT ON UPDATE CASCADE,
  CONSTRAINT fk_events_user FOREIGN KEY (created_by) REFERENCES users (id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE IF NOT EXISTS settings (
  id INT UNSIGNED NOT NULL AUTO_INCREMENT,
  setting_key VARCHAR(100) NOT NULL,
  setting_value TEXT DEFAULT NULL,
  PRIMARY KEY (id),
  UNIQUE KEY uq_settings_key (setting_key)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO users (name, email, password, role, phone) VALUES
(
  'Admin User',
  'admin@batakwedding.com',
  '$2y$10$a3v5FP6hmzys6Z/OIuFFy.v00uP6OuSQS9lQ.VMF6aeu8iJ3Fjosa',
  'admin',
  '+62 812 3456 7890'
);

INSERT INTO settings (setting_key, setting_value) VALUES
('site_name', 'Batak Wedding'),
('welcome_note', 'Staff Portal');
