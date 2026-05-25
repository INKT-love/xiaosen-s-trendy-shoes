<?php
// 避免任何 PHP 警告/错误输出成 HTML，保证只返回 JSON
error_reporting(E_ALL);
ini_set('display_errors', '0');
ini_set('log_errors', '1');

// 在解析 POST 前无法用 $_POST，但可读 Content-Length 与 post_max_size
$contentLength = isset($_SERVER['HTTP_CONTENT_LENGTH']) ? (int)$_SERVER['HTTP_CONTENT_LENGTH'] : 0;
$postMaxSize = ini_get('post_max_size');
$postMaxBytes = $postMaxSize ? (int)trim($postMaxSize) : 0;
if (preg_match('/^\s*(\d+)\s*([gmk]?)\s*$/i', trim($postMaxSize), $m)) {
    $n = (int)$m[1];
    $u = strtoupper($m[2] ?? '');
    $postMaxBytes = $u === 'G' ? $n * 1024 * 1024 * 1024 : ($u === 'M' ? $n * 1024 * 1024 : ($u === 'K' ? $n * 1024 : $n));
}
if ($contentLength > 0 && $postMaxBytes > 0 && $contentLength > $postMaxBytes) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(413);
    echo json_encode([
        'success' => false,
        'message' => '上传总大小超过服务器限制（当前限制约 ' . ini_get('post_max_size') . '），请缩小文件或联系管理员在 php.ini 中增大 post_max_size 和 upload_max_filesize。'
    ]);
    exit;
}

session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Content-Type: application/json; charset=utf-8');
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => '未登录']);
    exit;
}

header('Content-Type: application/json; charset=utf-8');

$action = $_POST['action'] ?? '';

// 处理单张图片上传
if ($action === 'upload_image') {
    if (!isset($_FILES['image']) || $_FILES['image']['error'] !== UPLOAD_ERR_OK) {
        $error = $_FILES['image']['error'] ?? '未知错误';
        echo json_encode(['success' => false, 'message' => '上传失败: ' . $error]);
        exit;
    }

    $result = uploadSingleFile($_FILES['image']);
    echo json_encode($result);
    exit;
}

// 处理批量图片上传
if ($action === 'upload_images') {
    // 检查是否有文件上传
    if (!isset($_FILES['images'])) {
        echo json_encode(['success' => false, 'message' => '没有上传文件，检查 $_FILES: ' . json_encode($_FILES)]);
        exit;
    }

    // 检查是否为数组（多文件上传）或字符串（单文件上传时 name="images[]" 也会是字符串）
    $names = $_FILES['images']['name'] ?? [];
    if (empty($names)) {
        echo json_encode(['success' => false, 'message' => '文件名为空']);
        exit;
    }

    // 如果是单文件，PHP 会传字符串，转为数组格式统一处理
    if (!is_array($names)) {
        $_FILES['images']['name'] = [$names];
        $_FILES['images']['type'] = [$_FILES['images']['type']];
        $_FILES['images']['tmp_name'] = [$_FILES['images']['tmp_name']];
        $_FILES['images']['error'] = [$_FILES['images']['error']];
        $_FILES['images']['size'] = [$_FILES['images']['size']];
    }

    $urls = [];
    $errors = [];

    $count = count($_FILES['images']['name']);
    for ($i = 0; $i < $count; $i++) {
        $fileError = $_FILES['images']['error'][$i];
        if ($fileError !== UPLOAD_ERR_OK) {
            $errors[] = '第' . ($i + 1) . '张上传失败: 错误码' . $fileError;
            continue;
        }

        $file = [
            'name' => $_FILES['images']['name'][$i],
            'type' => $_FILES['images']['type'][$i],
            'tmp_name' => $_FILES['images']['tmp_name'][$i],
            'error' => $_FILES['images']['error'][$i],
            'size' => $_FILES['images']['size'][$i]
        ];

        $result = uploadSingleFile($file);
        if ($result['success']) {
            $urls[] = $result['url'];
        } else {
            $errors[] = '第' . ($i + 1) . '张: ' . ($result['message'] ?? '上传失败');
        }
    }

    if (count($urls) > 0) {
        echo json_encode([
            'success' => true,
            'urls' => $urls,
            'message' => count($urls) . '张上传成功' . (count($errors) > 0 ? ', ' . implode(', ', $errors) : '')
        ]);
    } else {
        echo json_encode(['success' => false, 'message' => implode(', ', $errors) ?: '上传失败']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => '无效操作']);

// 单文件上传辅助函数
function uploadSingleFile($file) {
    if (empty($file['tmp_name']) || !file_exists($file['tmp_name'])) {
        return ['success' => false, 'message' => '临时文件无效'];
    }

    $allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];
    if (function_exists('mime_content_type')) {
        $fileType = @mime_content_type($file['tmp_name']);
    } else {
        $fileType = $file['type'] ?? '';
    }
    if (!$fileType || !in_array($fileType, $allowedTypes)) {
        return ['success' => false, 'message' => '仅支持 JPG、PNG、GIF、WebP 格式'];
    }

    $name = is_array($file['name']) ? ($file['name'][0] ?? '') : $file['name'];
    $ext = strtolower(pathinfo($name, PATHINFO_EXTENSION));
    if ($ext === 'jpeg') $ext = 'jpg';
    if (!in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp'])) $ext = 'jpg';
    $filename = uniqid('img_') . '.' . $ext;
    // api/admin/upload.php -> 往上一级是 api，再往上一级是项目根目录
    $uploadDir = dirname(dirname(dirname(__FILE__))) . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR;

    if (!is_dir($uploadDir)) {
        if (!@mkdir($uploadDir, 0755, true)) {
            return ['success' => false, 'message' => '无法创建上传目录'];
        }
    }

    $targetPath = $uploadDir . $filename;

    if (is_uploaded_file($file['tmp_name'])) {
        $ok = @move_uploaded_file($file['tmp_name'], $targetPath);
    } else {
        $ok = @copy($file['tmp_name'], $targetPath);
    }
    if ($ok) {
        return ['success' => true, 'url' => '/uploads/' . $filename];
    }
    return ['success' => false, 'message' => '保存文件失败'];
}
