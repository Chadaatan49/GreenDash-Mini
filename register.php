<?php
$title='สมัครสมาชิก'; // ชื่อหน้า
require_once 'functions.php'; // โหลดฟังก์ชัน
if(!empty($_SESSION['uid'])){ header('Location: logs.php'); exit; } // ถ้าล็อกอินแล้วไปหน้า logs
$db=new DB(); $u=new Users($db); // สร้าง DB/Users
$err=''; // เก็บข้อความสั้น ๆ ถ้าผิด
if($_SERVER['REQUEST_METHOD']==='POST'){ // ถ้ามีการส่งฟอร์ม
  $fn=trim($_POST['fullname']??''); $un=trim($_POST['username']??''); $pw=$_POST['password']??''; // รับค่า
  if($fn!=='' && $un!=='' && $pw!==''){ // ถ้าครบ
    $ok=$u->create($fn,$un,$pw,'user'); // สร้างผู้ใช้ใหม่ role=user
    if($ok){ header('Location: signin.php'); exit; } // สำเร็จ → ไปล็อกอิน
    else{ $err='ชื่อผู้ใช้ซ้ำ'; } // ไม่สำเร็จเพราะซ้ำ
  } else { $err='กรอกให้ครบ'; } // กรอกไม่ครบ
}
include 'header.php'; // ส่วนหัว
?>
<div class="row justify-content-center"><div class="col-md-6"> <!-- กลางหน้า -->
  <div class="card"><div class="card-body"> <!-- กล่อง -->
    <h5>สมัครสมาชิก</h5> <!-- หัวข้อ -->
    <?php if($err!==''): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?> <!-- แจ้งสั้น ๆ -->
    <form method="post"> <!-- ฟอร์ม -->
      <div class="mb-3"><label class="form-label">ชื่อ-สกุล</label><input name="fullname" class="form-control" required></div> <!-- ชื่อ -->
      <div class="mb-3"><label class="form-label">ชื่อผู้ใช้</label><input name="username" class="form-control" required></div> <!-- ผู้ใช้ -->
      <div class="mb-3"><label class="form-label">รหัสผ่าน</label><input type="password" name="password" class="form-control" required></div> <!-- รหัส -->
      <button class="btn btn-success">สมัครสมาชิก</button> <!-- ปุ่ม -->
      <a href="signin.php" class="btn btn-outline-secondary">เข้าสู่ระบบ</a> <!-- ลิงก์ -->
    </form>
  </div></div>
</div></div>
<?php include 'footer.php'; // ส่วนท้าย ?>
