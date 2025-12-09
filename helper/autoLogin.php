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
    $_SESSION['user_id'] = $row['ID'];
    
    // Check if there's a redirect after login (from shop-infos booking)
    if (isset($_SESSION['redirect_after_login']) && $_SESSION['redirect_after_login'] === 'map' && isset($_SESSION['redirect_shop_id'])) {
        $shopId = $_SESSION['redirect_shop_id'];
        // Clear redirect session variables
        unset($_SESSION['redirect_after_login']);
        unset($_SESSION['redirect_shop_id']);
        header("Location: /MotoCare/html/customer/map.php?shop_id=" . $shopId);
        exit();
    }
    
     header("Location: /MotoCare/html/customer/homepage.php");
     exit();
  }
  
}
?>