<?php
session_start();
header('Content-Type: application/json');

require_once __DIR__ . '/../data.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => '请先登录']);
    exit;
}

$userId = $_SESSION['user_id'];
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    $input = $_POST;
}

$userInfoPath = dirname(dirname(__DIR__)) . '/data/users/' . $userId . '.json';
$dataPath = dirname(dirname(__DIR__)) . '/data';

// 读取现有用户信息
$userInfo = [];
if (file_exists($userInfoPath)) {
    $userInfo = json_decode(file_get_contents($userInfoPath), true) ?: [];
}

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

// 检查并更新用户名
if (isset($input['username']) && !empty($input['username'])) {
    $newUsername = trim($input['username']);
    if ($newUsername !== $currentUser['username']) {
        // 检查用户名是否已被占用
        $usernameExists = false;
        foreach ($users as $u) {
            if ($u['username'] === $newUsername && $u['id'] != $userId) {
                $usernameExists = true;
                break;
            }
        }
        if ($usernameExists) {
            echo json_encode(['success' => false, 'message' => '用户名已被占用']);
            exit;
        }
        // 更新 users.json 中的用户名
        foreach ($users as &$u) {
            if ($u['id'] == $userId) {
                $u['username'] = $newUsername;
                break;
            }
        }
        writeJson('users', $users);
        // 更新 session
        $_SESSION['user_username'] = $newUsername;
    }
}

// 更新用户信息
if (isset($input['nickname'])) {
    $userInfo['nickname'] = $input['nickname'];
}
if (isset($input['avatar'])) {
    $userInfo['avatar'] = $input['avatar'];
}
if (isset($input['address'])) {
    $userInfo['address'] = $input['address'];
}

// 保存用户信息
file_put_contents($userInfoPath, json_encode($userInfo, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT));

// 返回更新后的用户信息
$newUsername = isset($input['username']) ? trim($input['username']) : $currentUser['username'];
echo json_encode([
    'success' => true,
    'message' => '保存成功',
    'user' => [
        'id' => $userId,
        'username' => $newUsername,
        'nickname' => $userInfo['nickname'] ?? '',
        'avatar' => $userInfo['avatar'] ?? '',
        'address' => $userInfo['address'] ?? ['name' => '', 'phone' => '', 'detail' => ''],
        'role' => $currentUser['role']
    ]
]);
