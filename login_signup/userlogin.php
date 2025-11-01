<?php
session_start();

// ✅ Redirect kalau user sudah login
if (isset($_SESSION['user_id'])) {
    if ($_SESSION['user_type'] == 'customer') {
        header("Location: ../customer/customerhome.php");
        exit();
    } elseif ($_SESSION['user_type'] == 'admin') {
        header("Location: ../admin/admin_homepage.php");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login - Enduro Legends Rental</title>
  <link rel="stylesheet" href="../css/login.css">
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h1>Login</h1>

      <!-- Papar error / success message -->
      <?php if (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']); ?></p>
      <?php elseif (isset($_GET['success'])): ?>
        <p class="success"><?= htmlspecialchars($_GET['success']); ?></p>
      <?php endif; ?>

      <!-- Form login -->
      <form action="userloginquery.php" method="POST">
        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <button type="submit">Login</button>
      </form>

      <p class="switch">Don’t have an account? <a href="usersignup.php">Register</a></p>
    </div>
  </div>
</body>
</html>
