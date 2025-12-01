<?php
$title='บันทึกกิจกรรม'; // ชื่อหน้า
require_once 'functions.php'; // โหลดฟังก์ชัน
U::needLogin(); // ต้องล็อกอิน
$db=new DB(); $L=new Logs($db); // อ็อบเจ็กต์
$isAdmin=U::isAdmin(); $uid=(int)$_SESSION['uid']; // เช็คสิทธิ์/ไอดี
$q=trim($_GET['q']??''); // คำค้นหา
$rows=$L->get($uid,$isAdmin,100,$q); // ดึงรายการ
include 'header.php'; // ส่วนหัว
?>
<div class="d-flex flex-wrap justify-content-between align-items-center mb-3"> <!-- แถวบน -->
  <div class="mb-2"><h5 class="mb-0"><?=$isAdmin?'บันทึกทั้งหมด (ผู้ดูแล)':'บันทึกของฉัน'?></h5><?php if($q!==''): ?><div class="small text-muted">ค้นหา: "<?=U::e($q)?>"</div><?php endif; ?></div> <!-- ชื่อหัว -->
  <div class="d-flex gap-2 mb-2"> <!-- ปุ่มและค้นหา -->
    <?php if($isAdmin): ?><a href="admin_add_log.php" class="btn btn-outline-success btn-sm">+ บันทึกแทน</a><?php endif; ?> <!-- ปุ่มแอดมิน -->
    <a href="add_log.php" class="btn btn-success btn-sm">+ บันทึกของฉัน</a> <!-- ปุ่มของฉัน -->
    <form method="get" class="d-flex gap-2"> <!-- ฟอร์มค้นหา -->
      <input name="q" class="form-control form-control-sm" placeholder="ชื่อ/กิจกรรม/หมายเหตุ" value="<?=U::e($q)?>"> <!-- ช่องค้นหา -->
      <button class="btn btn-outline-primary btn-sm">ค้นหา</button> <!-- ปุ่มค้นหา -->
      <?php if($q!==''): ?><a href="logs.php" class="btn btn-outline-secondary btn-sm">ล้าง</a><?php endif; ?> <!-- ล้าง -->
    </form>
  </div>
</div>

<div class="card"><div class="card-body"> <!-- กล่องตาราง -->
  <div class="table-responsive"> <!-- เลื่อนแนวนอนได้ -->
    <table class="table table-striped align-middle"> <!-- ตาราง -->
      <thead><tr><th>#</th><th>เวลา</th><th>ผู้บันทึก</th><th>ประเภท</th><th class="text-end">จำนวน</th><th class="text-end">คะแนน</th><th>หมายเหตุ</th></tr></thead> <!-- หัวตาราง -->
      <tbody> <!-- ตัวตาราง -->
        <?php if(empty($rows)): ?> <!-- ถ้าไม่มีข้อมูล -->
          <tr><td colspan="7" class="text-center text-muted">ไม่พบข้อมูล</td></tr> <!-- แสดงว่าง -->
        <?php else: $i=1; foreach($rows as $r): ?> <!-- ถ้ามีข้อมูล วนแสดง -->
          <tr>
            <td><?=$i++?></td> <!-- ลำดับ -->
            <td><?=U::e($r['logged_at'])?></td> <!-- เวลา -->
            <td><?=U::e($r['user_name'])?></td> <!-- ผู้บันทึก -->
            <td><?=U::e($r['type_name'])?></td> <!-- ประเภท -->
            <td class="text-end"><?=number_format((float)$r['qty'],2)?></td> <!-- จำนวน -->
            <td class="text-end"><?=number_format((float)$r['score'],2)?></td> <!-- คะแนน -->
            <td><?=U::e($r['note'])?></td> <!-- หมายเหตุ -->
          </tr>
        <?php endforeach; endif; ?> <!-- จบวน -->
      </tbody>
    </table>
  </div>
</div></div>
<?php include 'footer.php'; // ส่วนท้าย ?>
