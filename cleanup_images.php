<?php
/**
 * 清理无用图片脚本
 * 功能：删除 uploads 目录下不被任何商品引用的图片
 * 使用：直接在浏览器访问此文件，或命令行运行 php cleanup_images.php
 */

header('Content-Type: text/html; charset=utf-8');

$baseDir = __DIR__;
$uploadDir = $baseDir . '/uploads';
$productsFile = $baseDir . '/data/products.json';

echo "<pre>";
echo "========== 图片清理工具 ==========\n\n";

// 检查 uploads 目录
if (!is_dir($uploadDir)) {
    echo "❌ uploads 目录不存在\n";
    exit;
}

// 扫描 uploads 目录下的所有图片
$uploadedFiles = glob($uploadDir . '/*');
if (empty($uploadedFiles)) {
    echo "✓ uploads 目录为空，无需清理\n";
    exit;
}

$uploadedFiles = array_filter($uploadedFiles, 'is_file');
echo "📁 uploads 目录共有 " . count($uploadedFiles) . " 个文件\n\n";

// 读取商品数据，获取所有被引用的图片
$referencedImages = [];

if (file_exists($productsFile)) {
    $products = json_decode(file_get_contents($productsFile), true);
    if ($products) {
        foreach ($products as $product) {
            if (!empty($product['image'])) {
                $images = explode(',', $product['image']);
                foreach ($images as $img) {
                    $img = trim($img);
                    if ($img && strpos($img, '/uploads/') === 0) {
                        $referencedImages[] = $img;
                    }
                }
            }
        }
    }
}

$referencedImages = array_unique($referencedImages);
echo "📊 数据库共引用 " . count($referencedImages) . " 张图片\n\n";

// 找出无用图片
$orphanFiles = [];
foreach ($uploadedFiles as $file) {
    $fileName = '/uploads/' . basename($file);
    if (!in_array($fileName, $referencedImages)) {
        $orphanFiles[] = $file;
    }
}

if (empty($orphanFiles)) {
    echo "✅ 所有图片都已被引用，没有需要清理的文件\n";
} else {
    echo "🗑️  发现 " . count($orphanFiles) . " 张无用图片：\n\n";
    
    $totalSize = 0;
    foreach ($orphanFiles as $file) {
        $size = filesize($file);
        $totalSize += $size;
        $sizeStr = $size > 1024 * 1024 
            ? round($size / 1024 / 1024, 2) . ' MB' 
            : round($size / 1024, 2) . ' KB';
        echo "  - " . basename($file) . " ($sizeStr)\n";
    }
    
    $totalSizeStr = $totalSize > 1024 * 1024 
        ? round($totalSize / 1024 / 1024, 2) . ' MB' 
        : round($totalSize / 1024, 2) . ' KB';
    echo "\n共占用: $totalSizeStr\n";
    
    // 自动删除
    $deleted = 0;
    foreach ($orphanFiles as $file) {
        if (@unlink($file)) {
            $deleted++;
        }
    }
    echo "\n✅ 已清理 $deleted / " . count($orphanFiles) . " 个文件\n";
}

echo "\n========== 完成 ==========";
echo "</pre>";
