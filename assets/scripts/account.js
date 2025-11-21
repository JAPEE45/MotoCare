function goBack() {
  document.querySelector(".main-container").style.transform =
    "translateX(-20px)";
  document.querySelector(".main-container").style.opacity = "0.8";

  window.location.href = "./customer/homepage.php";
}

function changeEmail() {
  const newEmail = document.getElementById("newEmail").value;
  const confirmEmail = document.getElementById("confirmEmail").value;
  const password = document.getElementById("emailPassword").value;

  if (!newEmail || !confirmEmail || !password) {
    alert("Please fill in all fields.");
    return;
  }

  if (!newEmail.includes("@")) {
    alert("Please enter a valid email address.");
    return;
  }

  if (newEmail !== confirmEmail) {
    alert("Email addresses do not match.");
    return;
  }

  alert(
    "Email change request submitted! Please check your inbox for verification."
  );
  bootstrap.Modal.getInstance(document.getElementById("emailModal")).hide();

  document.getElementById("emailForm").reset();
}

function changePassword() {
  const currentPassword = document.getElementById("currentPassword").value;
  const newPassword = document.getElementById("newPassword").value;
  const confirmPassword = document.getElementById("confirmPassword").value;

  if (!currentPassword || !newPassword || !confirmPassword) {
    alert("Please fill in all fields.");
    return;
  }

  if (newPassword.length < 8) {
    alert("Password must be at least 8 characters long.");
    return;
  }

  if (newPassword !== confirmPassword) {
    alert("Passwords do not match.");
    return;
  }

  alert("Password changed successfully!");
  bootstrap.Modal.getInstance(document.getElementById("passwordModal")).hide();

  document.getElementById("passwordForm").reset();
}

function confirmDeleteAccount() {
  const password = document.getElementById("deletePassword").value;
  const confirmation = document.getElementById("deleteConfirmation").value;
  const agreement = document.getElementById("deleteAgreement").checked;

  if (!password) {
    alert("Please enter your password to confirm account deletion.");
    document.getElementById("deletePassword").focus();
    return;
  }

  if (confirmation !== "DELETE") {
    alert('Please type "DELETE" exactly as shown to confirm.');
    document.getElementById("deleteConfirmation").focus();
    return;
  }

  if (!agreement) {
    alert("Please confirm that you understand this action is permanent.");
    return;
  }

  const deleteBtn = document.querySelector("#deleteModal .btn-custom-danger");
  const originalText = deleteBtn.innerHTML;

  deleteBtn.innerHTML =
    '<i class="fas fa-spinner fa-spin me-2"></i>Processing...';
  deleteBtn.disabled = true;

  setTimeout(() => {
    bootstrap.Modal.getInstance(document.getElementById("deleteModal")).hide();

    setTimeout(() => {
      window.location.href = "./index.php";
    }, 300);

    deleteBtn.innerHTML = originalText;
    deleteBtn.disabled = false;
  }, 2000);
}

function deleteAccount() {
  const confirmation = confirm(
    "Are you absolutely sure you want to delete your account?\n\nThis action cannot be undone and all your data will be permanently deleted."
  );

  if (confirmation) {
    const finalConfirmation = prompt(
      'Type "DELETE" in capital letters to confirm:'
    );
    if (finalConfirmation === "DELETE") {
      alert(
        "Account deletion initiated. You will receive a confirmation email shortly."
      );
    } else {
      alert("Account deletion cancelled.");
    }
  }
}

document
  .getElementById("personalForm")
  .addEventListener("submit", function (e) {
    e.preventDefault();

    const saveBtn = e.target.querySelector('button[type="submit"]');
    if (saveBtn) {
      saveBtn.style.color = "#ffffff";
      const originalText = saveBtn.innerHTML;
      saveBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
      saveBtn.disabled = true;
      setTimeout(() => {
        saveBtn.innerHTML =
          '<i class="fas fa-check me-2" style="color: #ffffff;"></i>Saved!';
        saveBtn.style.background =
          "linear-gradient(135deg, #10b981 0%, #059669 100%)";
      }, 2000);
    }

      setTimeout(() => {
        saveBtn.innerHTML = originalText;
        saveBtn.style.background = "";
        saveBtn.disabled = false;
      }, 2000);
    }, 1000);
  });

document.querySelectorAll('[data-bs-toggle="tab"]').forEach((tab) => {
  tab.addEventListener("shown.bs.tab", function (e) {
    const activePane = document.querySelector(
      e.target.getAttribute("data-bs-target")
    );
    activePane.classList.add("fade-in");

    setTimeout(() => {
      activePane.classList.remove("fade-in");
    }, 500);
  });
});

document
  .getElementById("emailModal")
  .addEventListener("hidden.bs.modal", function () {
    document.getElementById("emailForm").reset();
  });

document
  .getElementById("passwordModal")
  .addEventListener("hidden.bs.modal", function () {
    document.getElementById("passwordForm").reset();
  });

document
  .getElementById("deleteModal")
  .addEventListener("hidden.bs.modal", function () {
    document.getElementById("deleteForm").reset();
  });
