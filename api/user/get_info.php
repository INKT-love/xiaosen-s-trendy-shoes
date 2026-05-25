<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../data.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '请先登录']);
    exit;
}

$userId = $_SESSION['user_id'];
$username = $_SESSION['user_username'] ?? '';

// 读取用户基本信息
$users = readJson('users');
$currentUser = null;
foreach ($users as $u) {
    if ($u['id'] == $userId) {
        $currentUser = $u;
        break;
    }
}

if (!$currentUser) {
    echo json_encode(['success' => false, 'message' => '用户不存在']);
    exit;
}

// 读取用户详细信息
$userInfoPath = dirname(dirname(__DIR__)) . '/data/users/' . $userId . '.json';
$userInfo = [];
if (file_exists($userInfoPath)) {
    $userInfo = json_decode(file_get_contents($userInfoPath), true) ?: [];
}

// 返回完整的用户信息
echo json_encode([
    'success' => true,
    'user' => [
        'id' => $userId,
        'username' => $currentUser['username'],
        'nickname' => $userInfo['nickname'] ?? '',
        'avatar' => $userInfo['avatar'] ?? '',
        'address' => $userInfo['address'] ?? ['name' => '', 'phone' => '', 'detail' => ''],
        'role' => $currentUser['role']
    ]
]);
