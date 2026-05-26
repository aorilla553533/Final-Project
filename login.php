<?php
session_start();

$db_host = getenv('MYSQLHOST');
$db_user = getenv('MYSQLUSER');
$db_pass = getenv('MYSQLPASSWORD');
$db_name = getenv('MYSQLDATABASE');
$db_port = getenv('MYSQLPORT') ?: '3306';

try {
    $pdo = new PDO(
        "mysql:host=$db_host;port=$db_port;dbname=$db_name;charset=utf8mb4",
        $db_user,
        $db_pass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"
        ]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

$error = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = "Please fill in all fields.";
    } else {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && $password === $user['PASSWORD']) {
            $_SESSION['user_id']  = $user['id'];
            $_SESSION['username'] = $user['username'];
            header("Location: index.html");
            exit;
        } else {
            $error = "Invalid username or password.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
  <title>Pet Shop — Login</title>
  <style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body {
      min-height: 100vh;
      background-color: #f0eeea;
      display: flex;
      align-items: center;
      justify-content: center;
      font-family: 'Segoe UI', Arial, sans-serif;
      position: relative;
      overflow: hidden;
    }
    .paws  { position: absolute; top: 16px; left: 20px; }
    .bubbles { position: absolute; top: 0; right: 0; }
    .card {
      background: #f0eeea;
      border-radius: 18px;
      padding: 2.8rem 3rem;
      width: 360px;
      position: relative;
      z-index: 2;
      text-align: center;
    }
    .logo {
      font-size: 56px;
      font-weight: 900;
      font-family: 'Arial Rounded MT Bold', 'Trebuchet MS', sans-serif;
      letter-spacing: 3px;
      color: #cbc9c1;
      text-shadow: -2px -2px 0 #ffffff, 2px 2px 0 #aaa9a0, 3px 4px 8px rgba(0,0,0,0.18);
      line-height: 1.05;
      margin-bottom: 1.8rem;
      user-select: none;
    }
    .error-msg {
      background: #fff0f0;
      border: 1px solid #f5c6c6;
      color: #c0392b;
      font-size: 13px;
      padding: 8px 12px;
      border-radius: 7px;
      margin-bottom: 1rem;
      text-align: left;
    }
    .field { text-align: left; margin-bottom: 1rem; }
    .field label {
      display: block;
      font-size: 13.5px;
      color: #999;
      margin-bottom: 5px;
    }
    .field input {
      width: 100%;
      padding: 10px 14px;
      border: 1px solid #ddd;
      border-radius: 7px;
      font-size: 14px;
      background: #f7f6f2;
      color: #333;
      outline: none;
      transition: border-color 0.2s;
    }
    .field input:focus { border-color: #F5A623; }
    .forgot { text-align: right; margin-top: -0.5rem; margin-bottom: 1.2rem; }
    .forgot a { font-size: 12px; color: #F5A623; text-decoration: none; }
    .forgot a:hover { text-decoration: underline; }
    .btn-login {
      width: 100%;
      padding: 12px;
      background: #F5A623;
      color: #fff;
      border: none;
      border-radius: 9px;
      font-size: 15px;
      font-weight: 700;
      letter-spacing: 2px;
      cursor: pointer;
      transition: background 0.2s, transform 0.1s;
    }
    .btn-login:hover  { background: #e09510; }
    .btn-login:active { transform: scale(0.98); }
    .register-text { margin-top: 1.3rem; font-size: 12.5px; color: #bbb; }
    .register-text a { color: #F5A623; font-weight: 600; text-decoration: none; }
    .register-text a:hover { text-decoration: underline; }
  </style>
</head>
<body>
  <svg class="paws" width="90" height="90" viewBox="0 0 90 90" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <ellipse cx="20" cy="20" rx="8" ry="10" fill="#F5A623" opacity="0.85" transform="rotate(-20 20 20)"/>
    <ellipse cx="40" cy="13" rx="6" ry="8" fill="#F5A623" opacity="0.7" transform="rotate(10 40 13)"/>
    <ellipse cx="10" cy="38" rx="6" ry="8" fill="#F5A623" opacity="0.7" transform="rotate(-30 10 38)"/>
    <ellipse cx="34" cy="34" rx="15" ry="12" fill="#F5A623" opacity="0.85" transform="rotate(-15 34 34)"/>
    <ellipse cx="62" cy="62" rx="8" ry="10" fill="#F5A623" opacity="0.55" transform="rotate(-20 62 62)"/>
    <ellipse cx="78" cy="52" rx="5" ry="7" fill="#F5A623" opacity="0.4" transform="rotate(10 78 52)"/>
    <ellipse cx="54" cy="78" rx="5" ry="7" fill="#F5A623" opacity="0.4" transform="rotate(-30 54 78)"/>
    <ellipse cx="70" cy="72" rx="12" ry="9" fill="#F5A623" opacity="0.5" transform="rotate(-15 70 72)"/>
  </svg>
  <svg class="bubbles" width="150" height="150" viewBox="0 0 150 150" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
    <circle cx="110" cy="35" r="46" fill="#F5A623" opacity="0.9"/>
    <circle cx="88" cy="74" r="26" fill="#F5A623" opacity="0.65"/>
    <circle cx="130" cy="80" r="18" fill="#F5A623" opacity="0.45"/>
  </svg>
  <div class="card">
    <div class="logo">PET<br>SHOP</div>
    <?php if ($error): ?>
      <div class="error-msg"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="POST" action="">
      <div class="field">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" autocomplete="username"
               value="<?= htmlspecialchars($_POST['username'] ?? '') ?>" />
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" autocomplete="current-password" />
      </div>
      <div class="forgot">
        <a href="forgot_password.php">Forgot Password?</a>
      </div>
      <button class="btn-login" type="submit">LOGIN</button>
    </form>
    <p class="register-text">Don't have an account? <a href="register.php">Register</a></p>
  </div>
</body>
</html>
