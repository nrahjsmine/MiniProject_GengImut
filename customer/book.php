<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include '../login_signup/db_conn.php';

// ✅ Pastikan user login sebagai customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'customer') {
    header("Location: ../login_signup/userlogin.php");
    exit();
}

// ✅ Pastikan ada vehicle_id
if (!isset($_GET['vehicle_id'])) {
    echo "Invalid request!";
    exit();
}

$user_id = $_SESSION['user_id'];
$vehicle_id = intval($_GET['vehicle_id']);

// ✅ Ambil maklumat kereta
$query = "SELECT * FROM vehicles WHERE vehicle_id = $vehicle_id";
$result = mysqli_query($conn, $query);
$vehicle = mysqli_fetch_assoc($result);

if (!$vehicle) {
    echo "Vehicle not found!";
    exit();
}

// Bila form dihantar
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $fullname = mysqli_real_escape_string($conn, $_POST['fullname']);
    $start_date = mysqli_real_escape_string($conn, $_POST['rent_date']);
    $end_date = mysqli_real_escape_string($conn, $_POST['return_date']);

    // ✅ 1️⃣ Semak tarikh overlap
    $check_sql = "
        SELECT * FROM rentals
        WHERE vehicle_id = ?
        AND status NOT IN ('rejected','cancelled','completed')
        AND (
            (start_date <= ? AND end_date >= ?) OR 
            (start_date <= ? AND end_date >= ?) OR
            (? <= start_date AND ? >= end_date)
        )
    ";
    $stmt = $conn->prepare($check_sql);
    $stmt->bind_param("issssss", $vehicle_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date);
    $stmt->execute();
    $check_result = $stmt->get_result();

    if ($check_result->num_rows > 0) {
        echo "<script>alert('🚫 This vehicle is already booked for the selected dates. Please choose another date.'); window.history.back();</script>";
        exit();
    }

    // ✅ 2️⃣ Upload bukti bayaran
    $payment_proof = null;
    if (isset($_FILES['payment_proof']) && $_FILES['payment_proof']['error'] == 0) {
        $target_dir = "../uploads/";
        if (!file_exists($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        $file_name = time() . "_" . basename($_FILES["payment_proof"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["payment_proof"]["tmp_name"], $target_file)) {
            $payment_proof = $file_name;
        } else {
            echo "<script>alert('❌ Gagal muat naik bukti bayaran');</script>";
        }
    }

    // ✅ 3️⃣ Kira jumlah harga sewa
    $daily_price = $vehicle['price'];
    $days = (strtotime($end_date) - strtotime($start_date)) / (60 * 60 * 24);
    if ($days < 1) $days = 1;
    $total_amount = $daily_price * $days;

    // ✅ 4️⃣ Simpan booking ke DB
    $insert = "INSERT INTO rentals 
        (id, vehicle_id, total_amount, start_date, end_date, status, payment_status, payment_proof, booking_status)
        VALUES 
        ('$user_id', '$vehicle_id', '$total_amount', '$start_date', '$end_date', 'Pending', 'Pending', '$payment_proof', 'Pending')";
    
    if (mysqli_query($conn, $insert)) {
        echo "<script>alert('✅ Booking sent! Please wait for payment confirmation.'); window.location.href='mybookings.php';</script>";
        exit();
    } else {
        echo "<pre>❌ SQL Error: " . mysqli_error($conn) . "</pre>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Book Vehicle</title>
<link rel="stylesheet" href="../css/book.css">
<style>
.qr-box {
  background: #111;
  border: 2px solid #e10600;
  border-radius: 15px;
  padding: 20px;
  text-align: center;
  margin-bottom: 25px;
  box-shadow: 0 0 15px rgba(225, 6, 0, 0.4);
}
.qr-box img {
  width: 180px;
  border-radius: 10px;
  margin-bottom: 10px;
}
.qr-box h3 {
  color: #e10600;
  margin-bottom: 8px;
}
.qr-box p {
  color: #ccc;
  font-size: 0.95rem;
}
</style>
</head>
<body>

<header>
  <div class="navbar">
    <div class="logo"><h1>ENDURO LEGENDS RENTAL</h1></div>
    <ul class="nav-links">
      <li><a href="../customer/customerhome.php">Home</a></li>
      <li><a href="mybookings.php">My Bookings</a></li>
      <li><a href="../login_signup/logout.php">Logout</a></li>
    </ul>
  </div>
</header>

<section class="booking-section">
  <div class="container">
    <h2>Book <?php echo htmlspecialchars($vehicle['model']); ?></h2>
    <p>Please scan and pay before submitting your booking form.</p>

    <!-- QR PAYMENT SECTION -->
    <div class="qr-box">
      <h3>Step 1: Scan to Pay RM500 Deposit</h3>
      <img src="../Images/qrpayment.jpg" alt="QR Payment">
      <p>After making payment, upload your screenshot below.</p>
    </div>

    <!-- BOOKING FORM -->
    <form method="POST" enctype="multipart/form-data" class="booking-form">
      <label>Full Name:</label>
      <input type="text" name="fullname" required>

      <label>Rent Date:</label>
      <input type="date" name="rent_date" required 
             value="<?= isset($_GET['rent_date']) ? htmlspecialchars($_GET['rent_date']) : '' ?>">

      <label>Return Date:</label>
      <input type="date" name="return_date" required
             value="<?= isset($_GET['return_date']) ? htmlspecialchars($_GET['return_date']) : '' ?>">

      <label>Upload Payment Proof (QR Screenshot):</label>
      <input type="file" name="payment_proof" accept="image/*" required>

      <button type="submit">Submit Booking</button>
    </form>
  </div>
</section>

</body>
</html>
