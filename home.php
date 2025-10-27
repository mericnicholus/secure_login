<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: index.html");
    exit;
}
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Home</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <div class="container">
    <h2>Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</h2>
    <p>You are logged in successfully.</p>
    <a href="logout.php">Logout</a>
  </div>
</body>
</html>
