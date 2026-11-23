<?php
require __DIR__ . '/bootstrap.php';

requirePost();
checkCsrf();

$username = trim(field('username'));
$password = field('password');

$stmt = $pdo->prepare('SELECT id, username, password_hash FROM users WHERE username = ?');
$stmt->execute([$username]);
$user = $stmt->fetch();

$valid = $user && password_verify($password, $user['password_hash']);

if (!$valid) {
    redirectWith('error', 'Incorrect credentials. Check your username or password.');
}

session_regenerate_id(true);
$_SESSION['user_id'] = (int) $user['id'];

redirectWith('success', 'Welcome back, ' . $user['username'] . '!');