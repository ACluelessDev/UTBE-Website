<?php
require __DIR__ . '/bootstrap.php';

requirePost();
checkCsrf();

$user = currentUser($pdo);

if (!$user) {
    redirectWith('error', 'You need to log in first.');
}

$stmt = $pdo->prepare('SELECT password_hash FROM users WHERE id = ?');
$stmt->execute([$user['id']]);
$row = $stmt->fetch();

$valid = $row && password_verify(field('password'), $row['password_hash']);

if (!$valid) {
    redirectWith('error', 'Incorrect password. Your account was not deleted.');
}

$stmt = $pdo->prepare('SELECT image_path FROM posts WHERE user_id = ? AND image_path IS NOT NULL');
$stmt->execute([$user['id']]);
$images = $stmt->fetchAll();

$stmt = $pdo->prepare('DELETE FROM users WHERE id = ?');
$stmt->execute([$user['id']]);

foreach ($images as $image) {
    $imageFile = __DIR__ . '/../uploads/' . basename($image['image_path']);

    if (is_file($imageFile)) {
        unlink($imageFile);
    }
}

unset($_SESSION['user_id']);
session_regenerate_id(true);

redirectWith('success', 'Your account and all of your posts have been deleted.');