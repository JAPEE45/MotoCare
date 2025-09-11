<?php
include_once '../helper/db.php'

?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Sign in to MotoCare · MotoCare</title>
  
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
    <i class="motocare-logo fa-solid fa-motorcycle"></i>

    <div class="signin-card">
      <h1 class="signin-title">Sign in to <span>Moto</span>Care</h1>
      <p class="signin-subtitle">Let's get started!</p>

      <!-- Regular login form -->
      <form>
        <div class="mb-3">
          <input type="text" class="form-control" id="login" name="login" placeholder="Username or email address">
        </div>
        <div class="mb-3">
          <input type="password" class="form-control" id="password" name="password" placeholder="Password">
          <a href="#" class="forgot-password">Forgot password?</a>
        </div>
        <button id="signinButton" type="submit" class="btn btn-signin">Sign in</button>
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
        callback: handleCredentialResponse
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
      window.location.href = 'account.php'

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
