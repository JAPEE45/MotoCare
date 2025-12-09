# CHANGES!!!

Main Page

  - The main page is now `homepage.html` (previously `index.html`), located at `html/customer/homepage.html`.
  - The header displays **Sign In**, **Sign Up**, and **Juan Dela Cruz**. Since there is no backend yet, all are visible by default.
  - Clicking **Find Location** redirects to `map.html`, which shows the locations of all three shops.
  - Clicking a pinned shop location shows the shop name and a **Book Service** button.
  - If a user clicks **Book Service** without logging in, they will be redirected to the login page (not yet implemented). If logged in, the booking modal will appear.
  - If the user attempts to book but their profile information is incomplete, they will be redirected to `account.html` to complete their details (not yet implemented).
  - If the user books with complete information, they will be redirected to `booking-status.html`.

Staff Side
  - To log in, click "Sign In" and use the static credentials:
    * Username: `staff`
    * Password: `staff`
  - Once logged in, the user is redirected to the dashboard showing all appointment details.
  - To view all bookings (with options to cancel, reschedule, or confirm), go to the "Bookings" tab in the header.


NOTE:
- The "Admin side" (User Management and Analytics) is not yet implemented.
- This is pure FRONTEND.
