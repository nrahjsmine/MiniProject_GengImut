<?php
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $name = trim($_POST['name']);
  $phone = trim($_POST['phone_number']);
  $email = trim($_POST['email']);
  $password_raw = $_POST['password'];
  $user_type = $_POST['user_type'];

  // ✅ Password minimum 6 aksara
  if (strlen($password_raw) < 6) {
      header("Location: usersignup.php?error=Password must be at least 6 characters");
      exit();
  }

  $password = password_hash($password_raw, PASSWORD_DEFAULT);

  // Check if email already exists
  $check = $conn->prepare("SELECT * FROM users WHERE email = ?");
  $check->bind_param("s", $email);
  $check->execute();
  $result = $check->get_result();

  if ($result->num_rows > 0) {
    header("Location: usersignup.php?error=Email already registered");
    exit();
  }

  $stmt = $conn->prepare("INSERT INTO users (name, phone_number, email, password, user_type) VALUES (?, ?, ?, ?, ?)");
  $stmt->bind_param("sssss", $name, $phone, $email, $password, $user_type);

  if ($stmt->execute()) {
    header("Location: userlogin.php?success=Account created, please login");
  } else {
    header("Location: usersignup.php?error=Registration failed");
  }
  exit();
}
?>
