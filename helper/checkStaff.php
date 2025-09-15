<?php
  include 'db.php';  
  session_start();
    if(empty($_SESSION['user_id'])){
      header("Location: ./MotoCare/html/signin.php");
     exit();
    }
$smtp = $conn->prepare("SELECT * FROM user WHERE id = ?");
$smtp->bind_param("s",$_SESSION['user_id']);
$smtp->execute();
$result = $smtp->get_result();
if($result->num_rows > 0){
  $row = $result->fetch_assoc();
  
}else{
  echo "<h2>walaa</h2>";
//    header("Location: ./MotoCare/html/signin.php");
//      exit();
}
?>