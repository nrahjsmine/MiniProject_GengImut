<?php
session_start();
include '../login_signup/db_conn.php';

// ✅ Pastikan hanya admin boleh akses
if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] != 'admin') {
    header("Location: ../login_signup/userlogin.php");
    exit();
}

// ✅ Pastikan ada data dihantar
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['rent_id'])) {
    $rent_id = intval($_POST['rent_id']);

    // 1️⃣ Ambil data sewa dari rentals
    $sql = "SELECT * FROM rentals WHERE rent_id = $rent_id";
    $result = mysqli_query($conn, $sql);

    if ($row = mysqli_fetch_assoc($result)) {
        // 2️⃣ Masukkan ke rental_history (soft delete)
        $insert = "
            INSERT INTO rental_history 
            (rent_id, id, vehicle_id, start_date, end_date, total_amount, payment_status, status)
            VALUES (
                {$row['rent_id']},
                {$row['id']},
                {$row['vehicle_id']},
                '{$row['start_date']}',
                '{$row['end_date']}',
                '{$row['total_amount']}',
                '{$row['payment_status']}',
                '{$row['status']}'
            )
        ";
        mysqli_query($conn, $insert);

        // 3️⃣ Delete dari rentals
        $delete = "DELETE FROM rentals WHERE rent_id = $rent_id";
        mysqli_query($conn, $delete);
    }

    // ✅ Redirect balik ke bookings tab
    header("Location: admin_homepage.php?tab=bookings");
    exit();
} else {
    echo "❌ Invalid request.";
}
?>
