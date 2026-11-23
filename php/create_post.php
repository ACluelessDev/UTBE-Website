<?php
require __DIR__ . '/bootstrap.php';

requirePost();

if (empty($_POST) && isset($_SERVER['CONTENT_LENGTH']) && (int) $_SERVER['CONTENT_LENGTH'] > 0) {
    redirectWith('error', 'That upload is too large. Keep images under 2 MB.');
}

checkCsrf();

$user = currentUser($pdo);

if (!$user) {
    redirectWith('error', 'You need to log in to post.');
}

$title = trim(field('title'));
$caption = trim(field('caption'));

if ($title === '') {
    redirectWith('error', 'Your post needs a title.');
}

if (mb_strlen($title) > 100) {
    redirectWith('error', 'Title must be 100 characters or fewer.');
}

if ($caption === '') {
    redirectWith('error', 'Your post needs a caption.');
}

if (mb_strlen($caption) > 500) {
    redirectWith('error', 'Caption must be 500 characters or fewer.');
}

$isDeveloperPost = 0;

if ($user['role'] === 'developer' && field('as_developer') === '1') {
    $isDeveloperPost = 1;
}

$imagePath = null;

if (isset($_FILES['image']) && $_FILES['image']['error'] !== UPLOAD_ERR_NO_FILE) {
    $file = $_FILES['image'];

    if ($file['error'] !== UPLOAD_ERR_OK) {
        redirectWith('error', 'The image failed to upload. It may be too large (2 MB maximum).');
    }

    if ($file['size'] > 2 * 1024 * 1024) {
        redirectWith('error', 'Image must be 2 MB or smaller.');
    }

    $allowed = [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp'
    ];

    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($file['tmp_name']);

    if (!isset($allowed[$mime])) {
        redirectWith('error', 'Only JPG, PNG, GIF, or WEBP images are allowed.');
    }

    $folder = __DIR__ . '/../uploads/';

    if (!is_dir($folder)) {
        mkdir($folder, 0755, true);
    }

    $filename = bin2hex(random_bytes(16)) . '.' . $allowed[$mime];

    if (!move_uploaded_file($file['tmp_name'], $folder . $filename)) {
        redirectWith('error', 'Could not save the image. Please try again.');
    }

    $imagePath = 'uploads/' . $filename;
}

$stmt = $pdo->prepare(
    'INSERT INTO posts (user_id, title, caption, image_path, is_developer_post) VALUES (?, ?, ?, ?, ?)'
);
$stmt->execute([$user['id'], $title, $caption, $imagePath, $isDeveloperPost]);

redirectWith('success', 'Your post is live.');