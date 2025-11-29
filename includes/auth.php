<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/db.php';

function is_logged_in(): bool {
    return isset($_SESSION['user_id']);
}

function require_login(): void {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

/**
 * Get details of the logged-in user.
 * NOTE: Name is get_logged_in_user (not get_current_user) to avoid clash with PHP's built-in function.
 */
function get_logged_in_user() {
    global $pdo;
    if (!is_logged_in()) return null;

    $stmt = $pdo->prepare("
        SELECT user_id, first_name, last_name, username, rank, department
        FROM USER_ACCOUNT
        WHERE user_id = ?
    ");
    $stmt->execute([$_SESSION['user_id']]);
    return $stmt->fetch();
}
