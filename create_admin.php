<?php
require_once 'functions.php'; // โหลดฟังก์ชัน
$db=new DB(); $u=new Users($db); // อ็อบเจ็กต์
// ลบ admin เดิมก่อนกันซ้ำ
$db->p('DELETE FROM users WHERE username="admin"')->execute(); // ลบถ้ามี
// สร้าง admin ใหม่: admin / 123456
$ok=$u->create('Administrator','admin','123456','admin'); // สร้างแอดมิน
echo $ok?'สร้าง admin: admin / 123456 แล้ว':'ไม่สำเร็จ'; // แสดงผลลัพธ์
