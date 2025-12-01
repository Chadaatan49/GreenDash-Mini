<?php
$title='GreenDash — Log fast. See impact.'; // ตั้งชื่อหน้า
require_once 'functions.php'; // โหลดฟังก์ชันหลัก
include 'header.php'; // ส่วนหัว
?>
<div class="row align-items-center"> <!-- แถวหลัก -->
  <div class="col-lg-7"> <!-- คอลัมน์ซ้าย -->
    <h3>GreenDash (กรีนแดช)</h3> <!-- หัวข้อ -->
    <p class="lead">บันทึกกิจกรรมสีเขียวได้รวดเร็ว และเห็นผลคะแนนสะสมได้ทันที</p> <!-- คำโปรย -->
    <a href="register.php" class="btn btn-success me-2">สมัครสมาชิก</a> <!-- ปุ่มสมัคร -->
    <a href="signin.php" class="btn btn-outline-success">เข้าสู่ระบบ</a> <!-- ปุ่มล็อกอิน -->
  </div>
  <div class="col-lg-5 mt-4 mt-lg-0"> <!-- คอลัมน์ขวา -->
    <div class="card"><div class="card-body"> <!-- กล่อง -->
      <h5>วิธีใช้งาน</h5> <!-- หัวข้อย่อย -->
      <ol class="mb-0"> <!-- ลำดับขั้น -->
        <li>สมัครสมาชิกและเข้าสู่ระบบ</li> <!-- ขั้นตอน 1 -->
        <li>เลือกประเภทกิจกรรมและกรอกจำนวน</li> <!-- ขั้นตอน 2 -->
        <li>ดูคะแนนสะสม/ประวัติของตนเอง</li> <!-- ขั้นตอน 3 -->
      </ol>
    </div></div>
  </div>
</div>
<?php include 'footer.php'; // ส่วนท้าย ?>
