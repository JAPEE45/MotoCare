<?php
    include_once "db.php";
    include "mailer.php";
    $data = json_decode(file_get_contents("php://input"),true);
    if($data){
       try {
         $fullname = $data['fullname'];
        $email = $data['email'];
        $contact = $data['phone'];
        $status = $data['status'];
        $address = $data['address'];
        $password = $data['password'];
        $shop_id = $data['shop_id'];
        $role = "staff";
        $stmt = $conn->prepare("INSERT INTO user (fullname, email, contact, status, address, shop_id, role, password) VALUES (?,?,?,?,?,?,?,?)");
        $stmt->bind_param("sssssiss", $fullname, $email, $contact, $status, $address, $shop_id, $role, $password);
        if($stmt->execute()){
            $title = "Repair Hub: Staff Added!";
         $content = "<h1>Hi! {$fullname}</h1>
                <p>Here's your account:</p>
                <p>Email: {$email}</p>
                <p>Password: {$password}</p>";

            sendEmail($email,$title, $content );
            echo json_encode(['success'=> true]);    
        }else{
            echo json_encode(['error'=> $stmt->error]);   
        }
       } catch (Exception $th) {
         echo json_encode(['error'=> $th]);   
       }

    }else{
        echo json_encode(['error'=> "no data"]);
    }
    $stmt->close();
    $conn->close();
?>