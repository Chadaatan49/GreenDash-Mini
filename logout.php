<?php
require_once 'functions.php'; // โหลดฟังก์ชัน
session_destroy(); // ล้าง session ทั้งหมด
header('Location: index.php'); // กลับหน้าแรก
exit; // จบ
