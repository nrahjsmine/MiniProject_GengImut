<?php
include '../login_signup/db_conn.php';
session_start();

// ✅ Redirect kalau bukan customer
if (!isset($_SESSION['name']) || $_SESSION['user_type'] != 'customer') {
  header("Location: ../login_signup/userlogin.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Customer Dashboard - Enduro Legends Rental</title>
  <link rel="stylesheet" href="../css/customerhome.css">
  <style>
    .search-section {
      text-align: center;
      margin: 30px 0;
    }
    .search-section form {
      display: inline-block;
      background: #111;
      padding: 20px;
      border-radius: 10px;
      box-shadow: 0 0 10px rgba(225, 6, 0, 0.4);
    }
    .search-section label {
      margin-right: 10px;
    }
    .search-section input {
      padding: 8px;
      border-radius: 5px;
      border: none;
      margin-right: 10px;
    }
    .search-section button {
      padding: 8px 15px;
      background: #e10600;
      color: #fff;
      border: none;
      border-radius: 5px;
      cursor: pointer;
    }
    .search-section button:hover {
      background: #b50500;
    }
  </style>
</head>
<body>

<!-- ================= NAVBAR ================= -->
<header>
  <nav class="navbar">
    <div class="logo">
      <h1>Enduro Legends Rental</h1>
    </div>
    <ul class="nav-links">
      <li><a href="../Index/index.html">Home</a></li>
      <li><a href="../customer/mybookings.php">My Bookings</a></li>
      <li><a href="#">Profile</a></li>
    </ul>
    <a href="../login_signup/logout.php" class="btn-logout">Logout</a>
  </nav>
</header>

<!-- ================= DASHBOARD HEADER ================= -->
<section class="dashboard-header">
  <h2>Welcome, <?= htmlspecialchars($_SESSION['name']); ?>!</h2>
  <p>Explore our collection and book your next ride below.</p>
</section>

<!-- ================= TARIKH PILIHAN ================= -->
<section class="search-section">
  <form method="GET" action="">
    <label>Rent Date:</label>
    <input type="date" name="rent_date" required
      value="<?= isset($_GET['rent_date']) ? htmlspecialchars($_GET['rent_date']) : '' ?>">

    <label>Return Date:</label>
    <input type="date" name="return_date" required
      value="<?= isset($_GET['return_date']) ? htmlspecialchars($_GET['return_date']) : '' ?>">

    <button type="submit">Check Availability</button>
  </form>
</section>

<!-- ================= VEHICLE LIST ================= -->
<section class="vehicle-section">
  <div class="vehicle-grid">
    <?php
      // --- SEMAK JIKA ADA TARIKH DIPILIH ---
      if (isset($_GET['rent_date']) && isset($_GET['return_date'])) {
        $rent_date = mysqli_real_escape_string($conn, $_GET['rent_date']);
        $return_date = mysqli_real_escape_string($conn, $_GET['return_date']);

        // ✅ Tunjuk hanya kereta yang BELUM ditempah pada tarikh itu
        $query = "
          SELECT * FROM vehicles v
          WHERE v.vehicle_id NOT IN (
            SELECT vehicle_id FROM rentals 
            WHERE status NOT IN ('rejected','cancelled','completed')
            AND (
                (start_date <= '$rent_date' AND end_date >= '$rent_date') OR 
                (start_date <= '$return_date' AND end_date >= '$return_date') OR
                ('$rent_date' <= start_date AND '$return_date' >= end_date)
            )
          )
          ORDER BY v.model ASC
        ";
      } else {
        // Kalau belum pilih tarikh — tunjuk semua kereta
        $query = "SELECT * FROM vehicles ORDER BY model ASC";
      }

      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $imagePath = "../Images/" . htmlspecialchars($row['image']);
          $model = htmlspecialchars($row['model']);
          $plate = htmlspecialchars($row['plate_no']);
          $price = htmlspecialchars($row['price']);
          $vehicle_id = $row['vehicle_id'];

          // Bila tarikh dah dipilih, masukkan dalam URL
          $rent_link = isset($rent_date) && isset($return_date)
            ? "book.php?vehicle_id={$vehicle_id}&rent_date={$rent_date}&return_date={$return_date}"
            : "book.php?vehicle_id={$vehicle_id}";

          echo "
          <div class='vehicle-card'>
            <img src='$imagePath' alt='$model' onerror=\"this.src='../Images/default.jpg'\"> 
            <h3>$model</h3>
            <div class='vehicle-info'>
              <p><strong>Plate No:</strong> $plate</p>
              <p><strong>Price:</strong> RM $price / day</p>
              <p class='status available'><strong>Status:</strong> Available</p>
            </div>
            <a href='$rent_link' class='book-btn'>Book Now</a>
          </div>";
        }
      } else {
        echo "<p class='no-vehicles'>No vehicles available for the selected dates.</p>";
      }
    ?>
  </div>
</section>

<!-- ================= FOOTER ================= -->
<footer>
  <p>© 2025 Enduro Legends Rental. All rights reserved.</p>
</footer>

</body>
</html>
