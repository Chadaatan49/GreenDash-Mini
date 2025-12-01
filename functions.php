<?php
require_once 'config.php'; // โหลดค่าฐานข้อมูล
if (session_status()===PHP_SESSION_NONE) session_start(); // เริ่ม session ถ้ายังไม่เริ่ม

class DB{ // คลาสเชื่อมฐานข้อมูล
  private $c; // ตัวแปรเก็บการเชื่อมต่อ
  function __construct(){ // สร้างอ็อบเจ็กต์แล้วเชื่อมต่อทันที
    global $DB_HOST,$DB_USER,$DB_PASS,$DB_NAME; // ใช้ค่าจาก config
    $this->c=new mysqli($DB_HOST,$DB_USER,$DB_PASS,$DB_NAME); // เชื่อมต่อ
    if($this->c->connect_error) die('DB'); // ถ้าพลาดให้หยุดด้วยข้อความสั้น
    $this->c->set_charset('utf8mb4'); // ตั้ง charset
  }
  function p($sql){ return $this->c->prepare($sql); } // คืน prepared statement
  function q($sql){ return $this->c->query($sql); }   // รัน query ตรง ๆ
}

class U{ // คลาสยูทิลิตีแบบสั้น
  static function e($s){ return htmlspecialchars((string)$s,ENT_QUOTES,'UTF-8'); } // escape ข้อความ
  static function needLogin(){ if(empty($_SESSION['uid'])){ header('Location: signin.php'); exit; } } // ต้องล็อกอิน
  static function isAdmin(){ return !empty($_SESSION['role']) && $_SESSION['role']==='admin'; } // เป็นแอดมิน?
}

class Users{ // ฟังก์ชันเกี่ยวกับผู้ใช้
  private $db; // เก็บ DB
  function __construct($db){ $this->db=$db; } // รับ DB
  function signin($u,$p){ // ล็อกอิน
    $st=$this->db->p('SELECT id,fullname,username,password,role FROM users WHERE username=? LIMIT 1'); // เตรียม SQL
    $st->bind_param('s',$u); $st->execute(); $r=$st->get_result()->fetch_assoc(); $st->close(); // รัน/อ่าน/ปิด
    if(!$r) return false; // ไม่พบผู้ใช้
    if(!password_verify($p,$r['password'])) return false; // รหัสไม่ตรง
    return $r; // คืนข้อมูลผู้ใช้
  }
  function get($id){ // ดึงข้อมูลผู้ใช้ตาม id
    $st=$this->db->p('SELECT id,fullname,username,role FROM users WHERE id=? LIMIT 1'); // SQL
    $st->bind_param('i',$id); $st->execute(); $r=$st->get_result()->fetch_assoc(); $st->close(); return $r?:null; // คืนค่า
  }
  function existsOther($username,$me){ // ชื่อผู้ใช้ซ้ำ (ไม่นับตัวเอง)?
    $st=$this->db->p('SELECT id FROM users WHERE username=? AND id<>? LIMIT 1'); // SQL
    $st->bind_param('si',$username,$me); $st->execute(); $ok=$st->get_result()->num_rows>0; $st->close(); return $ok; // true ถ้าซ้ำ
  }
//  function updateProfile($id,$fn,$un){ // อัปเดตโปรไฟล์
//    $st=$this->db->p('UPDATE users SET fullname=?,username=? WHERE id=?'); // SQL
//    $st->bind_param('ssi',$fn,$un,$id); $ok=$st->execute(); $st->close(); return $ok; // คืนผล
//  }
//  function changePassword($id,$old,$new){ // เปลี่ยนรหัสผ่าน
//    $st=$this->db->p('SELECT password FROM users WHERE id=? LIMIT 1'); $st->bind_param('i',$id); $st->execute(); $r=$st->get_result()->fetch_assoc(); $st->close(); // ดึง hash เดิม
//    if(!$r) return false; // ไม่พบผู้ใช้
//    if(!password_verify($old,$r['password'])) return false; // รหัสเดิมไม่ตรง
//    $h=password_hash($new,PASSWORD_BCRYPT); // สร้าง hash ใหม่
//    $st2=$this->db->p('UPDATE users SET password=? WHERE id=?'); $st2->bind_param('si',$h,$id); $ok=$st2->execute(); $st2->close(); return $ok; // อัปเดต
//  }
//  function resetTemp($username){ // สร้างรหัสผ่านใหม่แบบชั่วคราวง่าย ๆ
//    $st=$this->db->p('SELECT id FROM users WHERE username=? LIMIT 1'); $st->bind_param('s',$username); $st->execute(); $r=$st->get_result()->fetch_assoc(); $st->close(); // หา user
//    if(!$r) return false; // ไม่พบ
//    $tmp=substr(bin2hex(random_bytes(4)),0,8); // สุ่ม 8 ตัวอักษรง่าย ๆ
//    $h=password_hash($tmp,PASSWORD_BCRYPT); // แฮช
//    $st2=$this->db->p('UPDATE users SET password=? WHERE id=?'); $st2->bind_param('si',$h,$r['id']); $ok=$st2->execute(); $st2->close(); // เซฟ
//    return $ok?$tmp:false; // คืนรหัสใหม่ถ้าสำเร็จ
//  }
  function listAll(){ // ดึงผู้ใช้ทั้งหมด (ให้ admin ใช้เลือก)
    $rs=$this->db->q('SELECT id,fullname,username,role FROM users ORDER BY fullname ASC'); return $rs?$rs->fetch_all(MYSQLI_ASSOC):array(); // คืนลิสต์
  }
  function create($fn,$un,$pw,$role){ // สร้างผู้ใช้ใหม่
    $st=$this->db->p('SELECT id FROM users WHERE username=? LIMIT 1'); $st->bind_param('s',$un); $st->execute(); $ex=$st->get_result()->num_rows>0; $st->close(); if($ex) return false; // ซ้ำ = false
    $h=password_hash($pw,PASSWORD_BCRYPT); // แฮชรหัส
    $role=($role==='admin')?'admin':'user'; // บังคับค่า role
    $st2=$this->db->p('INSERT INTO users(fullname,username,password,role) VALUES(?,?,?,?)'); $st2->bind_param('ssss',$fn,$un,$h,$role); $ok=$st2->execute(); $st2->close(); return $ok; // บันทึก
  }
}

class Types{ // ประเภทกิจกรรม
  private $db; function __construct($db){ $this->db=$db; } // เก็บ DB
  function active(){ $rs=$this->db->q('SELECT id,name,score_per_unit,unit_label FROM activity_types WHERE is_active=1 ORDER BY name ASC'); return $rs?$rs->fetch_all(MYSQLI_ASSOC):array(); } // คืนลิสต์
}

class Logs{ // บันทึกกิจกรรม
  private $db; function __construct($db){ $this->db=$db; } // เก็บ DB
  function add($uid,$tid,$qty,$note){ $st=$this->db->p('INSERT INTO logs(user_id,activity_type_id,qty,note,logged_at) VALUES(?,?,?,?,NOW())'); $st->bind_param('iids',$uid,$tid,$qty,$note); $ok=$st->execute(); $st->close(); return $ok; } // เพิ่ม log
  function get($uid,$isAdmin,$limit=100,$q=''){ // ดึงรายการ
    $q=trim($q); // ตัดช่องว่าง
    if($isAdmin){ // แอดมินเห็นทุกคน
      if($q!==''){ $like='%'.$q.'%'; $st=$this->db->p('SELECT l.id,l.logged_at,u.fullname user_name,t.name type_name,l.qty,(l.qty*t.score_per_unit) score,l.note FROM logs l JOIN users u ON u.id=l.user_id JOIN activity_types t ON t.id=l.activity_type_id WHERE u.fullname LIKE ? OR u.username LIKE ? OR t.name LIKE ? OR l.note LIKE ? ORDER BY l.logged_at DESC LIMIT ?'); $st->bind_param('ssssi',$like,$like,$like,$like,$limit); }
      else{ $st=$this->db->p('SELECT l.id,l.logged_at,u.fullname user_name,t.name type_name,l.qty,(l.qty*t.score_per_unit) score,l.note FROM logs l JOIN users u ON u.id=l.user_id JOIN activity_types t ON t.id=l.activity_type_id ORDER BY l.logged_at DESC LIMIT ?'); $st->bind_param('i',$limit); }
    }else{ // ผู้ใช้ทั่วไปเห็นของตัวเอง
      if($q!==''){ $like='%'.$q.'%'; $st=$this->db->p('SELECT l.id,l.logged_at,u.fullname user_name,t.name type_name,l.qty,(l.qty*t.score_per_unit) score,l.note FROM logs l JOIN users u ON u.id=l.user_id JOIN activity_types t ON t.id=l.activity_type_id WHERE l.user_id=? AND (t.name LIKE ? OR l.note LIKE ?) ORDER BY l.logged_at DESC LIMIT ?'); $st->bind_param('issi',$uid,$like,$like,$limit); }
      else{ $st=$this->db->p('SELECT l.id,l.logged_at,u.fullname user_name,t.name type_name,l.qty,(l.qty*t.score_per_unit) score,l.note FROM logs l JOIN users u ON u.id=l.user_id JOIN activity_types t ON t.id=l.activity_type_id WHERE l.user_id=? ORDER BY l.logged_at DESC LIMIT ?'); $st->bind_param('ii',$uid,$limit); }
    }
    $st->execute(); $rows=$st->get_result()->fetch_all(MYSQLI_ASSOC); $st->close(); return $rows; // คืนผล
  }
}
