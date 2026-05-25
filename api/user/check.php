<?php
session_start();
header('Content-Type: application/json');

if (isset($_SESSION['user_id']) && isset($_SESSION['user_username'])) {
    // 读取用户详细信息
    $userInfoPath = dirname(dirname(__DIR__)) . '/data/users/' . $_SESSION['user_id'] . '.json';
    $userInfo = [];
    if (file_exists($userInfoPath)) {
        $userInfo = json_decode(file_get_contents($userInfoPath), true) ?: [];
    }

    $fullUser = [
        'id' => $_SESSION['user_id'],
        'username' => $_SESSION['user_username'],
        'nickname' => $userInfo['nickname'] ?? '',
        'avatar' => $userInfo['avatar'] ?? '',
        'address' => $userInfo['address'] ?? ['name' => '', 'phone' => '', 'detail' => ''],
        'role' => $_SESSION['user_role'] ?? 'user'
    ];

    echo json_encode(['success' => true, 'logged_in' => true, 'user' => $fullUser]);
} else {
    echo json_encode(['success' => true, 'logged_in' => false]);
}
