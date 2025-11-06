<?php
session_start();
include '../login_signup/db_conn.php';


// AUTO UPDATE BOOKING STATUS

$today = date('Y-m-d');

// 1️⃣ Tukar kereta jadi available bila end_date dah lepas dan booking approved
// 🚫 Tapi jangan ubah kalau kereta tu tengah maintenance
$updateVehicles = "
    UPDATE vehicles 
    SET availability = 'available' 
    WHERE vehicle_id IN (
        SELECT vehicle_id FROM rentals 
        WHERE end_date < '$today' 
        AND status = 'approved'
    )
    AND availability != 'maintenance'
";
mysqli_query($conn, $updateVehicles);



// 2️⃣ Tukar status booking jadi completed bila dah lepas tarikh
$updateRentals = "
    UPDATE rentals 
    SET status = 'completed'
    WHERE end_date < '$today' 
    AND status = 'approved'
";
mysqli_query($conn, $updateRentals);


// ✅ Ensure admin is logged in
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login_signup/userlogin.php");
    exit();
}

// ✅ Handle vehicle status update
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    if (isset($_POST['vehicle_update'])) {
        $vehicle_id = intval($_POST['vehicle_id']);
        $new_status = $_POST['new_status'];
        $update_vehicle = "UPDATE vehicles SET availability='$new_status' WHERE vehicle_id=$vehicle_id";
        mysqli_query($conn, $update_vehicle);
        header("Location: admin_homepage.php?tab=vehicles");
        exit();
    }
}

// ✅ Determine active tab
$active_tab = isset($_GET['tab']) ? $_GET['tab'] : 'bookings';

// ✅ Get all bookings
$bookings_query = "
SELECT rentals.*, users.name AS username, vehicles.model
FROM rentals
JOIN users ON rentals.id = users.id
JOIN vehicles ON rentals.vehicle_id = vehicles.vehicle_id
ORDER BY rentals.start_date DESC
";
$bookings_result = mysqli_query($conn, $bookings_query);
if (!$bookings_result) {
    die('❌ SQL Error: ' . mysqli_error($conn));
}

// ✅ Get all vehicles
$vehicles_query = "SELECT * FROM vehicles ORDER BY model ASC";
$vehicles_result = mysqli_query($conn, $vehicles_query);

// ✅ Get rental history
$history_query = "SELECT * FROM rental_history ORDER BY deleted_at DESC";
$history_result = mysqli_query($conn, $history_query);

function bookingClass($status) {
    return 'status-' . strtolower($status);
}
function vehicleClass($status) {
    return 'vehicle-' . strtolower($status);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0" />
<title>Admin Dashboard - Enduro Legends Rental</title>
<link rel="stylesheet" href="../css/style.css" />
<link rel="stylesheet" href="../css/adminhome.css">
</head>
<body>

<!-- ✅ Navbar -->
<header>
  <nav class="navbar">
    <div class="logo">
      <h1>Enduro Legends Rental</h1>
    </div>
    <ul class="nav-links">
      <li><a href="admin_profile.php">Profile</a></li>
      <li><a href="?tab=vehicles">Vehicles</a></li>
    </ul>
    <a href="../login_signup/logout.php" class="btn-login">Logout</a>
  </nav>
</header>

<!-- ✅ Admin Dashboard -->
<div class="admin-container">
    <h2>Admin Dashboard</h2>

    <div class="tabs">
        <a href="?tab=history" class="tab-link <?php echo ($active_tab == 'history') ? 'active' : ''; ?>">History</a>
        <a href="?tab=bookings" class="tab-link <?php echo ($active_tab == 'bookings') ? 'active' : ''; ?>">All Bookings</a>
        <a href="?tab=vehicles" class="tab-link <?php echo ($active_tab == 'vehicles') ? 'active' : ''; ?>">Vehicle Management</a>
    </div>

    <?php if ($active_tab == 'bookings'): ?>
    <!-- 📋 All Bookings Table -->
    <table>
    <tr>
        <th>No</th>
        <th>Booking ID</th>
        <th>Customer</th>
        <th>Vehicle</th>
        <th>Start Date</th>
        <th>End Date</th>
        <th>Total (RM)</th>
        <th>Payment Proof</th>
        <th>Booking Status</th>
        <th>Action</th>
    </tr>

    <?php 
    $count = 1; // mula dari 1
    while($row = mysqli_fetch_assoc($bookings_result)): 
    ?>
    <tr>
        <td><?php echo $count++; ?></td> <!-- nombor urutan -->
        <td><?php echo $row['rent_id']; ?></td>
        <td><?php echo htmlspecialchars($row['username']); ?></td>
        <td><?php echo htmlspecialchars($row['model']); ?></td>
        <td><?php echo $row['start_date']; ?></td>
        <td><?php echo $row['end_date']; ?></td>
        <td><?php echo number_format($row['total_amount'], 2); ?></td>
        ...


            <!-- 📷 Payment proof -->
            <td>
                <?php if ($row['payment_proof']): ?>
                    <a href="../uploads/<?php echo $row['payment_proof']; ?>" target="_blank" style="color:violet;">View</a>
                <?php else: ?>
                    <span style="color:#777;">No proof</span>
                <?php endif; ?>
            </td>

            <!-- 💳 Payment status -->
            

            <!-- 📋 Booking status -->
            <td><?php echo ucfirst($row['status']); ?></td>

            <!-- 🛠️ Actions -->
            <td style="white-space: nowrap;">
                <!-- ✅ Approve/Reject payment -->
                <form action="admin_approve.php" method="POST" style="display:inline;">
                    <input type="hidden" name="rent_id" value="<?php echo $row['rent_id']; ?>">

                    <?php if(strtolower($row['payment_status']) == 'pending'): ?>
                        <button type="submit" name="action" value="approve" class="approve">Approve</button>
                        <button type="submit" name="action" value="reject" class="reject">Reject</button>
                    <?php elseif(strtolower($row['payment_status']) == 'approved'): ?>
                        <span style="color:limegreen;">Approved</span>
                    <?php elseif(strtolower($row['payment_status']) == 'rejected'): ?>
                        <span style="color:red;">Rejected</span>
                    <?php endif; ?>
                </form>

                <!-- 🗑️ Delete booking -->
                <form action="admin_delete.php" method="POST" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this booking?');">
                    <input type="hidden" name="rent_id" value="<?php echo $row['rent_id']; ?>">
                    <button type="submit" class="delete" style="margin-left:5px;">Delete</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>


    <?php elseif ($active_tab == 'vehicles'): ?>
    <!-- 🚗 Vehicle Management Table -->
    <table>
        <tr>
            <th>No.</th>
            <th>Car</th>
            <th>Plate No</th>
            <th>Price (RM)</th>
            <th>Status</th>
            <th>Edit</th>
        </tr>
        <?php 
        $count = 1;
        while($v = mysqli_fetch_assoc($vehicles_result)): ?>
        <tr>
            <td><?php echo $count++; ?></td>
            <td><?php echo htmlspecialchars($v['model']); ?></td>
            <td><?php echo htmlspecialchars($v['plate_no']); ?></td>
            <td><?php echo number_format($v['price'], 2); ?></td>
            <td class="<?php echo vehicleClass($v['availability']); ?>">
                <?php echo ucfirst($v['availability']); ?>
            </td>
            <td>
                <form method="POST">
                    <input type="hidden" name="vehicle_id" value="<?php echo $v['vehicle_id']; ?>">
                    <select name="new_status">
                        <option value="available" <?php if($v['availability']=='available') echo 'selected'; ?>>Available</option>
                        <option value="maintenance" <?php if($v['availability']=='maintenance') echo 'selected'; ?>>Maintenance</option>
                        
                    </select>
                    <button type="submit" name="vehicle_update" class="vehicle-update">Update</button>
                </form>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>


    <?php elseif ($active_tab == 'history'): ?>
    <!-- 🕓 Rental History (Soft Deleted Bookings) -->
    <h3>Deleted Booking History</h3>
    <table>
        <tr>
            <th>History ID</th>
            <th>Booking ID</th>
            <th>User ID</th>
            <th>Vehicle ID</th>
            <th>Start Date</th>
            <th>End Date</th>
            <th>Total (RM)</th>
            <th>Status</th>
            <th>Deleted At</th>
        </tr>

        <?php while($h = mysqli_fetch_assoc($history_result)): ?>
        <tr>
            <td><?php echo $h['history_id']; ?></td>
            <td><?php echo $h['rent_id']; ?></td>
            <td><?php echo $h['id']; ?></td>
            <td><?php echo $h['vehicle_id']; ?></td>
            <td><?php echo $h['start_date']; ?></td>
            <td><?php echo $h['end_date']; ?></td>
            <td><?php echo number_format($h['total_amount'], 2); ?></td>
            
            <td><?php echo ucfirst($h['status']); ?></td>
            <td><?php echo $h['deleted_at']; ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php endif; ?>
</div>

<footer>
  <p>© 2025 Enduro Legends Rental. All rights reserved.</p>
</footer>

</body>
</html>
