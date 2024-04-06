<?php
require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load environment variables
$dotenv = Dotenv::createImmutable(__DIR__ . '/../', '.env');
$dotenv->load();

class Connection
{
  // Database Handler
  private $pdo;
  // Initialize database connection
  public function __construct()
  {
    $dbHost = $_ENV['DB_HOST'];
    $dbName = $_ENV['DB_NAME'];
    $dbUser = $_ENV['DB_USER'];
    $dbPass = $_ENV['DB_PASS'] ?? '';
    $dbPort = $_ENV['DB_PORT'] ?? 3306;

    $dsn = "mysql:host=$dbHost;dbname=$dbName;port=$dbPort";
    $options = [
      PDO::ATTR_PERSISTENT => true,
      PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ];

    try {
      // Create a new PDO instance
      $this->pdo = new PDO($dsn, $dbUser, $dbPass, $options);
    } catch (PDOException $e) {
      throw new Exception("Failed to connect to database: " . $e->getMessage());
    }
  }

  public function getPDO()
  {
    // Return PDO instance
    return $this->pdo;
  }

  public function closeConnection()
  {
    // Close the database connection
    $this->pdo = null;
  }
}
