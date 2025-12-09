<?php
session_start();
include_once '../helper/db.php';
$error = "";

// Get redirect parameters if provided (from shop-infos.php booking)
$redirect = isset($_GET['redirect']) ? $_GET['redirect'] : '';
$shop_id = isset($_GET['shop_id']) ? intval($_GET['shop_id']) : 0;

// Store redirect info in session for use after login
if ($redirect === 'map' && $shop_id > 0) {
    $_SESSION['redirect_after_login'] = 'map';
    $_SESSION['redirect_shop_id'] = $shop_id;
}

function login($username, $password, $conn) {
    $stmt = $conn->prepare("SELECT role, ID, username, email_id, password FROM user WHERE email = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if ($password === $user['password']) {
            $_SESSION['user'] = $user['email_id'];
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['user_id'] = $user['ID']; 
            return true;
        } else {
            return false;
        }
    } else {
        return false; // user not found
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (login($username, $password, $conn)) {
        $uid = $_SESSION['user_id'];

        // Check if there's a redirect after login (from shop-infos booking)
        if (isset($_SESSION['redirect_after_login']) && $_SESSION['redirect_after_login'] === 'map' && isset($_SESSION['redirect_shop_id'])) {
            $shopId = $_SESSION['redirect_shop_id'];
            // Clear redirect session variables
            unset($_SESSION['redirect_after_login']);
            unset($_SESSION['redirect_shop_id']);
            header("Location: ./customer/map.php?shop_id=" . $shopId);
            exit();
        }

        if ($_SESSION['role'] === "staff") {
            header("Location: /MotoCare/html/staff/dashboard.php?uid=".$uid);
            exit();
        } elseif ($_SESSION['role'] === "admin") {
            header("Location: ./admin/dashboard.php");
            exit();
        } elseif ($_SESSION['role'] === "owner") {
            header("Location: ./owner/dashboard.php");
            exit();
        } else {
            header("Location: ./customer/homepage.php");
            exit();
        }
    } else {
        $error = "Invalid username or password.";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in to AutoRepair Shop</title>
  
  <!-- Google Sign-In script -->
  <script src="https://accounts.google.com/gsi/client" async defer></script>

  <!-- Bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <!-- Custom CSS -->
  <link rel="stylesheet" href="../assets/styles/signin.css">
</head>
<body>
  <div class="motocare-container">

    <div class="signin-card">
      <h1 class="signin-title">Sign in to <span>Auto</span>Repair Shop</h1>
      <p class="signin-subtitle">Let's get started! <?php echo $error ?></p>

      <!-- Regular login form -->
      <form method ="post" action="signin.php">
        <div class="mb-3">
          <input type="text" class="form-control" id="login" name="username" placeholder="Username or email address">
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password">
          <a href="#" class="forgot-password">Forgot password?</a>
        </div>
          
  
        <button id="signinButton" type="submit" class="btn btn-signin">Sign in</button>
        <?php if (!empty($error)): ?>
  <p class="text-danger text-center mt-2"><?php echo $error; ?></p>
<?php endif; ?>


      </form>

      <div class="divider"><span>or</span></div>

      <!-- Google Sign-In initialization -->
      <div id="g_id_onload"
           data-client_id="502340336274-s2q3a73k0p58djlia5mco0homdr8fljl.apps.googleusercontent.com"
           data-context="signin"
           data-ux_mode="popup"
           data-callback="handleCredentialResponse"
           data-auto_prompt="false">
      </div>

      <!-- Google Sign-In button will be placed here -->
      <div id="buttonDiv"></div>

    </div>
  </div>

  <script>
    const GOOGLE_AUTH = "502340336274-s2q3a73k0p58djlia5mco0homdr8fljl.apps.googleusercontent.com";

    window.onload = function () {
      google.accounts.id.initialize({
        client_id: GOOGLE_AUTH,
        callback: handleCredentialResponse,
        auto_select: false
      });

      // Render Google Sign-In button
      google.accounts.id.renderButton(
        document.getElementById("buttonDiv"),
        { theme: "outline", size: "large" }
      );
    };

    function handleCredentialResponse(response) {
      console.log("Encoded JWT ID token: ", response.credential);

      // Decode JWT payload
      const data = JSON.parse(atob(response.credential.split('.')[1]));
      console.log("User Info:", data);
      localStorage.setItem("email", JSON.stringify(data))
      window.location.href = `../helper/autoLogin.php?uid=${data.sub}`

      // Show user info in page
    //   document.body.innerHTML += `
    //     <div class="alert alert-success mt-3">
    //       <strong>Welcome, ${data.name}</strong><br>
    //       Email: ${data.email}<br>
    //       <img src="${data.picture}" alt="profile" style="border-radius:50%;width:50px;height:50px;">
    //     </div>
    //   `;
    }
  </script>

  <!-- Bootstrap JS -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
