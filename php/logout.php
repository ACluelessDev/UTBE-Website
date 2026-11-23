<?php
require __DIR__ . '/bootstrap.php';

requirePost();
checkCsrf();

unset($_SESSION['user_id']);
session_regenerate_id(true);

redirectWith('success', 'You have beem logged out.');