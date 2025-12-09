document.getElementById("signinButton").addEventListener("click", function (e) {
  e.preventDefault();
  const login = document.getElementById("login").value;
  const password = document.getElementById("password").value;
  if (login === "customer" && password === "customer") {
    window.location.href = "../../html/customer/homepage.html";
  } else if (login === "staff" && password === "staff") {
    window.location.href = "../../html/staff/dashboard.html";
  } else if (login === "owner" && password === "owner") {
    window.location.href = "../../html/owner/dashboard.php";
  } else {
    alert("invalid");
  }
});
