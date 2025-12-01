# GreenDash — “Log fast. See impact.”

ระบบบันทึกกิจกรรมสีเขียวแบบเบา ใช้งานออฟไลน์สำหรับฝึกซ้อมและแข่งขันทักษะอาชีพ (PHP + MySQLi, โค้ดมินิมอล พิมพ์ง่าย จำง่าย)

---

## ภาพรวม

GreenDash คือเว็บแอปแบบเรียบง่ายสำหรับ:

* บันทึกกิจกรรมด้านสิ่งแวดล้อม (เช่น รีไซเคิล, ปลูกต้นไม้)
* คิดคะแนนแบบหน่วย × ค่าคะแนนต่อหน่วย
* แสดงรายการกิจกรรมล่าสุดของผู้ใช้
* ผู้ดูแลระบบ (admin) สามารถ “บันทึกแทนผู้ใช้คนอื่น” ได้

> รุ่นนี้ออกแบบเพื่อ **สนามแข่ง/ออฟไลน์**: ไม่มีการต่ออินเทอร์เน็ต, ไม่มี CSRF/Flash/ระบบค้นหา, โค้ดสั้นที่สุดเท่าที่ใช้งานได้จริง

---

## คุณสมบัติหลัก

* สมัครสมาชิก / เข้าสู่ระบบ (bcrypt)
* บันทึกกิจกรรมของตนเอง
* แอดมินบันทึกแทนผู้ใช้คนอื่น
* ตารางแสดงผลล่าสุดสูงสุด 100 รายการ
* ทำงานออฟไลน์ 100% (สามารถใช้ Bootstrap ออฟไลน์ หรือใช้สไตล์แบบ CSS เล็ก ๆ ก็ได้)

---

## สถาปัตยกรรมย่อ

* PHP (สไตล์ MySQLi OOP) + MySQL/MariaDB
* โครงสร้างข้อมูล: `users`, `activity_types`, `logs`
* การคำนวณคะแนน: `logs.qty * activity_types.score_per_unit`
* โค้ดหลัก:

  * `config.php` : ตั้งค่าฐานข้อมูล/ปิด error
  * `functions.php` : คลาส `DB`, `U`, `Users`, `Types`, `Logs`
  * `index.php` : **หน้าเดียวจบ** (สมัคร/ล็อกอิน/บันทึก/บันทึกแทน/แสดงผล)

---

## โครงสร้างโฟลเดอร์ (เวอร์ชันสั้นสุด)

```
greendash/
  config.php
  functions.php
  index.php
  schema.sql        ← สคริปต์สร้างฐานข้อมูล/ตาราง/ตัวอย่าง activity
```

> ถ้าต้องการธีม UI เพิ่ม เติมไฟล์ `css/bootstrap.min.css` และปรับ `<link>` เองได้ (ออฟไลน์)

---

## ข้อกำหนดระบบ

* PHP 7.4+ (แนะนำ 8.x)
* MySQL/MariaDB
* XAMPP / MAMP / LAMP ใด ๆ ก็ได้

---

## ติดตั้งแบบรวดเร็ว (Quick Start)

1. สร้างฐานข้อมูลและตาราง

   * เปิด phpMyAdmin → Import `schema.sql` (มีตัวอย่าง `activity_types` มาให้แล้ว)
2. ตั้งค่าฐานข้อมูล

   * แก้ `config.php` ให้ตรงกับเครื่อง (host/user/pass/db)
3. สร้างผู้ใช้ `admin`

   * วิธี A: รัน SQL ด้านล่าง (bcrypt ของ “123456”)

     ```sql
     USE greendash_lean;
     DELETE FROM users WHERE username='admin';
     INSERT INTO users(fullname,username,password,role)
     VALUES ('Administrator','admin',
     '$2y$10$6gE1h0O1cO1nXq2B9aS2jO5t7a7T6v2q8m3nZB1qgH7q7d3E5vEdu','admin');
     ```
   * วิธี B: ให้ผู้สอน/กรรมการรันสคริปต์สร้างแอดมิน (ถ้ามี) แล้วลบไฟล์ทิ้ง
4. วางโฟลเดอร์ `greendash` ใน `htdocs` (XAMPP) หรือโฮสต์ที่ใช้
5. เปิด `http://localhost/greendash/index.php`

   * ยังไม่ล็อกอิน: เห็นฟอร์ม “สมัคร” และ “เข้าสู่ระบบ”
   * ล็อกอินแล้ว: เห็นฟอร์มบันทึก (และสำหรับแอดมินจะเห็นฟอร์ม “บันทึกแทน”)

---

## วิธีใช้งาน (Flow)

* สมัครสมาชิก → เข้าสู่ระบบ → เลือกกิจกรรม → ใส่จำนวนหน่วย → บันทึก
* แอดมิน: เลือกผู้ใช้ → เลือกกิจกรรม → ใส่จำนวนหน่วย → บันทึก “แทนผู้ใช้”
* ตารางด้านล่างแสดงรายการล่าสุดสูงสุด 100 รายการ (ของตนเอง / ของทุกคนถ้าเป็นแอดมิน)

---

## ตัวอย่างฐานข้อมูล (`schema.sql`)

```sql
CREATE DATABASE IF NOT EXISTS greendash_lean CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE greendash_lean;

CREATE TABLE IF NOT EXISTS users (
  id INT AUTO_INCREMENT PRIMARY KEY,
  fullname VARCHAR(120) NOT NULL,
  username VARCHAR(60) NOT NULL UNIQUE,
  password VARCHAR(255) NOT NULL,
  role ENUM('admin','user') NOT NULL DEFAULT 'user',
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS activity_types (
  id INT AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  score_per_unit DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  unit_label VARCHAR(30) NOT NULL DEFAULT 'ครั้ง',
  is_active TINYINT(1) NOT NULL DEFAULT 1,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS logs (
  id INT AUTO_INCREMENT PRIMARY KEY,
  user_id INT NOT NULL,
  activity_type_id INT NOT NULL,
  qty DECIMAL(10,2) NOT NULL DEFAULT 1.00,
  note VARCHAR(255),
  logged_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  INDEX (user_id), INDEX (activity_type_id), INDEX (logged_at),
  CONSTRAINT fk_u FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
  CONSTRAINT fk_t FOREIGN KEY (activity_type_id) REFERENCES activity_types(id) ON DELETE RESTRICT
) ENGINE=InnoDB;

INSERT INTO activity_types (name, score_per_unit, unit_label, is_active) VALUES
('รีไซเคิลขยะ',1.00,'ครั้ง',1),
('ปลูกต้นไม้',5.00,'ต้น',1),
('ลดใช้ไฟฟ้า',2.00,'ชั่วโมง',1);
```

---

## ไฟล์หลัก (อธิบายสั้น)

* `config.php`
  ตั้งค่า DB และปิดการแสดง error บนจอเพื่อไม่ให้รบกวนการแข่ง
* `functions.php`

  * `DB` : เชื่อม MySQLi (มี `p()` และ `q()`)
  * `U` : `needLogin()`, `isAdmin()`
  * `Users` : `signin()`, `create()`, `listAll()`
  * `Types` : `active()`
  * `Logs` : `add()`, `list()`
* `index.php`
  หน้าเดียว: สมัคร/เข้าสู่ระบบ/บันทึก/บันทึกแทน/ตารางผล
