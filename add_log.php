<?php
$title='บันทึกกิจกรรม'; // ชื่อหน้า
require_once 'functions.php'; // โหลดฟังก์ชัน
U::needLogin(); // ต้องล็อกอิน
$db=new DB(); $types=new Types($db); $logs=new Logs($db); // อ็อบเจ็กต์
$err=''; // เก็บข้อความสั้น ๆ
if($_SERVER['REQUEST_METHOD']==='POST'){ // ถ้ากดบันทึก
  $type_id=(int)($_POST['activity_type_id']??0); $qty=(float)($_POST['qty']??0); $note=trim($_POST['note']??''); // รับค่า
  if($type_id>0 && $qty>0){ $ok=$logs->add((int)$_SESSION['uid'],$type_id,$qty,$note); if($ok){ header('Location: logs.php'); exit; } else { $err='บันทึกไม่สำเร็จ'; } } // ถ้าข้อมูลพอ → บันทึก
  else{ $err='กรอกไม่ครบ'; } // ไม่ครบ
}
$all=$types->active(); // ดึงประเภท
include 'header.php'; // ส่วนหัว
?>
<div class="row justify-content-center"><div class="col-md-6"> <!-- จัดกลาง -->
  <div class="card"><div class="card-body"> <!-- กล่อง -->
    <h5>บันทึกกิจกรรมของฉัน</h5> <!-- หัวข้อ -->
    <?php if($err!==''): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?> <!-- แจ้งสั้น -->
    <form method="post"> <!-- ฟอร์ม -->
      <div class="mb-3">
        <label class="form-label">ประเภทกิจกรรม</label> <!-- ป้าย -->
        <select name="activity_type_id" class="form-select" required> <!-- เลือกประเภท -->
          <option value="">-- เลือก --</option> <!-- ค่าว่าง -->
          <?php foreach($all as $t): ?> <!-- วนรายการ -->
            <option value="<?=$t['id']?>"><?=U::e($t['name'])?> (<?=U::e($t['unit_label'])?> * <?=U::e($t['score_per_unit'])?>)</option> <!-- แสดงชื่อ -->
          <?php endforeach; ?> <!-- จบวน -->
        </select>
      </div>
      <div class="mb-3"><label class="form-label">จำนวน</label><input type="number" step="0.01" min="0.01" name="qty" class="form-control" required></div> <!-- จำนวน -->
      <div class="mb-3"><label class="form-label">หมายเหตุ</label><input name="note" class="form-control"></div> <!-- หมายเหตุ -->
      <button class="btn btn-success">บันทึก</button> <!-- ปุ่ม -->
      <a href="logs.php" class="btn btn-secondary">ยกเลิก</a> <!-- ยกเลิก -->
    </form>
  </div></div>
</div></div>
<?php include 'footer.php'; // ส่วนท้าย ?>
