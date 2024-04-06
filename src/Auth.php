<?php

require_once __DIR__ . '/Connection.php';
require_once __DIR__ . '/../vendor/autoload.php';

use Firebase\JWT\JWT;
use Firebase\JWT\Key;

// Struct for session
class Session
{
  public string $user_id;
  public string $name;
  public string $username;
  public string $exp;

  public function __construct(string $user_id, string $name, string $username, string $exp)
  {
    $this->user_id = $user_id;
    $this->name = $name;
    $this->username = $username;
    $this->exp = $exp;
  }
}

// Auth Class
class Auth
{
  // Secret key for JWT
  public const SECRET_KEY = "RAHASIA_DECK_JANGAN_KASIH_TAU_ORANG_LAIN";
  public static function login(string $username, string $password): bool
  {
    // Get user from database
    $pdo = (new Connection())->getPDO();
    $query = "SELECT * FROM users WHERE username = ?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if user exists
    if ($user) {
      // Verify password
      if (!password_verify($password, $user['password'])) {
        return false;
      }

      // Generate JWT
      $jwt = self::generateJWT([
        "user_id" => $user['id'],
        "username" => $user['username'],
        "name" => $user['name'],
      ]);

      // Set HTTP only cookie
      setcookie("X-RND-SESSION", $jwt, 0, "", "", false, true);

      // User found
      return true;
    }

    // User not found
    $pdo = null;
    return false;
  }

  public static function logout(): void
  {
    setcookie("X-RND-SESSION", "LOGOUT", time() - 3600, "", "", false, true);
  }

  public static function getCurrentSession(): array
  {
    if ($_COOKIE['X-RND-SESSION']) {
      $jwt = $_COOKIE['X-RND-SESSION'];
      try {
        $payload = JWT::decode($jwt, new Key(self::SECRET_KEY, 'HS256'));
        return (array) $payload;
      } catch (Exception $exception) {
        throw new Exception("User is not login");
      }
    } else {
      throw new Exception("User is not login");
    }
  }

  private static function generateJWT(array $payload): string
  {
    $issuedAt = time();
    $expirationTime = $issuedAt + 3600;

    $payload = array_merge($payload, [
      "iat" => $issuedAt,
      "iss" => "RND-Dev",
      "exp" => $expirationTime,
    ]);
    return JWT::encode($payload, self::SECRET_KEY, 'HS256');
  }
}
