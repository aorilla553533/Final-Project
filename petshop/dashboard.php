<?php
session_start();

// Redirect to login if not authenticated
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8"/>
  <title>Pet Shop — Dashboard</title>
  <style>
    body { font-family: 'Segoe UI', sans-serif; background: #f0eeea; display: flex; align-items: center; justify-content: center; min-height: 100vh; }
    .box { background: #fff; border-radius: 14px; padding: 2.5rem 3rem; text-align: center; box-shadow: 0 2px 16px rgba(0,0,0,0.07); }
    h1 { font-size: 24px; color: #333; margin-bottom: 0.5rem; }
    p  { color: #888; font-size: 14px; margin-bottom: 1.5rem; }
    a  { background: #F5A623; color: #fff; padding: 10px 24px; border-radius: 8px; text-decoration: none; font-weight: 600; }
    a:hover { background: #e09510; }
  </style>
</head>
<body>
  <div class="box">
    <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>! 🐾</h1>
    <p>You are now logged in to Pet Shop.</p>
    <a href="logout.php">Logout</a>
  </div>
</body>
</html>