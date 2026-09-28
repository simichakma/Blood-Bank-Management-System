CREATE DATABASE IF NOT EXISTS lifeblood CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE lifeblood;

CREATE TABLE users (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL UNIQUE,
  email_verified_at TIMESTAMP NULL,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','donor','hospital') NOT NULL DEFAULT 'donor',
  phone VARCHAR(30) NULL,
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  remember_token VARCHAR(100) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_users_role_active(role,is_active)
) ENGINE=InnoDB;

CREATE TABLE donor_profiles (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL UNIQUE,
  blood_group VARCHAR(5) NOT NULL,
  date_of_birth DATE NULL,
  gender VARCHAR(20) NULL,
  address VARCHAR(255) NULL,
  city VARCHAR(100) NULL,
  last_donation_date DATE NULL,
  eligible TINYINT(1) NOT NULL DEFAULT 1,
  emergency_contact VARCHAR(30) NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_donor_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_donor_group_eligible(blood_group,eligible)
) ENGINE=InnoDB;

CREATE TABLE hospitals (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  user_id BIGINT UNSIGNED NOT NULL UNIQUE,
  hospital_name VARCHAR(255) NOT NULL,
  registration_no VARCHAR(100) NOT NULL UNIQUE,
  contact_person VARCHAR(255) NULL,
  address VARCHAR(255) NULL,
  city VARCHAR(100) NULL,
  phone VARCHAR(30) NULL,
  status ENUM('pending','approved','suspended') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_hospital_user FOREIGN KEY(user_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_hospital_city_status(city,status)
) ENGINE=InnoDB;

CREATE TABLE blood_inventory (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hospital_id BIGINT UNSIGNED NULL,
  blood_group VARCHAR(5) NOT NULL,
  units INT UNSIGNED NOT NULL DEFAULT 0,
  storage_location VARCHAR(150) NULL,
  expiry_date DATE NULL,
  status ENUM('available','reserved','expired') NOT NULL DEFAULT 'available',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  INDEX idx_inventory_group_status(blood_group,status),
  INDEX idx_inventory_hospital_group_status(hospital_id,blood_group,status),
  CONSTRAINT fk_inventory_hospital FOREIGN KEY(hospital_id) REFERENCES hospitals(id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE blood_donations (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  donor_id BIGINT UNSIGNED NOT NULL,
  blood_group VARCHAR(5) NOT NULL,
  units INT UNSIGNED NOT NULL DEFAULT 1,
  donated_at DATE NOT NULL,
  screening_status ENUM('pending','approved','rejected') NOT NULL DEFAULT 'pending',
  notes TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_donation_donor FOREIGN KEY(donor_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_donation_donor_date(donor_id,donated_at)
) ENGINE=InnoDB;

CREATE TABLE blood_requests (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  hospital_id BIGINT UNSIGNED NOT NULL,
  patient_name VARCHAR(255) NOT NULL,
  patient_age SMALLINT UNSIGNED NULL,
  blood_group VARCHAR(5) NOT NULL,
  units_required INT UNSIGNED NOT NULL,
  urgency ENUM('normal','urgent','critical') NOT NULL DEFAULT 'normal',
  needed_by DATETIME NULL,
  reason TEXT NULL,
  notes TEXT NULL,
  status ENUM('pending','approved','fulfilled','rejected','cancelled') NOT NULL DEFAULT 'pending',
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_request_hospital FOREIGN KEY(hospital_id) REFERENCES hospitals(id) ON DELETE CASCADE,
  INDEX idx_request_group_status_urgency(blood_group,status,urgency)
) ENGINE=InnoDB;

CREATE TABLE appointments (
  id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  donor_id BIGINT UNSIGNED NOT NULL,
  appointment_at DATETIME NOT NULL,
  location VARCHAR(150) NULL,
  status ENUM('scheduled','completed','cancelled') NOT NULL DEFAULT 'scheduled',
  notes TEXT NULL,
  created_at TIMESTAMP NULL,
  updated_at TIMESTAMP NULL,
  CONSTRAINT fk_appointment_donor FOREIGN KEY(donor_id) REFERENCES users(id) ON DELETE CASCADE,
  INDEX idx_appointment_donor_date(donor_id,appointment_at)
) ENGINE=InnoDB;
