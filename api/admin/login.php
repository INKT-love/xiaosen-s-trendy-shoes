<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../data.php';

$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($username) || empty($password)) {
    echo json_encode(['success' => false, 'message' => '请输入用户名和密码']);
    exit;
}

$users = readJson('users');
$found = false;

foreach ($users as $user) {
    if ($user['username'] === $username && $user['password'] === $password) {
        $found = true;
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['admin_username'] = $user['username'];
        $_SESSION['admin_role'] = $user['role'];
        break;
    }
}

if ($found) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => '用户名或密码错误']);
}
