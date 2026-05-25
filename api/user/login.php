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
$user = null;

foreach ($users as $u) {
    if ($u['username'] === $username && $u['password'] === $password) {
        $found = true;
        $user = $u;
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['user_username'] = $user['username'];
        $_SESSION['user_role'] = $user['role'];
        break;
    }
}

if ($found) {
    // 读取用户详细信息
    $userInfoPath = dirname(dirname(__DIR__)) . '/data/users/' . $user['id'] . '.json';
    $userInfo = [];
    if (file_exists($userInfoPath)) {
        $userInfo = json_decode(file_get_contents($userInfoPath), true) ?: [];
    }

    // 合并用户信息
    $fullUser = [
        'id' => $user['id'],
        'username' => $user['username'],
        'nickname' => $userInfo['nickname'] ?? '',
        'avatar' => $userInfo['avatar'] ?? '',
        'address' => $userInfo['address'] ?? ['name' => '', 'phone' => '', 'detail' => ''],
        'role' => $user['role']
    ];

    echo json_encode(['success' => true, 'message' => '登录成功', 'user' => $fullUser]);
} else {
    echo json_encode(['success' => false, 'message' => '用户名或密码错误']);
}
