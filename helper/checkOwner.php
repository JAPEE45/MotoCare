<?php
  include 'db.php';  
  session_start();
  $email_id = $_SESSION['user_id'];
    if(empty($email_id)){
        header("Location: /MotoCare/html/signin.php?msg=walang user");
     exit();
    }
$smtp = $conn->prepare("SELECT u.fullname, u.id, s.id as shop_id, s.name as shop_name, u.role FROM user u JOIN shop s ON u.shop_id = s.id WHERE u.ID = ?");
$smtp->bind_param("i",$email_id);
$smtp->execute();
$result = $smtp->get_result();
if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  if($row['role'] != "owner"){
    session_start();
    if(empty($_SESSION['shop_id'])){
        $_SESSION['shop_id'] = $row['shop_id'];
    }
    session_abort();
    header("Location: /MotoCare/html/signin.php?msg=reject");
     exit();
  }
}else{
   header("Location: /MotoCare/html/signin.php?msg=".$email_id);
     exit();
  
}
?>