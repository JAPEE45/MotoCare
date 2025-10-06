<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign up for MotoCare</title>
     <script src="https://accounts.google.com/gsi/client" async defer></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/styles/signup.css">
</head>
<body>
    <div class="container">
        <div class="signup-container">
            <div class="signin-link">
                <span>Already have an account? <a href="./signin.php" class="signin-link-text">Sign in</a></span>
            </div>
            <div class="signup-header">
                <h1>Sign up</h1>
            </div>
            
             <div id="g_id_onload"
           data-client_id="502340336274-s2q3a73k0p58djlia5mco0homdr8fljl.apps.googleusercontent.com"
           data-context="signin"
           data-ux_mode="popup"
           data-callback="handleCredentialResponse"
           data-auto_prompt="false">
      </div>

      <!-- Google Sign-In button will be placed here -->
      <div id="buttonDiv"></div>
            
            <div class="divider">
                <span>Please select your email first</span>
            </div>
            

                <div class="mb-2">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" placeholder="Enter your full name">
                </div>
                
                <div class="mb-2">
                    <label for="contact" class="form-label">Contact No.</label>
                    <input type="tel" class="form-control" id="contact" placeholder="Enter your contact number">
                </div>
                
                <div class="mb-2">
                    <label for="address" class="form-label">Address</label>
                    <textarea class="form-control" id="address" rows="3" id="address" placeholder="Enter your address"></textarea>
                </div>
                <div class="mb-2">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter your email">
                </div>
                
                <div class="mb-2">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" placeholder="Create a password">
                    <div class="password-requirements">
                        Password should be at least 15 characters OR at least 8 characters including a number and a lowercase letter.
                    </div>
                </div>
                
                <div class="mb-2">
                    <label for="confirmPassword" class="form-label">Confirm Password</label>
                    <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm your password">
                </div>

                <div class="terms-text">
                    By creating an account, you agree to the <a href="#">Terms of Service</a>.
                </div>
                
                <button id="registerBtn" class="btn create-account-btn">
                    Create account
                    <i class="fas fa-arrow-right ms-1"></i>
                </button>
                
        </div>
    </div>
      <script>
        let data = ""
    const GOOGLE_AUTH = "502340336274-s2q3a73k0p58djlia5mco0homdr8fljl.apps.googleusercontent.com";
    const email = document.getElementById("email");
    const name = document.getElementById("name");
    email.disabled = true
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
const btn = document.getElementById("registerBtn");
    function handleCredentialResponse(response) {
      console.log("Encoded JWT ID token: ", response.credential);

      // Decode JWT payload
      data = JSON.parse(atob(response.credential.split('.')[1]));
      console.log("User Info:", data);
      localStorage.setItem("email", JSON.stringify(data))

      // Show user info in page
    //   document.body.innerHTML += `
    //     <div class="alert alert-success mt-3">
    //       <strong>Welcome, ${data.name}</strong><br>
    //       Email: ${data.email}<br>
    //       <img src="${data.picture}" alt="profile" style="border-radius:50%;width:50px;height:50px;">
    //     </div>
    //   `;
    email.value = data.email
    name.value = data.name
    document.getElementById("buttonDiv").innerHTML = data.email
    
    }
    async function addUser() {
      btn.disabled = true
       btn.innerHTML = "Please Wait....."
  try {
    const datas = {
      email: data.email,
      password: document.getElementById("password").value, 
      picture: data.picture,
      fullname: data.name,   
      email_id: data.sub,    
      address: document.getElementById("address").value, 
      contact: document.getElementById("contact").value, 
    };

    const a = await fetch("../helper/addUser.php", {
      method: "POST",
      headers: {  
        "Content-Type": "application/json",
      },
      body: JSON.stringify(datas),
    });

    const c = await a.json();
    console.log(c);
    if(c.status == "success"){
        window.location.href = "/MotoCare/html/signin.php"
    }
    if(c.status == "error"){
        alert(c.message)
        window.location.reload()
    }
  } catch (error) {
    console.log("Fetch error:", error);
  }
 
}
document.getElementById("registerBtn").addEventListener("click", ()=>{
    addUser();
})
    
  </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.0/js/bootstrap.bundle.min.js"></script>
</body>
</html>