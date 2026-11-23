<?php
require __DIR__ . '/bootstrap.php';

requirePost();
checkCsrf();

$username = trim(field('username'));
$password = field('password');
$confirm = field('confirm_password');

if (!preg_match('/^[A-Za-z0-9_]{3,20}$/', $username)) {
    redirectWith('error', 'Username must be 3 to 20 characters: letters, numbers, and underscores only.');
}

if (strlen($password) < 8) {
    redirectWith('error', 'Password must be at least 8 characters.');
}

if (strlen($password) > 72) {
    redirectWith('error', 'Password must be 72 characters or fewer.');
}

if ($password !== $confirm) {
    redirectWith('error', 'Passwords do not match.');
}

$hash = password_hash($password, PASSWORD_DEFAULT);

try {
    $stmt = $pdo->prepare('INSERT INTO users (username, password_hash) VALUES (?, ?)');
    $stmt->execute([$username, $hash]);
} catch (PDOException $e) {
    if ($e->getCode() === '23000') {
        redirectWith('error', 'That username is already taken.');
    }
    throw $e;
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $pdo->lastInsertId();

redirectWith('success', 'Contract made. Good to have you, ' . $username . '.');