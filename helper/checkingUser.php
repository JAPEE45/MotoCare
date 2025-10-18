<?php
  include 'db.php';  
  session_start();
  if (!isset($conn) || $conn->connect_error) {
    die("Database connection error.");
  }
  if(empty($_SESSION['user'])){
      header("Location: /MotoCare/html/signin.php");
      exit();
  }
  $smtp = $conn->prepare("SELECT * FROM user WHERE email_id = ?");
  $smtp->bind_param("s",$_SESSION['user']);
  $smtp->execute();
  $result = $smtp->get_result();
  if($result->num_rows > 0){
    $row = $result->fetch_assoc();
  }else{
    header("Location: /MotoCare/html/signin.php");
    exit();
  }
?>