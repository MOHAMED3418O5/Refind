<?php

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function csrfToken(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrfField(): string
{
    return '<input type="hidden" name="csrf_token" value="' . htmlspecialchars(csrfToken()) . '">';
}

function csrfCheck(array $post = null): void
{
    $post = $post ?? $_POST;
    if (empty($post['csrf_token']) || !hash_equals($_SESSION['csrf_token'] ?? '', $post['csrf_token'])) {
        http_response_code(419);
        die("انتهت صلاحية الجلسة، حاول مرة أخرى.");
    }
}

function loggedIn(): bool
{
    return !empty($_SESSION['user_id']);
}

function currentUser(PDO $conn): ?array
{
    if (!loggedIn()) {
        return null;
    }
    $stmt = $conn->prepare("SELECT id, name, email, phone, created_at FROM users WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch();
    return $user ?: null;
}

function requireLogin(): void
{
    if (!loggedIn()) {
        header("Location: ../auth/Log-in.php");
        exit;
    }
}

function requireGuest(): void
{
    if (loggedIn()) {
        header("Location: ../Home.php");
        exit;
    }
}

function flash(string $key, ?string $value = null): ?string
{
    if ($value !== null) {
        $_SESSION['flash'][$key] = $value;
        return null;
    }
    $message = $_SESSION['flash'][$key] ?? null;
    unset($_SESSION['flash'][$key]);
    return $message;
}
?>