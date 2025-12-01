<?php
// header.php: ต้องให้ทุกหน้า require_once 'functions.php' มาก่อนเสมอ
?>
<!doctype html> <!-- ใช้ HTML5 -->
<html lang="th"> <!-- ภาษาไทย -->
<head> <!-- ส่วนหัว -->
  <meta charset="utf-8"> <!-- UTF-8 -->
  <meta name="viewport" content="width=device-width, initial-scale=1"> <!-- รองรับมือถือ -->
  <title><?= U::e(isset($title)?$title:'GreenDash') ?></title> <!-- ชื่อหน้า -->
  <link rel="stylesheet" href="css/bootstrap.min.css"> <!-- โหลด Bootstrap ออฟไลน์ -->
</head>
<body class="bg-light"> <!-- เริ่มเนื้อหา -->
<nav class="navbar navbar-expand-lg navbar-dark bg-success"> <!-- แถบนำทาง -->
  <div class="container"> <!-- กล่องกึ่งกลาง -->
    <a class="navbar-brand fw-bold" href="index.php">GreenDash</a> <!-- โลโก้ -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav"><span class="navbar-toggler-icon"></span></button> <!-- ปุ่มพับเมนู -->
    <div class="collapse navbar-collapse" id="nav"> <!-- เมนู -->
      <ul class="navbar-nav ms-auto"> <!-- รายการเมนู -->
        <li class="nav-item"><a class="nav-link" href="index.php">หน้าแรก</a></li> <!-- หน้าแรก -->
        <?php if(empty($_SESSION['uid'])): ?> <!-- ถ้ายังไม่ล็อกอิน -->
          <li class="nav-item"><a class="nav-link" href="register.php">สมัคร</a></li> <!-- สมัคร -->
          <li class="nav-item"><a class="nav-link" href="signin.php">เข้าสู่ระบบ</a></li> <!-- ล็อกอิน -->
        <?php else: ?> <!-- ถ้าล็อกอินแล้ว -->
          <?php if(U::isAdmin()): ?> <!-- ถ้าเป็นแอดมิน -->
            <li class="nav-item"><a class="nav-link" href="logs.php">บันทึกทั้งหมด</a></li> <!-- ดูทั้งหมด -->
            <li class="nav-item"><a class="nav-link" href="admin_add_log.php">บันทึกแทน</a></li> <!-- บันทึกแทน -->
          <?php else: ?> <!-- ผู้ใช้ทั่วไป -->
            <li class="nav-item"><a class="nav-link" href="logs.php">บันทึกของฉัน</a></li> <!-- ของฉัน -->
            <li class="nav-item"><a class="nav-link" href="add_log.php">บันทึกกิจกรรม</a></li> <!-- เพิ่ม -->
          <?php endif; ?>
          <li class="nav-item"><a class="nav-link" href="logout.php">ออกจากระบบ</a></li> <!-- ออก -->
        <?php endif; ?>
      </ul> <!-- จบเมนู -->
    </div> <!-- จบ collapse -->
  </div> <!-- จบ container -->
</nav> <!-- จบ navbar -->
<div class="container py-4"> <!-- เนื้อหาหลักเริ่ม -->
