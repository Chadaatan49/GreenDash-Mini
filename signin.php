<?php
$title='เข้าสู่ระบบ'; // ชื่อหน้า
require_once 'functions.php'; // โหลดฟังก์ชัน
if(!empty($_SESSION['uid'])){ header('Location: logs.php'); exit; } // ถ้าล็อกอินแล้วไป logs
$db=new DB(); $users=new Users($db); $err=''; // เตรียมใช้งานและตัวแปร error
if($_SERVER['REQUEST_METHOD']==='POST'){ // ถ้ากดส่งฟอร์ม
  $un=trim($_POST['username']??''); $pw=$_POST['password']??''; // รับค่า
  $row=$users->signin($un,$pw); // ตรวจล็อกอิน
  if($row){ $_SESSION['uid']=(int)$row['id']; $_SESSION['name']=$row['fullname']; $_SESSION['role']=$row['role']; header('Location: logs.php'); exit; } // สำเร็จ → ตั้ง session แล้วไป logs
  else{ $err='ผิดพลาด'; } // ผิดชื่อหรือรหัส
}
include 'header.php'; // ส่วนหัว
?>
<div class="row justify-content-center"><div class="col-md-5"> <!-- จัดกลาง -->
  <div class="card"><div class="card-body"> <!-- กล่อง -->
    <h5>เข้าสู่ระบบ</h5> <!-- หัวข้อ -->
    <?php if($err!==''): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?> <!-- แจ้งสั้น -->
    <form method="post"> <!-- ฟอร์ม -->
      <div class="mb-3"><label class="form-label">ชื่อผู้ใช้</label><input name="username" class="form-control" required></div> <!-- ผู้ใช้ -->
      <div class="mb-3"><label class="form-label">รหัสผ่าน</label><input type="password" name="password" class="form-control" required></div> <!-- รหัส -->
      <button class="btn btn-success w-100">เข้าสู่ระบบ</button> <!-- ปุ่ม -->
    </form>
  </div></div>
</div></div>
<?php include 'footer.php'; // ส่วนท้าย ?>
