<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Account</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="../assets/styles/account.css">
</head>
<body>
    <div class="container-fluid">
        <div class="main-container">
            <div class="header-section">
                <button class="back-btn" onclick="goBack()">
                    <i class="fas fa-arrow-left"></i>
                </button>
                <h1 class="header-title">Edit Account</h1>
                <p class="header-subtitle">Manage your personal information and settings</p>
            </div>

            <ul class="nav nav-tabs" id="accountTabs" role="tablist">
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link active w-100" id="personal-tab" data-bs-toggle="tab" data-bs-target="#personal" type="button" role="tab">
                        <i class="fas fa-user me-2"></i>Personal Info
                    </button>
                </li>
                <li class="nav-item flex-fill" role="presentation">
                    <button class="nav-link w-100" id="account-tab" data-bs-toggle="tab" data-bs-target="#account" type="button" role="tab">
                        <i class="fas fa-cog me-2"></i>Account Settings
                    </button>
                </li>
            </ul>

            <div class="tab-content" id="accountTabsContent">
                <div class="tab-pane fade show active fade-in" id="personal" role="tabpanel">
                    <div class="section-title">
                        <i class="fas fa-user-edit"></i>
                        Personal Information
                    </div>

                    <form id="personalForm">
                        <div class="row">
                            <div class="col-12 mb-3">
                                <label for="fullName" class="form-label">Full Name</label>
                                <input type="text" class="form-control" id="fullName" placeholder="Enter your full name" value="Juan Dela Cruz">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="contactNo" class="form-label">Contact Number</label>
                                <input type="tel" class="form-control" id="contact" placeholder="Enter your phone number" value="0912-345-6789">
                            </div>
                            
                            <div class="col-md-6 mb-3">
                                <label for="email" class="form-label">Email (Read-only)</label>
                                <input type="email" class="form-control" id="email" value="juan.delacruz@example.com" readonly>
                            </div>
                            
                            <div class="col-12 mb-4">
                                <label for="address" class="form-label">Address</label>
                                <textarea class="form-control" id="address" rows="3" id = 'address' placeholder="Enter your full address"></textarea>
                            </div>
                        </div>

                        <button onclick="addUser()" class="btn btn-custom-primary w-100">
                            <i class="fas fa-save me-2"></i>Save Personal Info
                        </button>
                    </form>
                </div>

                <div class="tab-pane fade" id="account" role="tabpanel">
                    <div class="section-title">
                        <i class="fas fa-user-cog"></i>
                        Account Settings
                    </div>

                    <div class="change-field">
                        <h5><i class="fas fa-envelope me-2"></i>Email Address</h5>
                        <p class="mb-3">Current: juan.delacruz@example.com</p>
                        <button type="button" class="btn btn-custom-secondary w-100" data-bs-toggle="modal" data-bs-target="#emailModal">
                            <i class="fas fa-edit me-2"></i>Change Email
                        </button>
                    </div>

                    <div class="change-field">
                        <h5><i class="fas fa-lock me-2"></i>Password</h5>
                        <p class="mb-3">Last changed 3 months ago</p>
                        <button type="button" class="btn btn-custom-secondary w-100" data-bs-toggle="modal" data-bs-target="#passwordModal">
                            <i class="fas fa-key me-2"></i>Change Password
                        </button>
                    </div>

                    <div class="divider">
                        <span>or</span>
                    </div>
                    
                    <div class="change-field danger">
                        <h5><i class="fas fa-exclamation-triangle me-2"></i>Delete Account</h5>
                        <p class="mb-3">This action cannot be undone. All your data will be permanently deleted.</p>
                        <button type="button" class="btn btn-custom-danger w-100" data-bs-toggle="modal" data-bs-target="#deleteModal">
                            <i class="fas fa-trash me-2"></i>Delete Account
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="emailModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-envelope me-2"></i>Change Email Address</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="emailForm">
                        <div class="mb-3">
                            <label for="currentEmail" class="form-label">Current Email</label>
                            <input type="email" class="form-control" id="currentEmail" value="juan.delacruz@example.com" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="newEmail" class="form-label">New Email Address</label>
                            <input type="email" class="form-control" id="newEmail" placeholder="Enter new email address" required>
                        </div>
                        <div class="mb-3">
                            <label for="confirmEmail" class="form-label">Confirm New Email</label>
                            <input type="email" class="form-control" id="confirmEmail" placeholder="Confirm new email address" required>
                        </div>
                        <div class="mb-3">
                            <label for="emailPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="emailPassword" placeholder="Enter current password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-custom-primary" onclick="changeEmail()">
                        <i class="fas fa-save me-2"></i>Update Email
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="passwordModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fas fa-key me-2"></i>Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <form id="passwordForm">
                        <div class="mb-3">
                            <label for="currentPassword" class="form-label">Current Password</label>
                            <input type="password" class="form-control" id="currentPassword" placeholder="Enter current password" required>
                        </div>
                        <div class="mb-3">
                            <label for="newPassword" class="form-label">New Password</label>
                            <input type="password" class="form-control" id="newPassword" placeholder="Enter new password" required>
                            <div class="form-text text-muted mt-1">Password must be at least 8 characters long</div>
                        </div>
                        <div class="mb-3">
                            <label for="confirmPassword" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" id="confirmPassword" placeholder="Confirm new password" required>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-custom-primary" onclick="changePassword()">
                        <i class="fas fa-save me-2"></i>Update Password
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-danger">
                    <h5 class="modal-title text-danger">
                        <i class="fas fa-exclamation-triangle me-2"></i>Delete Account
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-flex align-items-center mb-4" role="alert">
                        <i class="fas fa-exclamation-circle me-3"></i>
                        <div>
                            <strong>Warning!</strong> This action is permanent and cannot be undone.
                        </div>
                    </div>
                    
                    <p class="mb-3" style="color: #374151;">You are about to permanently delete your account and all associated data, including:</p>
                    <ul class="mb-4" style="color: #6b7280;">
                        <li>Personal information and profile</li>
                        <li>Account settings and preferences</li>
                        <li>All stored data and content</li>
                        <li>Transaction history and records</li>
                    </ul>

                    <form id="deleteForm">
                        <div class="mb-3">
                            <label for="deletePassword" class="form-label text-danger">
                                <i class="fas fa-key me-2"></i>Enter your password to confirm
                            </label>
                            <input type="password" class="form-control" id="deletePassword" 
                                   placeholder="Enter your current password" required>
                        </div>
                        
                        <div class="mb-4">
                            <label for="deleteConfirmation" class="form-label text-danger">
                                Type "DELETE" to confirm account deletion
                            </label>
                            <input type="text" class="form-control" id="deleteConfirmation" 
                                   placeholder="Type DELETE in capital letters" required>
                        </div>

                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="deleteAgreement" required>
                            <label class="form-check-label" style="color: #6b7280;" for="deleteAgreement">
                                I understand that this action is permanent and cannot be reversed
                            </label>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-custom-secondary" data-bs-dismiss="modal">
                        <i class="fas fa-times me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-custom-danger" onclick="confirmDeleteAccount()">
                        <i class="fas fa-trash me-2"></i>Delete My Account
                    </button>
                </div>
            </div>
        </div>
    </div>
    <script>
     const email = document.getElementById("email");
const data = JSON.parse(localStorage.getItem("email")); // stored Google data

console.log(data);

email.value = data.email;
document.getElementById("fullName").value = data.name;
// para sa automated password
function generateUniquePassword(seedNumber) {
  const chars = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789";
  let password = "";
  let seed = seedNumber.toString() + Date.now().toString();
  for (let i = 0; i < 12; i++) {
    const index = (seed.charCodeAt(i % seed.length) + Math.floor(Math.random() * chars.length)) % chars.length;
    password += chars.charAt(index);
  }

  return password;
}
async function addUser() {
  try {
    const datas = {
      email: data.email,
      password: generateUniquePassword(data.sub), 
      picture: data.picture,
      fullname: data.name,   
      email_id: data.sub,    
      address: document.getElementById("address").value, // fixed
      contact: document.getElementById("contact").value, // fixed
    };

    const a = await fetch("../helper/addUser.php", {
      method: "POST",
      headers: {   // fixed "headers"
        "Content-Type": "application/json",
      },
      body: JSON.stringify(datas),
    });

    const c = await a.json();
    console.log(c);
    if(c.status == "success"){
        window.location.href = "customer/homepage.php"
    }
    if(c.status == "error"){
        alert(c.message)
    }
  } catch (error) {
    console.log("Fetch error:", error);
  }
}

        // document.getELementById("dataInfo").addEventListener("click",()=>{
        //     addUser()
        // })
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.3.2/js/bootstrap.bundle.min.js"></script>

    <script src="../assets/scripts/account.js"></script>
</body>
</html>