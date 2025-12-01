<?php
$title='บันทึกแทนผู้ใช้'; // ชื่อหน้า
require_once 'functions.php'; // โหลดฟังก์ชัน
U::needLogin(); if(!U::isAdmin()){ header('Location: index.php'); exit; } // ต้องเป็นแอดมิน
$db=new DB(); $users=new Users($db); $types=new Types($db); $logs=new Logs($db); // อ็อบเจ็กต์
$err=''; // เก็บข้อความผิดพลาดสั้น ๆ
if($_SERVER['REQUEST_METHOD']==='POST'){ // ถ้ากดบันทึก
  $user_id=(int)($_POST['user_id']??0); $type_id=(int)($_POST['activity_type_id']??0); $qty=(float)($_POST['qty']??0); $note=trim($_POST['note']??''); // รับค่า
  if($user_id>0 && $type_id>0 && $qty>0){ $ok=$logs->add($user_id,$type_id,$qty,$note); if($ok){ header('Location: logs.php'); exit; } else { $err='บันทึกไม่สำเร็จ'; } } // บันทึก
  else{ $err='กรอกไม่ครบ'; } // ไม่ครบ
}
$allUsers=$users->listAll(); $allTypes=$types->active(); // ดึงลิสต์
include 'header.php'; // ส่วนหัว
?>
<div class="row justify-content-center"><div class="col-md-7"> <!-- จัดกลาง -->
  <div class="card"><div class="card-body"> <!-- กล่อง -->
    <h5>บันทึกกิจกรรมแทนผู้ใช้ (Admin)</h5> <!-- หัวข้อ -->
    <?php if($err!==''): ?><div class="alert alert-danger"><?=$err?></div><?php endif; ?> <!-- แจ้งสั้น -->
    <form method="post"> <!-- ฟอร์ม -->
      <div class="mb-3"><label class="form-label">เลือกผู้ใช้</label><select name="user_id" class="form-select" required><option value="">-- เลือก --</option><?php foreach($allUsers as $u): ?><option value="<?=$u['id']?>"><?=U::e($u['fullname'])?> (<?=U::e($u['username'])?>)</option><?php endforeach; ?></select></div> <!-- ผู้ใช้ -->
      <div class="mb-3"><label class="form-label">ประเภทกิจกรรม</label><select name="activity_type_id" class="form-select" required><option value="">-- เลือก --</option><?php foreach($allTypes as $t): ?><option value="<?=$t['id']?>"><?=U::e($t['name'])?> (<?=U::e($t['unit_label'])?> * <?=U::e($t['score_per_unit'])?>)</option><?php endforeach; ?></select></div> <!-- ประเภท -->
      <div class="mb-3"><label class="form-label">จำนวน</label><input type="number" step="0.01" min="0.01" name="qty" class="form-control" required></div> <!-- จำนวน -->
      <div class="mb-3"><label class="form-label">หมายเหตุ</label><input name="note" class="form-control"></div> <!-- หมายเหตุ -->
      <button class="btn btn-success">บันทึกแทนผู้ใช้</button> <!-- ปุ่ม -->
      <a href="logs.php" class="btn btn-secondary">ยกเลิก</a> <!-- ยกเลิก -->
    </form>
  </div></div>
</div></div>
<?php include 'footer.php'; // ส่วนท้าย ?>
