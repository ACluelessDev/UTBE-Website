<?php
require __DIR__ . '/../php/bootstrap.php';

$user = currentUser($pdo);
$flash = isset($_SESSION['flash']) ? $_SESSION['flash'] : null;
unset($_SESSION['flash']);
$csrf = csrfToken();
$posts = [];

if ($user) {
    $posts = $pdo->query(
        'SELECT p.id, p.title, p.caption, p.image_path, p.is_developer_post, p.created_at, u.username
         FROM posts p
         JOIN users u ON u.id = p.user_id
         ORDER BY p.created_at DESC, p.id DESC
         LIMIT 100'
    )->fetchAll();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>QuantaCat | Community</title>
    <link rel="stylesheet" href="../css/community.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
</head>
<body>
<header class="top-bar">
    <nav class="navbar">
        <div class="quantacat-dropdown" tabindex="0">
            <label class="quantacat-dropdown-label">
                QUANTACAT <br>STUDIO
                <i class="dropdown-arrow">▼</i>
            </label>
            <div class="quantacat-dropdown-content">
                <ul>
                    <li><a href="about.html">About</a></li>
                    <li><a href="contact.html">Contact</a></li>
                    <li><a href="community.php">Community</a></li>
                    <li><a href="Other Game Ideas.html">Other Game Ideas</a></li>
                </ul>
            </div>
        </div>
        <a class="navbar-return" href="../index.html">RETURN</a>
    </nav>
</header>
<main class="community-main">
    <h1 class="community-title">COMMUNITY</h1>
    <h2 class="community-subtext">Forums and developer updates.</h2>

    <?php if ($flash): ?>
        <div class="flash flash-<?= e($flash['type']) ?>"><?= e($flash['message']) ?></div>
    <?php endif; ?>

    <?php if (!$user): ?>
        <section class="auth-grid">
            <form class="panel" action="../php/login.php" method="post">
                <h2>LOGIN</h2>
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <label for="login-username">Username</label>
                <input type="text" id="login-username" name="username" maxlength="20" required>
                <label for="login-password">Password</label>
                <input type="password" id="login-password" name="password" required>
                <button class="button" type="submit">LOGIN</button>
            </form>
            <form class="panel" action="../php/register.php" method="post">
                <h2>REGISTER</h2>
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <label for="register-username">Username</label>
                <input type="text" id="register-username" name="username" minlength="3" maxlength="20" required>
                <label for="register-password">Password</label>
                <input type="password" id="register-password" name="password" minlength="8" maxlength="72" required>
                <label for="register-confirm">Confirm password</label>
                <input type="password" id="register-confirm" name="confirm_password" minlength="8" maxlength="72" required>
                <p class="panel-note">Username: 3 to 20 letters, numbers, or underscores. Password: at least 8 characters.</p>
                <button class="button" type="submit">CREATE ACCOUNT</button>
            </form>
        </section>
    <?php else: ?>
        <section class="userbar">
            <span>
                Logged in as <strong><?= e($user['username']) ?></strong>
                <?php if ($user['role'] === 'developer'): ?>
                    <span class="role-badge">DEVELOPER</span>
                <?php endif; ?>
            </span>
            <form action="../php/logout.php" method="post">
                <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                <button class="button button-small" type="submit">LOGOUT</button>
            </form>
        </section>

        <form class="panel post-form" action="../php/create_post.php" method="post" enctype="multipart/form-data">
            <h2>NEW POST</h2>
            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
            <label for="post-title">Title</label>
            <input type="text" id="post-title" name="title" maxlength="100" required>
            <label for="post-caption">Caption</label>
            <textarea id="post-caption" name="caption" maxlength="500" required></textarea>
            <label for="post-image">Image (optional, 2 MB max)</label>
            <input type="file" id="post-image" name="image" accept="image/jpeg,image/png,image/gif,image/webp">
            <?php if ($user['role'] === 'developer'): ?>
                <label class="checkbox-row">
                    <input type="checkbox" name="as_developer" value="1" checked>
                    Post as a DEVELOPER update
                </label>
            <?php endif; ?>
            <button class="button" type="submit">POST</button>
        </form>

        <h2 class="feed-heading">LATEST POSTS</h2>

        <?php if (count($posts) === 0): ?>
            <p class="empty-feed">No posts yet. Be the first to post something.</p>
        <?php endif; ?>

        <?php foreach ($posts as $post): ?>
            <article class="post<?= $post['is_developer_post'] ? ' post-developer' : '' ?>">
                <?php if ($post['is_developer_post']): ?>
                    <div class="post-developer-header">DEVELOPER</div>
                <?php endif; ?>
                <div class="post-body">
                    <h3 class="post-title"><?= e($post['title']) ?></h3>
                    <p class="post-caption"><?= e($post['caption']) ?></p>
                    <?php if ($post['image_path']): ?>
                        <img class="post-image" src="../<?= e($post['image_path']) ?>" alt="Image attached to the post <?= e($post['title']) ?>" loading="lazy">
                    <?php endif; ?>
                </div>
                <div class="post-meta">
                    <span>Posted by <?= e($post['username']) ?> on <?= e(date('M j, Y g:i A', strtotime($post['created_at']))) ?></span>
                    <?php if ($user['role'] === 'developer'): ?>
                        <form action="../php/delete_post.php" method="post" onsubmit="return confirm('Delete this post?');">
                            <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                            <input type="hidden" name="post_id" value="<?= (int) $post['id'] ?>">
                            <button class="button button-delete" type="submit">DELETE</button>
                        </form>
                    <?php endif; ?>
                </div>
            </article>
        <?php endforeach; ?>
            <details class="panel delete-account">
                <summary>DELETE MY ACCOUNT</summary>
                <form action="../php/delete_account.php" method="post" onsubmit="return confirm('This permanently deletes your account and all of your posts. Continue?');">
                    <input type="hidden" name="csrf" value="<?= e($csrf) ?>">
                    <p class="panel-note">This permanently deletes your account and every post you made. Enter your password to confirm.</p>
                    <label for="delete-password">Password</label>
                    <input type="password" id="delete-password" name="password" required>
                    <button class="button button-delete" type="submit">DELETE ACCOUNT</button>
                </form>
            </details>
    <?php endif; ?>
</main>
<footer>
    <p>© 2026 QuantaCat Studio. All Rights Reserved. Website source code is licensed under the MIT license.</p>
</footer>
</body>
</html>