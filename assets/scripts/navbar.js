const profileBtn = document.getElementById('profileBtn');
const profileDropdown = document.getElementById('profileDropdown');

profileBtn.addEventListener('click', function(e) {
    e.stopPropagation();
    profileDropdown.classList.toggle('show');
    console.log("clicked");
});

document.addEventListener('click', function(e) {
    if (!profileBtn.contains(e.target) && !profileDropdown.contains(e.target)) {
        profileDropdown.classList.remove('show');
    }
});