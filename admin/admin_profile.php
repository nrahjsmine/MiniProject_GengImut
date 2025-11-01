<?php
session_start();
include '../login_signup/db_conn.php';

// ✅ Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login_signup/userlogin.php");
    exit();
}

// ✅ Get admin data
$admin_id = $_SESSION['user_id'];
$query = "SELECT * FROM users WHERE id = '$admin_id'";
$result = mysqli_query($conn, $query);

if (!$result || mysqli_num_rows($result) == 0) {
    die("❌ Admin data not found.");
}

$admin = mysqli_fetch_assoc($result);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Profile - Enduro Legends Rental</title>
<link rel="stylesheet" href="../css/adminhome.css">
<style>
body {
  background-color: #0b0b0b;
  color: #f2f2f2;
  font-family: Arial, Helvetica, sans-serif;
  margin: 0;
  padding: 0;
}

.profile-container {
  width: 400px;
  margin: 120px auto;
  background-color: #1a1a1a;
  border-radius: 15px;
  box-shadow: 0 0 20px rgba(225,6,0,0.4);
  padding: 40px 30px;
}

h2 {
  text-align: center;
  color: #e10600;
  text-transform: uppercase;
  letter-spacing: 1px;
  margin-bottom: 25px;
  text-shadow: 0 0 10px rgba(225,6,0,0.6);
}

.profile-info {
  display: flex;
  flex-direction: column;
  gap: 20px;
}

.info-item {
  background: #111;
  padding: 12px 15px;
  border-radius: 8px;
  border-left: 4px solid #e10600;
  transition: 0.3s ease;
}

.info-item:hover {
  background: #1f1f1f;
}

.info-label {
  font-size: 14px;
  color: #e10600;
  text-transform: uppercase;
  letter-spacing: 1px;
}

.info-value {
  font-size: 16px;
  font-weight: bold;
  color: #f2f2f2;
}

footer {
  text-align: center;
  padding: 20px;
  color: #777;
  border-top: 1px solid #333;
  position: fixed;
  bottom: 0;
  width: 100%;
  background: #111;
}
</style>
</head>
<body>

<div class="profile-container">
  <h2>Admin Profile</h2>

  <div class="profile-info">
    <div class="info-item">
      <div class="info-label">Admin ID</div>
      <div class="info-value"><?php echo htmlspecialchars($admin['id']); ?></div>
    </div>

    <div class="info-item">
      <div class="info-label">Name</div>
      <div class="info-value"><?php echo htmlspecialchars($admin['name']); ?></div>
    </div>

    <div class="info-item">
      <div class="info-label">Email</div>
      <div class="info-value"><?php echo htmlspecialchars($admin['email']); ?></div>
    </div>
  </div>
</div>

<footer>
  <p>© 2025 Enduro Legends Rental. All rights reserved.</p>
</footer>

</body>
</html>
