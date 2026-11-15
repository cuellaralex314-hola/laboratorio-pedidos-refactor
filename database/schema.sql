CREATE DATABASE IF NOT EXISTS laboratorio
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE laboratorio;

CREATE TABLE IF NOT EXISTS orders (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    patient VARCHAR(150) NOT NULL,
    amount DECIMAL(12,2) NOT NULL,
    patient_type VARCHAR(30) NOT NULL,
    total DECIMAL(12,2) NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    INDEX idx_orders_created_at (created_at),
    INDEX idx_orders_patient_type (patient_type)
) ENGINE=InnoDB;