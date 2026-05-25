<?php
// 动态获取商品标签
$productsFile = __DIR__ . '/data/products.json';
$allTags = [];

if (file_exists($productsFile)) {
    $products = json_decode(file_get_contents($productsFile), true);
    if ($products) {
        // 提取所有标签
        foreach ($products as $product) {
            if (!empty($product['tags']) && is_array($product['tags'])) {
                $allTags = array_merge($allTags, $product['tags']);
            }
        }
        // 去重
        $allTags = array_unique($allTags);
        // 按字母/拼音顺序排序（英文在前，中文在后）
        usort($allTags, function($a, $b) {
            return strcasecmp($a, $b);
        });
    }
}
?>
<header class="sticky top-0 z-10 bg-white border-b border-gray-100">
    <h1 class="text-center text-lg font-bold py-3 text-black">分类</h1>
</header>
<main class="flex-1 pb-20 px-4 pt-6">
    <p class="text-gray-500 text-sm mb-4">点击标签跳转首页并筛选</p>
    <div class="flex flex-wrap gap-3">
        <?php foreach ($allTags as $tag): ?>
        <a href="?page=home&tag=<?= urlencode($tag) ?>" class="category-tag px-4 py-2.5 bg-white rounded-xl shadow-sm border border-gray-100 text-gray-700 font-medium hover:bg-orange-500 hover:text-white hover:border-orange-500 transition-colors"><?= htmlspecialchars($tag) ?></a>
        <?php endforeach; ?>
    </div>
</main>
