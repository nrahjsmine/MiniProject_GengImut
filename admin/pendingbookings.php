<?php
session_start();
include '../login_signup/db_conn.php';

// Pastikan hanya admin boleh masuk
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login_signup/userlogin.php");
    exit();
}

// Proses tindakan admin
if (isset($_GET['action']) && isset($_GET['rent_id'])) {
    $rent_id = intval($_GET['rent_id']);
    $action = $_GET['action'];

    switch ($action) {
        case 'approve_payment':
            $query = "UPDATE rentals SET payment_status = 'Paid' WHERE rent_id = $rent_id";
            break;
        case 'reject_payment':
            $query = "UPDATE rentals SET payment_status = 'Rejected' WHERE rent_id = $rent_id";
            break;
        case 'approve_booking':
            $query = "UPDATE rentals SET booking_status = 'Approved' WHERE rent_id = $rent_id";
            break;
        case 'reject_booking':
            $query = "UPDATE rentals SET booking_status = 'Rejected' WHERE rent_id = $rent_id";
            break;
    }

    mysqli_query($conn, $query);
    header("Location: pendingbookings.php");
    exit();
}

// Ambil semua data booking
$query = "
SELECT rentals.*, users.username, vehicles.model
FROM rentals
JOIN users ON rentals.id = users.id
JOIN vehicles ON rentals.vehicle_id = vehicles.vehicle_id
ORDER BY rentals.rent_date DESC
";
$result = mysqli_query($conn, $query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Pending Bookings - Admin Panel</title>
<link rel="stylesheet" href="../css/adminhome.css">
<style>
body {
  font-family: Arial, sans-serif;
  padding: 20px;
  background: #f4f4f4;
}
table {
  width: 100%;
  border-collapse: collapse;
  background: #fff;
  margin-top: 20px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
}
th, td {
  border: 1px solid #ddd;
  padding: 10px;
  text-align: center;
}
th {
  background-color: #333;
  color: white;
}
img {
  width: 100px;
  border-radius: 6px;
}
button {
  padding: 6px 10px;
  margin: 2px;
  border: none;
  border-radius: 5px;
  cursor: pointer;
}
.approve { background-color: #4CAF50; color: white; }
.reject { background-color: #f44336; color: white; }
.pending { background-color: #ffa500; color: white; }
</style>
</head>
<body>

<h2>Booking Management (Admin)</h2>

<table>
<tr>
  <th>Booking ID</th>
  <th>Customer</th>
  <th>Vehicle</th>
  <th>Rent Date</th>
  <th>Return Date</th>
  <th>Payment Status</th>
  <th>Payment Proof</th>
  <th>Booking Status</th>
  <th>Action</th>
</tr>

<?php while ($row = mysqli_fetch_assoc($result)) { ?>
<tr>
  <td><?php echo $row['rent_id']; ?></td>
  <td><?php echo $row['username']; ?></td>
  <td><?php echo $row['model']; ?></td>
  <td><?php echo $row['rent_date']; ?></td>
  <td><?php echo $row['return_date']; ?></td>

  <!-- Payment status -->
  <td>
    <?php
      if ($row['payment_status'] == 'Pending') echo "<span style='color:orange;'>Pending</span>";
      elseif ($row['payment_status'] == 'Paid') echo "<span style='color:green;'>Paid</span>";
      else echo "<span style='color:red;'>Rejected</span>";
    ?>
  </td>

  <!-- Payment proof -->
  <td>
    <?php if (!empty($row['payment_proof'])) { ?>
      <a href="../uploads/<?php echo $row['payment_proof']; ?>" target="_blank">
        <img src="../uploads/<?php echo $row['payment_proof']; ?>" alt="proof">
      </a>
    <?php } else { echo "No proof"; } ?>
  </td>

  <!-- Booking status -->
  <td>
    <?php
      if ($row['booking_status'] == 'Pending') echo "<span style='color:orange;'>Pending</span>";
      elseif ($row['booking_status'] == 'Approved') echo "<span style='color:green;'>Approved</span>";
      else echo "<span style='color:red;'>Rejected</span>";
    ?>
  </td>

  <!-- Action buttons -->
  <td>
    <?php if ($row['payment_status'] == 'Pending') { ?>
      <a href="?action=approve_payment&rent_id=<?php echo $row['rent_id']; ?>"><button class="approve">Approve Payment</button></a>
      <a href="?action=reject_payment&rent_id=<?php echo $row['rent_id']; ?>"><button class="reject">Reject Payment</button></a>
    <?php } elseif ($row['payment_status'] == 'Paid' && $row['booking_status'] == 'Pending') { ?>
      <a href="?action=approve_booking&rent_id=<?php echo $row['rent_id']; ?>"><button class="approve">Approve Booking</button></a>
      <a href="?action=reject_booking&rent_id=<?php echo $row['rent_id']; ?>"><button class="reject">Reject Booking</button></a>
    <?php } else { echo "-"; } ?>
  </td>
</tr>
<?php } ?>

</table>
</body>
</html>
