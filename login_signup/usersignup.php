<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Register - Enduro Legends Rental</title>
  <link rel="stylesheet" href="../css/register.css">
</head>
<body>
  <div class="container">
    <div class="form-box">
      <h1>Create Account</h1>

      <?php if (isset($_GET['error'])): ?>
        <p class="error"><?= htmlspecialchars($_GET['error']); ?></p>
      <?php endif; ?>

      <form action="usersignupquery.php" method="POST">
        <label>Full Name</label>
        <input type="text" name="name" required>

        <label>Phone Number</label>
        <input type="text" name="phone_number" required>

        <label>Email</label>
        <input type="email" name="email" required>

        <label>Password</label>
        <input type="password" name="password" required>

        <input type="hidden" name="user_type" value="customer">

        <button type="submit">Register</button>
      </form>

      <p class="switch">Already have an account? <a href="userlogin.php">Login</a></p>
    </div>
  </div>
</body>
</html>
