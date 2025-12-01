-- สร้างฐานข้อมูลและตารางอย่างย่อ
CREATE DATABASE IF NOT EXISTS greendash_lean CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci; -- สร้าง DB
USE greendash_lean; -- เลือกใช้ DB

-- ตารางผู้ใช้
CREATE TABLE IF NOT EXISTS users ( -- ตารางผู้ใช้
  id INT AUTO_INCREMENT PRIMARY KEY, -- ไอดี
  fullname VARCHAR(120) NOT NULL, -- ชื่อ-สกุล
  username VARCHAR(60) NOT NULL UNIQUE, -- ชื่อผู้ใช้
  password VARCHAR(255) NOT NULL, -- รหัสผ่าน (hash)
  role ENUM('admin','user') NOT NULL DEFAULT 'user', -- สิทธิ์
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- เวลาเพิ่ม
) ENGINE=InnoDB; -- ใช้ InnoDB

-- ตารางประเภทกิจกรรม
CREATE TABLE IF NOT EXISTS activity_types ( -- ประเภทกิจกรรม
  id INT AUTO_INCREMENT PRIMARY KEY, -- ไอดี
  name VARCHAR(120) NOT NULL, -- ชื่อกิจกรรม
  score_per_unit DECIMAL(10,2) NOT NULL DEFAULT 1.00, -- คะแนนต่อหน่วย
  unit_label VARCHAR(30) NOT NULL DEFAULT 'ครั้ง', -- หน่วย
  is_active TINYINT(1) NOT NULL DEFAULT 1, -- ใช้งาน?
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP -- เวลาเพิ่ม
) ENGINE=InnoDB; -- InnoDB

-- ตารางบันทึก
CREATE TABLE IF NOT EXISTS logs ( -- บันทึกกิจกรรม
  id INT AUTO_INCREMENT PRIMARY KEY, -- ไอดี
  user_id INT NOT NULL, -- ผู้ใช้
  activity_type_id INT NOT NULL, -- ประเภท
  qty DECIMAL(10,2) NOT NULL DEFAULT 1.00, -- จำนวน
  note VARCHAR(255), -- หมายเหตุ
  logged_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP, -- เวลาเกิดเหตุ
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- เวลาเพิ่ม
  INDEX (user_id), INDEX (activity_type_id), INDEX (logged_at), -- ดัชนี
  CONSTRAINT fk_u FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE, -- FK ผู้ใช้
  CONSTRAINT fk_t FOREIGN KEY (activity_type_id) REFERENCES activity_types(id) ON DELETE RESTRICT -- FK ประเภท
) ENGINE=InnoDB; -- InnoDB

-- เติมประเภทตัวอย่าง
INSERT INTO activity_types (name, score_per_unit, unit_label, is_active) VALUES -- เพิ่มตัวอย่าง
('รีไซเคิลขยะ',1.00,'ครั้ง',1), -- รายการ 1
('ปลูกต้นไม้',5.00,'ต้น',1), -- รายการ 2
('ลดใช้ไฟฟ้า',2.00,'ชั่วโมง',1); -- รายการ 3
