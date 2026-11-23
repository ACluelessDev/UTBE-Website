<?php
require __DIR__ . '/bootstrap.php';

requirePost();
checkCsrf();

$user = currentUser($pdo);

if (!$user) {
    redirectWith('error', 'You need to log in first.');
}

if ($user['role'] !== 'developer') {
    redirectWith('error', 'Only developers can delete posts.');
}

$postId = (int) field('post_id');

$stmt = $pdo->prepare('SELECT image_path FROM posts WHERE id = ?');
$stmt->execute([$postId]);
$post = $stmt->fetch();

if (!$post) {
    redirectWith('error', 'That post no longer exists.');
}

$stmt = $pdo->prepare('DELETE FROM posts WHERE id = ?');
$stmt->execute([$postId]);

if ($post['image_path']) {
    $imageFile = __DIR__ . '/../uploads/' . basename($post['image_path']);

    if (is_file($imageFile)) {
        unlink($imageFile);
    }
}

redirectWith('success', 'Post deleted.');