<?php
session_start();
include '../login_signup/db_conn.php';

// Pastikan hanya admin boleh guna
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login_signup/userlogin.php");
    exit();
}

// Pastikan data dihantar
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['action'], $_POST['rent_id'])) {
    $rent_id = intval($_POST['rent_id']);
    $action = strtolower($_POST['action']);

    if ($action === 'approve') {

        // ✔ Update status kepada approved
        $sql = "UPDATE rentals SET status='approved' WHERE rent_id=$rent_id";

        if (mysqli_query($conn, $sql)) {

            // ✔ Dapatkan vehicle_id untuk ubah availability (jika perlu)
            $vsql = "SELECT vehicle_id FROM rentals WHERE rent_id=$rent_id";
            $vres = mysqli_query($conn, $vsql);
            if ($vrow = mysqli_fetch_assoc($vres)) {
                $vehicle_id = $vrow['vehicle_id'];

                // Optional: Kalau nak tukar availability
                // mysqli_query($conn, "UPDATE vehicles SET availability='booked' WHERE vehicle_id=$vehicle_id");
            }

        } else {
            die("❌ SQL Error (approve): " . mysqli_error($conn));
        }

    } elseif ($action === 'reject') {

        // ✔ Ubah status kepada rejected
        $sql = "UPDATE rentals SET status='rejected' WHERE rent_id=$rent_id";
        
        if (!mysqli_query($conn, $sql)) {
            die("❌ SQL Error (reject): " . mysqli_error($conn));
        }
    }

    header("Location: admin_homepage.php?tab=bookings");
    exit();

} else {
    echo "❌ Invalid request.";
}
?>
