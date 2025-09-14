<?php
  include 'db.php';  
  session_start();
    
$smtp = $conn->prepare("SELECT * FROM user WHERE email_id = ?");
$smtp->bind_param("s",$_GET['uid']);
$smtp->execute();
$result = $smtp->get_result();
if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  if($row['role'] == 'customer'){
    $_SESSION['user'] = $row['email_id'];
     header("Location: /MotoCare/html/customer/homepage.php");
     exit();
  }
  
}
?>