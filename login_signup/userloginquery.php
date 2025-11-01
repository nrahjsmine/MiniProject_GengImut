<?php
session_start();
include 'db_conn.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // ✅ Password minimum 6 aksara
    if (strlen($password) < 6) {
        header("Location: userlogin.php?error=Password must be at least 6 characters");
        exit();
    }

    // Cari user ikut email
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $row = $result->fetch_assoc();

        // Semak password hash
        if (password_verify($password, $row['password'])) {

            // Set session
            $_SESSION['user_id'] = $row['id'];
            $_SESSION['name'] = $row['name'];
            $_SESSION['user_type'] = $row['user_type'];

            // Redirect ikut user type
            if ($row['user_type'] === 'customer') {
                header("Location: ../customer/customerhome.php");
                exit();
            } elseif ($row['user_type'] === 'admin') {
                header("Location: ../admin/admin_homepage.php");
                exit();
            } else {
                header("Location: userlogin.php?error=User type not recognized");
                exit();
            }

        } else {
            header("Location: userlogin.php?error=Incorrect password");
            exit();
        }
    } else {
        header("Location: userlogin.php?error=Account not found");
        exit();
    }
} else {
    header("Location: userlogin.php");
    exit();
}
?>
