-- PBI 17 - Audit Report
-- SQL ini membuat tabel yang diperlukan controller AuditReportController
-- dan mengisi data sampel sesuai nama kolom yang dibaca controller.

CREATE TABLE IF NOT EXISTS `suppliers` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(255) NOT NULL,
  `type` VARCHAR(50) NOT NULL,
  `regency` VARCHAR(255) NOT NULL,
  `land_count` INT UNSIGNED NOT NULL DEFAULT 0,
  `land_area` DECIMAL(12,2) NOT NULL DEFAULT 0.00,
  `account_status` VARCHAR(50) NOT NULL,
  `document_completeness` VARCHAR(100) NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `suppliers`
  (`name`, `type`, `regency`, `land_count`, `land_area`, `account_status`, `document_completeness`, `created_at`, `updated_at`)
VALUES
  ('PT Agro Sejahtera', 'Perusahaan', 'Kabupaten Kutai Barat', 3, 48.20, 'Aktif', 'Lengkap', NOW(), NOW()),
  ('Koperasi Plasma Mandiri', 'Plasma', 'Kabupaten Musi Banyuasin', 18, 92.75, 'Aktif', 'Hampir Lengkap', NOW(), NOW()),
  ('UD Sawit Mandiri', 'Mandiri', 'Kabupaten Bengkulu Utara', 2, 12.40, 'Nonaktif', 'Belum Lengkap', NOW(), NOW());

CREATE TABLE IF NOT EXISTS `sustainability_assessments` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_name` VARCHAR(255) NOT NULL,
  `supplier_type` VARCHAR(50) NOT NULL,
  `criteria_version` VARCHAR(50) NOT NULL,
  `score` TINYINT UNSIGNED NOT NULL,
  `category` VARCHAR(20) NOT NULL,
  `operating_eligibility` VARCHAR(50) NOT NULL,
  `verified_at` DATE NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `sustainability_assessments`
  (`supplier_name`, `supplier_type`, `criteria_version`, `score`, `category`, `operating_eligibility`, `verified_at`, `created_at`, `updated_at`)
VALUES
  ('PT Agro Sejahtera', 'Perusahaan', 'v2', 82, 'Hijau', 'Ya', CURDATE(), NOW(), NOW()),
  ('Koperasi Plasma Mandiri', 'Plasma', 'v2', 58, 'Kuning', 'Bersyarat', DATE_SUB(CURDATE(), INTERVAL 12 DAY), NOW(), NOW()),
  ('UD Sawit Mandiri', 'Mandiri', 'v1', 34, 'Merah', 'Tidak', DATE_SUB(CURDATE(), INTERVAL 28 DAY), NOW(), NOW());

CREATE TABLE IF NOT EXISTS `traceability_batches` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `batch_code` VARCHAR(100) NOT NULL,
  `supplier_name` VARCHAR(255) NOT NULL,
  `supplier_type` VARCHAR(50) NOT NULL,
  `land_block` VARCHAR(255) NOT NULL,
  `regency` VARCHAR(255) NOT NULL,
  `commodity` VARCHAR(255) NOT NULL,
  `weight_kg` DECIMAL(12,2) NOT NULL,
  `shipment_status` VARCHAR(100) NOT NULL,
  `mill_status` VARCHAR(100) NOT NULL,
  `ledger_hash` CHAR(64) NOT NULL,
  `integrity_status` VARCHAR(50) NOT NULL,
  `shipment_date` DATE NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `traceability_batches`
  (`batch_code`, `supplier_name`, `supplier_type`, `land_block`, `regency`, `commodity`, `weight_kg`, `shipment_status`, `mill_status`, `ledger_hash`, `integrity_status`, `shipment_date`, `created_at`, `updated_at`)
VALUES
  ('BATCH-2026-001', 'PT Agro Sejahtera', 'Perusahaan', 'Blok A-01', 'Kabupaten Kutai Barat', 'TBS Kelapa Sawit', 2450.00, 'Dikirim', 'Diterima',
   SHA2('SIPS-SA-2026-001', 256), 'Valid', CURDATE(), NOW(), NOW()),
  ('BATCH-2026-018', 'Koperasi Plasma Mandiri', 'Plasma', 'Blok P-07', 'Kabupaten Musi Banyuasin', 'TBS Kelapa Sawit', 1880.00, 'Dikirim', 'Diterima',
   SHA2('SIPS-PL-2026-018', 256), 'Valid', CURDATE(), NOW(), NOW()),
  ('BATCH-2026-023', 'UD Sawit Mandiri', 'Mandiri', 'Blok M-03', 'Kabupaten Bengkulu Utara', 'TBS Kelapa Sawit', 920.00, 'Dalam Proses', 'Belum Diterima',
   SHA2('SIPS-MD-2026-023', 256), 'Rusak', CURDATE(), NOW(), NOW());

CREATE TABLE IF NOT EXISTS `supplier_documents` (
  `id` BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
  `supplier_name` VARCHAR(255) NOT NULL,
  `supplier_type` VARCHAR(50) NOT NULL,
  `document_type` VARCHAR(100) NOT NULL,
  `document_number` VARCHAR(100) NOT NULL,
  `land` VARCHAR(255) NOT NULL,
  `expiry_date` DATE NOT NULL,
  `created_at` TIMESTAMP NULL DEFAULT NULL,
  `updated_at` TIMESTAMP NULL DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

INSERT INTO `supplier_documents`
  (`supplier_name`, `supplier_type`, `document_type`, `document_number`, `land`, `expiry_date`, `created_at`, `updated_at`)
VALUES
  ('PT Agro Sejahtera', 'Perusahaan', 'ISPO', 'ISPO-2025-001', 'Blok A-01', DATE_ADD(CURDATE(), INTERVAL 42 DAY), NOW(), NOW()),
  ('Koperasi Plasma Mandiri', 'Plasma', 'STDB', 'STDB-2024-118', 'Blok P-07', DATE_ADD(CURDATE(), INTERVAL 75 DAY), NOW(), NOW()),
  ('UD Sawit Mandiri', 'Mandiri', 'SHM', 'SHM-00991', 'Blok M-03', DATE_SUB(CURDATE(), INTERVAL 8 DAY), NOW(), NOW());
