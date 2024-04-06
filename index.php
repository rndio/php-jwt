<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Auth.php';

try {
  $session = Auth::getCurrentSession();
} catch (Exception $exception) {
  Auth::logout();
  header('Location: /login.php');
  exit(0);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Index</title>
</head>

<body>
  <h1>Welcome, <?= $session['name'] ?></h1>
  <a href="/logout.php">Logout</a>
</body>

</html>