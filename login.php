<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Auth.php';

$message = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = $_POST['username'];
  $password = $_POST['password'];

  if (Auth::login($username, $password)) {
    header('Location: index.php');
  } else {
    $message = "Invalid username or password";
  }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Login</title>
</head>

<body>

  <h1>Login</h1>
  <?php if ($message) : ?>
    <h5><?= $message ?></h5>
  <?php endif; ?>
  <form action="" method="POST">
    <input type="text" name="username" id="username" placeholder="Username">
    <input type="password" name="password" id="password" placeholder="Password">
    <button type="submit">Login</button>
  </form>

</body>

</html>