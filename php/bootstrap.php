<?php
session_set_cookie_params([
    'httponly' => true,
    'samesite' => 'Lax'
]);
session_start();
date_default_timezone_set('Asia/Manila');

$dbHost = 'localhost';
$dbName = 'utbe_community';
$dbUser = 'quantacat_app';
$dbPass = 'acluelessdev';

try {
    $pdo = new PDO(
        'mysql:host=' . $dbHost . ';dbname=' . $dbName . ';charset=utf8mb4',
        $dbUser,
        $dbPass,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false
        ]
    );
    $pdo->exec("SET time_zone = '+08:00'");
} catch (PDOException $e) {
      http_response_code(500);
      exit($e->getMessage());
}

function e($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8');
}

function field($name)
{
    if (isset($_POST[$name]) && is_string($_POST[$name])) {
        return $_POST[$name];
    }
    return '';
}

function csrfToken()
{
    if (!isset($_SESSION['csrf'])) {
        $_SESSION['csrf'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf'];
}

function checkCsrf()
{
    $real = isset($_SESSION['csrf']) ? $_SESSION['csrf'] : '';
    $valid = $real !== '' && hash_equals($real, field('csrf'));

    if (!$valid) {
        http_response_code(403);
        exit('Invalid request. Go back, refresh the page, and try again.');
    }
}

function requirePost()
{
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../html/community.php');
        exit;
    }
}

function redirectWith($type, $message)
{
    $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    header('Location: ../html/community.php');
    exit;
}

function currentUser($pdo)
{
    if (!isset($_SESSION['user_id'])) {
        return null;
    }

    $stmt = $pdo->prepare('SELECT id, username, role FROM users WHERE id = ?');
    $stmt->execute([$_SESSION['user_id']]);
    $user = $stmt->fetch();

    return $user ? $user : null;
}