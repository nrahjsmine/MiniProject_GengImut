<?php
session_start();
include '../login_signup/db_conn.php';

// Pastikan user login sebagai customer
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'customer') {
    header("Location: ../login_signup/userlogin.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Ambil semua booking milik customer ni
$query = "
SELECT rentals.*, vehicles.model
FROM rentals
JOIN vehicles ON rentals.vehicle_id = vehicles.vehicle_id
WHERE rentals.id = '$user_id'
ORDER BY rentals.start_date DESC
";

$result = mysqli_query($conn, $query);
if (!$result) {
    die('❌ SQL Error: ' . mysqli_error($conn));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>My Bookings - Enduro Legends Rental</title>
<link rel="stylesheet" href="../css/style.css">
<link rel="stylesheet" href="../css/mybookings.css">
</head>
<body>

<!-- ================= NAVBAR (SAMA MACAM PAGE LAIN) ================= -->
<header>
  <nav class="navbar">
    <div class="logo">
      <h1>Enduro Legends Rental</h1>
    </div>
    <ul class="nav-links">
      <li><a href="customerhome.php">Home</a></li>
      <li><a href="mybookings.php" class="active">My Bookings</a></li>
    </ul>
    <a href="../login_signup/logout.php" class="btn-login">Logout</a>
  </nav>
</header>

<!-- ================= BOOKING SECTION ================= -->
<section class="booking-section">
<div class="container">
    <h2>My Bookings</h2>

    <?php if (mysqli_num_rows($result) > 0) { ?>
    <table>
        <tr>
            <th>Booking ID</th>
            <th>Vehicle</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total Amount (RM)</th>
            <th>Payment Status</th>
            <th>Booking Status</th>
        </tr>

        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
        <tr>
            <td><?php echo $row['rent_id']; ?></td>
            <td><?php echo htmlspecialchars($row['model']); ?></td>
            <td><?php echo $row['start_date']; ?></td>
            <td><?php echo $row['end_date']; ?></td>
            <td><?php echo number_format($row['total_amount'], 2); ?></td>

            <!-- Payment Status -->
            <td>
                <span class="status <?php echo strtolower($row['payment_status']); ?>">
                    <?php echo ucfirst($row['payment_status']); ?>
                </span>
            </td>

            <!-- Booking Status -->
            <td>
                <span class="status <?php echo strtolower($row['status']); ?>">
                    <?php echo ucfirst($row['status']); ?>
                </span>
            </td>
        </tr>
        <?php } ?>
    </table>
    <?php } else { ?>
        <p class="no-booking">You have no bookings yet.</p>
    <?php } ?>
</div>
</section>

<footer>
  <p>© 2025 Enduro Legends Rental. All rights reserved.</p>
</footer>

</body>
</html>
