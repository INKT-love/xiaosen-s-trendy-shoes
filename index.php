<?php
declare(strict_types=1);

require_once __DIR__ . '/api/data.php';
$products = readJson('products');

if (empty($products)) {
    $products = [
        [
            'id' => 1,
            'name' => '【小森精选】Nike AJ1 Retro High 黑红',
            'price' => 1299,
            'image' => 'https://images.unsplash.com/photo-1542291026-7eec264c27ff?w=400',
            'tags' => ['AJ', 'Nike', '高帮', '限量'],
            'is_new' => true,
        ],
        [
            'id' => 2,
            'name' => '【小森精选】Nike Dunk Low 熊猫',
            'price' => 899,
            'image' => 'https://images.unsplash.com/photo-1606107557195-0e29a4b5b4aa?w=400',
            'tags' => ['Dunk', 'Nike', '低帮'],
            'is_new' => true,
        ],
        [
            'id' => 3,
            'name' => '【小森精选】Adidas Yeezy 350 V2',
            'price' => 1899,
            'image' => 'https://images.unsplash.com/photo-1600185365926-3a2ce3cdb9eb?w=400',
            'tags' => ['Yeezy', 'Adidas'],
            'is_new' => false,
        ],
        [
            'id' => 4,
            'name' => '【小森精选】Nike AJ4 军事蓝',
            'price' => 1399,
            'image' => 'https://images.unsplash.com/photo-1595950653106-6c9ebd614d3a?w=400',
            'tags' => ['AJ', 'Nike', '高帮'],
            'is_new' => true,
        ],
        [
            'id' => 5,
            'name' => '【小森精选】New Balance 550 白绿',
            'price' => 799,
            'image' => 'https://images.unsplash.com/photo-1539185441755-769473a23570?w=400',
            'tags' => ['New Balance', '复古'],
            'is_new' => false,
        ],
        [
            'id' => 6,
            'name' => '【小森精选】Nike AJ1 Low 芝加哥',
            'price' => 999,
            'image' => 'https://images.unsplash.com/photo-1552346154-21d32810aba3?w=400',
            'tags' => ['AJ', 'Nike', '低帮'],
            'is_new' => true,
        ],
        [
            'id' => 7,
            'name' => '【小森精选】Adidas Superstar',
            'price' => 699,
            'image' => 'https://images.unsplash.com/photo-1603808033192-082d6919d3e1?w=400',
            'tags' => ['Adidas', '经典'],
            'is_new' => false,
        ],
        [
            'id' => 8,
            'name' => '【小森精选】Nike SB Dunk 橙盒',
            'price' => 1099,
            'image' => 'https://images.unsplash.com/photo-1549298916-b41d501d3772?w=400',
            'tags' => ['Dunk', 'Nike', '滑板'],
            'is_new' => false,
        ],
        [
            'id' => 9,
            'name' => '【小森精选】Nike AJ3 白水泥',
            'price' => 1399,
            'image' => 'https://images.unsplash.com/photo-1605348532760-6753d2c43329?w=400',
            'tags' => ['AJ', 'Nike', '高帮', '限量'],
            'is_new' => true,
        ],
        [
            'id' => 10,
            'name' => '【小森精选】New Balance 990v5',
            'price' => 1599,
            'image' => 'https://images.unsplash.com/photo-1600185365483-26d2a4f751d6?w=400',
            'tags' => ['New Balance', '复古', '高端'],
            'is_new' => false,
        ],
        [
            'id' => 11,
            'name' => '【小森精选】Adidas Yeezy 700',
            'price' => 2099,
            'image' => 'https://images.unsplash.com/photo-1608231387042-66d1773070a5?w=400',
            'tags' => ['Yeezy', 'Adidas', '老爹鞋'],
            'is_new' => true,
        ],
        [
            'id' => 12,
            'name' => '【小森精选】Nike AJ1 Mid 影子灰',
            'price' => 1199,
            'image' => 'https://images.unsplash.com/photo-1612902377746-fb3c2d980c0d?w=400',
            'tags' => ['AJ', 'Nike', '中帮'],
            'is_new' => false,
        ],
    ];
}

$page = $_GET['page'] ?? 'home';
$allowed = ['home', 'cart', 'me', 'category', 'product'];
if (!in_array($page, $allowed, true)) {
    $page = 'home';
}

$initialTag = $_GET['tag'] ?? '';

// ========== 系列配置与分组逻辑 ==========
// 从 JSON 文件读取系列配置
$seriesFile = __DIR__ . '/data/series.json';
$seriesData = [];
if (file_exists($seriesFile)) {
    $seriesData = json_decode(file_get_contents($seriesFile), true) ?: [];
}

// 只保留启用的系列
$seriesConfig = [];
foreach ($seriesData as $s) {
    if (!isset($s['enabled']) || $s['enabled']) {
        $seriesConfig[] = $s;
    }
}

// 按系列分组商品
$sections = [];
foreach ($seriesConfig as $series) {
    $sections[$series['slug']] = [];
}
$sections['other'] = [];

foreach ($products as $p) {
    $tags = $p['tags'] ?? [];
    $assigned = false;
    foreach ($seriesConfig as $series) {
        if (in_array($series['name'], $tags, true)) {
            $sections[$series['slug']][] = $p;
            $assigned = true;
            break;
        }
    }
    if (!$assigned) {
        $sections['other'][] = $p;
    }
}

// 计算每个系列是否有商品（用于隐藏空系列）
$hasProducts = [];
foreach ($sections as $slug => $list) {
    $hasProducts[$slug] = !empty($list);
}

// 提取所有系列 slug 用于前端（用于标签点击滚动）
$allSeriesSlugs = array_column($seriesConfig, 'slug');
// ========== 系列分组结束 ==========

// 商品详情页：根据 id 获取商品
$detailProduct = null;
if ($page === 'product') {
    $detailId = isset($_GET['id']) ? (int) $_GET['id'] : 0;
    foreach ($products as $p) {
        if ((int)$p['id'] === $detailId) {
            $detailProduct = $p;
            break;
        }
    }
    if (!$detailProduct) {
        $page = 'home';
    }
}
?>
<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>小森的潮物</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: '#07c160',
                        orange: { 500: '#ff6b35', 400: '#ff8c5a', 600: '#e55a2b' }
                    }
                }
            }
        }
    </script>
    <link href="assets/style.css" rel="stylesheet">
    <style>
        /* 未打开时弹窗必须完全隐藏，避免在部分浏览器中露在页面上 */
        dialog:not([open]) {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-900 min-h-screen flex flex-col mx-auto" style="max-width: 640px;">
<?php if ($page === 'home'): ?>
    <header class="sticky top-0 z-20 bg-white shadow-sm">
        <div class="px-2 py-2 flex items-center gap-1 sm:gap-2 min-w-0">
            <h1 class="text-base sm:text-lg font-bold text-black shrink-0 flex-none">小森的潮物</h1>
            <div class="flex-1 min-w-0 flex items-center bg-gray-100 rounded-lg px-2 sm:px-3 py-1.5 sm:py-2 max-w-[50%] sm:max-w-none">
                <svg class="w-4 h-4 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                <input type="search" id="searchInput" placeholder="搜索商品" class="flex-1 min-w-0 bg-transparent text-sm outline-none ml-1 sm:ml-2 w-0">
            </div>
            <button type="button" id="clearFilterBtn" class="hidden px-2 py-1 text-xs text-orange-500 font-medium whitespace-nowrap shrink-0">清除</button>
            <button type="button" id="shareBtn" class="p-1.5 sm:p-2 text-gray-500 shrink-0" aria-label="分享"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"/></svg></button>
            <button type="button" id="userLoginBtn" class="p-1.5 sm:p-2 text-gray-500 shrink-0 text-sm min-w-[2.5rem] max-w-[5rem] sm:max-w-none truncate" aria-label="登录" title="登录">登录</button>
        </div>
        <div class="flex items-center justify-between border-b border-gray-100">
            <nav class="flex gap-6 px-4 py-2" id="tabNav">
                <button type="button" class="tab-btn text-brand font-medium border-b-2 border-brand pb-2 -mb-px" data-tab="all">全部</button>
                <button type="button" class="tab-btn text-gray-500 pb-2 -mb-px" data-tab="new">上新</button>
                <button type="button" class="tab-btn text-gray-500 pb-2 -mb-px" data-tab="video">视频</button>
                <button type="button" class="tab-btn text-gray-500 pb-2 -mb-px" data-tab="gallery">图集</button>
            </nav>
            <button type="button" id="notFoundBtn" class="mr-4 px-3 py-1.5 text-xs font-medium bg-orange-500 text-white rounded-full hover:bg-orange-600 active:bg-orange-700 transition-colors">没找到商品？</button>
        </div>
    </header>
    <main class="flex-1 pb-20">
        <!-- 三个系列放一块：黑框 + 跳转标签 -->
        <?php foreach ($seriesConfig as $series): ?>
        <section id="section-<?= htmlspecialchars($series['slug']) ?>" class="series-header" data-section="<?= htmlspecialchars($series['slug']) ?>">
            <!-- 黑色标题框 -->
            <div class="bg-gray-900 px-4 py-6 flex flex-col items-center justify-center">
                <span class="text-white font-bold text-2xl italic tracking-wide"><?= htmlspecialchars($series['display']) ?></span>
                <span class="text-white text-lg font-medium opacity-95 mt-1"><?= htmlspecialchars($series['display_cn'] ?? $series['display']) ?></span>
            </div>
            <!-- 白框：跳转标签 -->
            <div class="bg-white px-4 py-4 border-b border-gray-100">
                <div class="flex flex-wrap gap-2 justify-center">
                    <?php
                    $hotTags = $series['hot_tags'] ?? [];
                    foreach ($hotTags as $tag):
                    ?>
                    <a href="?page=home&tag=<?= urlencode($tag) ?>" class="jump-tag-btn px-4 py-2 rounded-lg bg-gray-100 text-gray-700 text-sm font-medium hover:bg-orange-500 hover:text-white transition-colors" data-tag="<?= htmlspecialchars($tag) ?>"><?= htmlspecialchars($tag) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
        <?php endforeach; ?>

        <!-- 统一商品网格 -->
        <div id="productGrid" class="grid grid-cols-2 gap-2 p-3 bg-white">
            <?php foreach ($products as $p): ?>
            <?php $firstImage = is_array($p['image'] ?? null) ? ($p['image'][0] ?? '') : (strpos($p['image'] ?? '', ',') !== false ? trim(explode(',', $p['image'])[0]) : ($p['image'] ?? '')); $firstImage = image_url($firstImage); ?>
            <article class="product-card group bg-white rounded-lg overflow-hidden relative" data-id="<?= (int)$p['id'] ?>" data-tags="<?= htmlspecialchars(json_encode($p['tags'])) ?>" data-new="<?= $p['is_new'] ? '1' : '0' ?>" data-jump-tag="<?= htmlspecialchars($p['jump_tag'] ?? '') ?>">
                <a href="?page=product&id=<?= (int)$p['id'] ?>" class="block">
                    <div class="relative aspect-square bg-gray-50 flex items-center justify-center">
                        <img data-src="<?= htmlspecialchars($firstImage) ?>" src="data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Crect fill='%23f9fafb' width='100' height='100'/%3E%3C/svg%3E" alt="" class="lazy-img w-full h-full object-contain">
                        <button type="button" class="favorite-btn absolute top-1 right-1 w-6 h-6 flex items-center justify-center rounded-full bg-white/90 text-gray-500" data-id="<?= (int)$p['id'] ?>" data-name="<?= htmlspecialchars($p['name']) ?>" data-price="<?= (int)$p['price'] ?>" data-image="<?= htmlspecialchars($firstImage) ?>" aria-label="收藏"><svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg></button>
                    </div>
                    <div class="py-1.5 px-0.5 text-center">
                        <h2 class="text-xs font-medium text-gray-800 line-clamp-1 product-name"><?= htmlspecialchars($p['name']) ?></h2>
                        <div class="flex flex-wrap gap-0.5 mt-1 justify-center" data-tag-wrap>
                            <?php foreach ($p['tags'] as $tag): ?>
                            <span class="tag cursor-pointer text-[10px] px-1.5 py-0.5 rounded bg-gray-100 text-gray-500 hover:bg-orange-500 hover:text-white" data-tag="<?= htmlspecialchars($tag) ?>"><?= htmlspecialchars($tag) ?></span>
                            <?php endforeach; ?>
                        </div>
                        <div class="mt-1">
                            <span class="text-red-600 font-bold text-xs">¥<?= number_format($p['price']) ?></span>
                        </div>
                    </div>
                </a>
                <div class="absolute right-1 bottom-1">
                    <button type="button"
                        data-id="<?= (int)$p['id'] ?>"
                        data-name="<?= htmlspecialchars($p['name']) ?>"
                        data-price="<?= (int)$p['price'] ?>"
                        data-image="<?= htmlspecialchars($firstImage) ?>"
                        class="add-to-cart-btn w-7 h-7 rounded-full bg-green-600 text-white flex items-center justify-center shadow hover:bg-green-700 active:bg-green-800"
                        aria-label="加入购物车">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/></svg>
                    </button>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div id="noResults" class="hidden text-center py-12 text-gray-500">暂无相关商品</div>
        <!-- 回到顶部 -->
        <button type="button" id="backToTopBtn" class="fixed bottom-20 right-4 z-20 w-11 h-11 rounded-full bg-white shadow-lg border border-gray-200 flex items-center justify-center text-gray-700 hover:bg-gray-50 active:scale-95 transition-all" aria-label="回到顶部">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18"/></svg>
        </button>
    </main>
<?php elseif ($page === 'cart'): ?>
    <?php include __DIR__ . '/cart.php'; ?>
<?php elseif ($page === 'me'): ?>
    <?php include __DIR__ . '/me.php'; ?>
<?php elseif ($page === 'category'): ?>
    <?php include __DIR__ . '/category.php'; ?>
<?php elseif ($page === 'product' && $detailProduct): ?>
    <?php include __DIR__ . '/product.php'; ?>
<?php endif; ?>

    <nav class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-30 mx-auto" style="max-width: 640px;">
        <div class="flex justify-around py-2">
            <a href="?page=home" class="flex flex-col items-center py-1 <?= $page === 'home' ? 'text-brand' : 'text-gray-400' ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
                <span class="text-xs mt-0.5">首页</span>
            </a>
            <a href="?page=category" class="flex flex-col items-center py-1 <?= $page === 'category' ? 'text-brand' : 'text-gray-400' ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/></svg>
                <span class="text-xs mt-0.5">分类</span>
            </a>
            <a href="?page=cart" class="flex flex-col items-center py-1 relative <?= $page === 'cart' ? 'text-brand' : 'text-gray-400' ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13h10l4-8H5.4M7 13v10a1 1 0 001 1h10a1 1 0 001-1V7h2a1 1 0 001-1v-2a1 1 0 00-1-1H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                <span id="cartBadge" class="absolute -top-0.5 right-1/2 translate-x-4 min-w-[18px] h-[18px] flex items-center justify-center rounded-full bg-red-500 text-white text-xs font-medium hidden">0</span>
                <span class="text-xs mt-0.5">购物车</span>
            </a>
            <a href="?page=me" class="flex flex-col items-center py-1 <?= $page === 'me' ? 'text-brand' : 'text-gray-400' ?>">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                <span class="text-xs mt-0.5">我</span>
            </a>
        </div>
    </nav>

    <!-- 商品规格选择弹窗（须在 script 前，保证 getElementById 能取到） -->
    <dialog id="productSelectModal" class="rounded-2xl p-0 backdrop:bg-black/50 w-full max-w-sm mx-auto max-h-[90vh] overflow-hidden flex flex-col">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden flex flex-col max-h-[90vh]">
            <div class="p-4 border-b border-gray-100 flex items-start gap-3 shrink-0">
                <button type="button" id="productSelectModalClose" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-500 -ml-1" aria-label="关闭">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
                <img id="productSelectModalImage" src="" alt="" class="w-16 h-16 object-cover rounded-lg bg-gray-100 shrink-0">
                <div class="flex-1 min-w-0">
                    <p id="productSelectModalPrice" class="text-red-600 font-bold text-lg">¥0</p>
                    <div class="flex gap-4 mt-2">
                        <button type="button" class="product-select-type text-sm text-brand border-b-2 border-brand -mb-px pb-1 font-medium" data-type="single">单买</button>
                        <button type="button" class="product-select-type text-sm text-gray-600 border-b-2 border-transparent -mb-px pb-1" data-type="batch">批量</button>
                    </div>
                </div>
            </div>
            <div class="p-4 overflow-y-auto flex-1 min-h-0">
                <div id="productSelectModalSizeList" class="space-y-3">
                </div>
            </div>
            <div class="p-4 border-t border-gray-200 bg-white shrink-0">
                <div class="flex items-center justify-between mb-3">
                    <span class="text-gray-600 text-sm">共 <span id="productSelectModalTotalQty" class="font-medium text-black">0</span> 件 <span id="productSelectModalTotalPrice" class="font-medium text-red-600">¥0</span></span>
                </div>
                <div class="flex gap-3">
                    <button type="button" id="productSelectModalBuyNow" class="flex-1 py-3 rounded-xl bg-red-600 text-white font-medium hover:bg-red-700 active:bg-red-800">立即购买</button>
                    <button type="button" id="productSelectModalAddCart" class="flex-1 py-3 rounded-xl bg-orange-500 text-white font-medium hover:bg-orange-600 active:bg-orange-700">加入购物车</button>
                </div>
            </div>
        </div>
    </dialog>

    <!-- 分享弹窗 -->
    <dialog id="shareModal" class="fixed inset-0 z-50 rounded-2xl p-0 backdrop:bg-black/50 w-full max-w-sm mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <h3 class="text-lg font-bold text-black mb-2">可以分享给好友啦~</h3>
                <p class="text-sm text-gray-500 mb-4">扫码或分享链接打开</p>
                <div class="flex justify-center mb-4">
                    <img id="shareQrImage" src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=https%3A%2F%2Fxscw.inktandwkx.love%3A1145" alt="分享二维码" class="w-48 h-48 rounded-lg border border-gray-200">
                </div>
                <p class="text-xs text-orange-600 mb-4">转发至朋友圈并配文（保留至少3天，不屏蔽他人）可找客服领取5元奖励~</p>
                <button type="button" id="shareModalClose" class="w-full py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50">关闭</button>
            </div>
        </div>
    </dialog>

    <!-- 收货地址提示弹窗 -->
    <dialog id="addressPromptModal" class="rounded-2xl p-0 backdrop:bg-black/50 w-full max-w-sm mx-auto relative">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-orange-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-black mb-2">请先填写收货地址</h3>
                <p class="text-sm text-gray-500 mb-6">加入购物车前请先填写收货地址，方便商家为您发货</p>
                <button type="button" id="addressPromptGoBtn" class="w-full py-3 rounded-xl bg-orange-500 text-white font-medium hover:bg-orange-600">去填写</button>
            </div>
        </div>
    </dialog>

    <!-- 没找到商品弹窗 -->
    <dialog id="notFoundModal" class="fixed inset-0 z-50 rounded-2xl p-0 backdrop:bg-black/50 w-full max-w-sm mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-orange-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-black mb-2">没找到想要的商品？</h3>
                <p class="text-sm text-gray-500 mb-4">添加客服微信，帮你找货~</p>
                <div class="flex items-center justify-center gap-2 mb-4">
                    <span class="text-gray-600 text-sm">微信号：</span>
                    <span id="wechatId" class="text-lg font-bold text-orange-600">INKT_Love</span>
                    <button type="button" id="copyWechatBtn" class="px-2 py-1 text-xs bg-gray-100 text-gray-600 rounded hover:bg-gray-200">复制</button>
                </div>
                <button type="button" id="notFoundModalClose" class="w-full py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50">关闭</button>
            </div>
        </div>
    </dialog>

    <!-- 付款弹窗 -->
    <dialog id="paymentModal" class="fixed inset-0 z-50 rounded-2xl p-0 backdrop:bg-black/50 w-full max-w-sm mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-5 border-b border-gray-100 text-center relative">
                <h3 class="text-lg font-bold text-black">选择支付方式</h3>
                <button type="button" id="paymentModalClose" class="absolute top-3 right-3 w-8 h-8 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-400" aria-label="关闭">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
            <div class="p-5">
                <p class="text-center text-gray-600 text-sm mb-4">需支付 <span id="paymentAmount" class="text-red-600 font-bold text-xl">¥0</span></p>
                <!-- 支付方式选择 -->
                <div class="flex gap-2 mb-4">
                    <button type="button" id="payMethodWechat" class="payment-method-btn flex-1 py-2.5 rounded-xl border-2 border-green-500 bg-green-50 text-green-700 font-medium text-sm flex items-center justify-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-green-500 flex items-center justify-center text-white text-xs">微</span>微信支付
                    </button>
                    <button type="button" id="payMethodAlipay" class="payment-method-btn flex-1 py-2.5 rounded-xl border-2 border-gray-200 bg-gray-50 text-gray-600 font-medium text-sm flex items-center justify-center gap-2">
                        <span class="w-6 h-6 rounded-full bg-blue-500 flex items-center justify-center text-white text-xs">支</span>支付宝
                    </button>
                </div>
                <div class="flex justify-center mb-4">
                    <img id="paymentQrImage" src="assets/wechat-pay-qr.png" alt="支付二维码" class="w-48 h-48 rounded-lg object-contain bg-gray-50 border border-gray-200">
                </div>
                <p id="paymentQrHint" class="text-center text-gray-500 text-xs mb-5">请使用微信扫描上方二维码完成支付</p>
                <button type="button" id="paymentConfirmBtn" class="w-full py-3 rounded-xl bg-green-600 text-white font-medium hover:bg-green-700 active:bg-green-800">完成支付</button>
            </div>
        </div>
    </dialog>

    <!-- 支付审核提示弹窗 -->
    <dialog id="paymentVerifyModal" class="fixed inset-0 z-50 rounded-2xl p-0 backdrop:bg-black/50 w-full max-w-sm mx-auto">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6 text-center">
                <div class="w-14 h-14 mx-auto mb-3 rounded-full bg-yellow-100 flex items-center justify-center">
                    <svg class="w-7 h-7 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-black mb-2">订单已提交</h3>
                <p class="text-sm text-gray-500 mb-6">支付状态正在审核中，我们会尽快发货~</p>
                <button type="button" id="paymentVerifyModalClose" class="w-full py-3 rounded-xl bg-yellow-500 text-white font-medium hover:bg-yellow-600">我知道了</button>
            </div>
        </div>
    </dialog>

    <!-- 登录/注册弹窗 -->
    <dialog id="loginModal" class="rounded-2xl p-0 backdrop:bg-black/50 w-full max-w-sm mx-auto relative">
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            <div class="p-6">
                <h3 id="loginModalTitle" class="text-xl font-bold text-black text-center mb-6">登录</h3>
                <form id="loginForm">
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">用户名</label>
                        <input type="text" id="loginUsername" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-brand focus:outline-none" placeholder="请输入用户名">
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">密码</label>
                        <input type="password" id="loginPassword" required class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-brand focus:outline-none" placeholder="请输入密码">
                    </div>
                    <p id="loginError" class="text-red-500 text-sm mb-4 hidden"></p>
                    <button type="submit" id="loginSubmitBtn" class="w-full py-3 rounded-xl bg-green-600 text-white font-medium hover:bg-green-700">登录</button>
                </form>
                <div class="mt-4 text-center">
                    <span id="loginToggleText" class="text-sm text-gray-500">还没有账号？</span>
                    <button type="button" id="loginToggleBtn" class="text-sm text-brand font-medium ml-1">立即注册</button>
                </div>
                <button type="button" id="loginModalClose" class="w-full mt-3 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50">取消</button>
            </div>
        </div>
    </dialog>

    <script>
    (function() {
        // 图片懒加载 - 使用 Intersection Observer
        function initLazyLoad() {
            if ('IntersectionObserver' in window) {
                var observer = new IntersectionObserver(function(entries) {
                    entries.forEach(function(entry) {
                        if (entry.isIntersecting) {
                            var img = entry.target;
                            var src = img.dataset.src;
                            if (src) {
                                img.src = src;
                                img.onerror = function() {
                                    this.onerror = null;
                                    this.src = "data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='100' height='100' viewBox='0 0 100 100'%3E%3Crect fill='%23f3f4f6' width='100' height='100'/%3E%3Ctext x='50' y='55' fill='%239ca3af' font-size='12' text-anchor='middle'%3E暂无图片%3C/text%3E%3C/svg%3E";
                                };
                                img.classList.remove('lazy-img');
                                observer.unobserve(img);
                            }
                        }
                    });
                }, { rootMargin: '50px' });
                
                document.querySelectorAll('.lazy-img').forEach(function(img) {
                    observer.observe(img);
                });
            } else {
                // 兼容不支持 IntersectionObserver 的浏览器
                document.querySelectorAll('.lazy-img').forEach(function(img) {
                    var src = img.dataset.src;
                    if (src) img.src = src;
                });
            }
        }
        
        // 页面加载完成后初始化懒加载
        if (document.readyState === 'loading') {
            document.addEventListener('DOMContentLoaded', initLazyLoad);
        } else {
            initLazyLoad();
        }

        var isLoggedIn = false;
        var currentUser = null;
        var loginMode = 'login';

        var USER_KEY = 'xiaosen_user';

        function getStoredUser() {
            try {
                var raw = localStorage.getItem(USER_KEY);
                return raw ? JSON.parse(raw) : null;
            } catch (e) {
                return null;
            }
        }

        function setStoredUser(user) {
            if (user) {
                localStorage.setItem(USER_KEY, JSON.stringify(user));
            } else {
                localStorage.removeItem(USER_KEY);
            }
            currentUser = user;
            isLoggedIn = !!user;
        }

        // 检查登录状态
        function checkLoginStatus() {
            var storedUser = getStoredUser();
            if (storedUser) {
                isLoggedIn = true;
                currentUser = storedUser;
                updateLoginUI();
                return;
            }

            var xhr = new XMLHttpRequest();
            xhr.open('GET', 'api/user/check.php', true);
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success && response.logged_in && response.user) {
                            setStoredUser(response.user);
                        }
                        updateLoginUI();
                    } catch (e) {
                        updateLoginUI();
                    }
                }
            };
            xhr.send();
        }

        function updateLoginUI() {
            var loginBtn = document.getElementById('userLoginBtn');
            if (!loginBtn) return;

            if (isLoggedIn && currentUser) {
                var displayName = currentUser.nickname || currentUser.username;
                loginBtn.textContent = displayName;
                loginBtn.title = displayName;
                loginBtn.classList.remove('text-gray-500');
                loginBtn.classList.add('text-brand', 'font-medium');
            } else {
                loginBtn.textContent = '登录';
                loginBtn.title = '登录';
                loginBtn.classList.add('text-gray-500');
                loginBtn.classList.remove('text-brand', 'font-medium');
            }
        }

        function openLoginModal() {
            var modal = document.getElementById('loginModal');
            if (modal) modal.showModal();
        }

        function closeLoginModal() {
            var modal = document.getElementById('loginModal');
            if (modal) modal.close();
            document.getElementById('loginUsername').value = '';
            document.getElementById('loginPassword').value = '';
            document.getElementById('loginError').classList.add('hidden');
        }

        function switchLoginMode(mode) {
            loginMode = mode;
            var title = document.getElementById('loginModalTitle');
            var submitBtn = document.getElementById('loginSubmitBtn');
            var toggleText = document.getElementById('loginToggleText');
            var toggleBtn = document.getElementById('loginToggleBtn');

            if (mode === 'login') {
                title.textContent = '登录';
                submitBtn.textContent = '登录';
                toggleText.textContent = '还没有账号？';
                toggleBtn.textContent = '立即注册';
            } else {
                title.textContent = '注册';
                submitBtn.textContent = '注册';
                toggleText.textContent = '已有账号？';
                toggleBtn.textContent = '立即登录';
            }
            document.getElementById('loginError').classList.add('hidden');
        }

        function showLoginError(msg) {
            var el = document.getElementById('loginError');
            el.textContent = msg;
            el.classList.remove('hidden');
        }

        function handleLoginSubmit(e) {
            e.preventDefault();
            var username = document.getElementById('loginUsername').value.trim();
            var password = document.getElementById('loginPassword').value;
            var submitBtn = document.getElementById('loginSubmitBtn');

            if (!username || !password) {
                showLoginError('请输入用户名和密码');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = '登录中...';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api/user/login.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                submitBtn.disabled = false;
                submitBtn.textContent = '登录';

                if (xhr.readyState === 4) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            setStoredUser(response.user);
                            closeLoginModal();
                            updateLoginUI();
                        } else {
                            showLoginError(response.message || '登录失败');
                        }
                    } catch (e) {
                        showLoginError('登录失败，请稍后重试');
                    }
                }
            };
            xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
        }

        function handleRegisterSubmit(e) {
            e.preventDefault();
            var username = document.getElementById('loginUsername').value.trim();
            var password = document.getElementById('loginPassword').value;
            var submitBtn = document.getElementById('loginSubmitBtn');

            if (!username || !password) {
                showLoginError('请输入用户名和密码');
                return;
            }

            submitBtn.disabled = true;
            submitBtn.textContent = '注册中...';

            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api/user/register.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                submitBtn.disabled = false;
                submitBtn.textContent = '注册';

                if (xhr.readyState === 4) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            // 注册成功后提示用户并切换到登录页面
                            closeLoginModal();
                            alert('注册成功，请登录');
                            switchLoginMode('login');
                            openLoginModal();
                        } else {
                            showLoginError(response.message || '注册失败');
                        }
                    } catch (e) {
                        showLoginError('注册失败，请稍后重试');
                    }
                }
            };
            xhr.send('username=' + encodeURIComponent(username) + '&password=' + encodeURIComponent(password));
        }

        function handleLogout() {
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'api/user/logout.php', true);
            xhr.onreadystatechange = function() {
                localStorage.removeItem('xiaosen_user');
                setStoredUser(null);
                updateLoginUI();
                location.reload();
            };
            xhr.send();
        }

        // 初始化登录状态检查
        checkLoginStatus();

        // 登录按钮点击
        var userLoginBtn = document.getElementById('userLoginBtn');
        if (userLoginBtn) {
            userLoginBtn.addEventListener('click', function() {
                if (isLoggedIn && currentUser) {
                    if (confirm('确定要退出登录吗？')) {
                        handleLogout();
                    }
                } else {
                    switchLoginMode('login');
                    openLoginModal();
                }
            });
        }

        // 登录弹窗关闭按钮
        var loginModalClose = document.getElementById('loginModalClose');
        if (loginModalClose) {
            loginModalClose.addEventListener('click', closeLoginModal);
        }

        // 登录/注册切换
        var loginToggleBtn = document.getElementById('loginToggleBtn');
        if (loginToggleBtn) {
            loginToggleBtn.addEventListener('click', function() {
                switchLoginMode(loginMode === 'login' ? 'register' : 'login');
            });
        }

        // 表单提交
        var loginForm = document.getElementById('loginForm');
        if (loginForm) {
            loginForm.addEventListener('submit', function(e) {
                if (loginMode === 'login') {
                    handleLoginSubmit(e);
                } else {
                    handleRegisterSubmit(e);
                }
            });
        }

        // 弹窗点击背景关闭
        var loginModal = document.getElementById('loginModal');
        if (loginModal) {
            loginModal.addEventListener('click', function(e) {
                if (e.target === loginModal) closeLoginModal();
            });
        }

        // 需要登录的操作
        function requireLogin(callback) {
            if (isLoggedIn && currentUser) {
                callback();
            } else {
                switchLoginMode('login');
                openLoginModal();
            }
        }

        // 阻止未登录用户购物 - 覆盖原有的 addToCart 函数
        var originalAddToCart = null;
        var CART_KEY = 'xiaosen_cart';

        function imageUrl(s) {
            if (!s) return '';
            if (typeof s !== 'string') return s;
            if (s.indexOf('http') === 0 || s.indexOf('/') === 0) return s;
            return '/' + s;
        }

        function getCart() {
            try {
                const raw = localStorage.getItem(CART_KEY);
                return raw ? JSON.parse(raw) : [];
            } catch (_) {
                return [];
            }
        }

        function saveCart(items) {
            localStorage.setItem(CART_KEY, JSON.stringify(items));
            updateCartBadge();
        }

        function getCartCount() {
            return getCart().reduce(function(sum, it) {
                return sum + (it.quantity || 1);
            }, 0);
        }

        function updateCartBadge() {
            const el = document.getElementById('cartBadge');
            if (!el) return;
            const n = getCartCount();
            el.textContent = n;
            el.classList.toggle('hidden', n === 0);
        }

        function showToast(message) {
            var existing = document.getElementById('cartToast');
            if (existing) existing.remove();

            var toast = document.createElement('div');
            toast.id = 'cartToast';
            toast.className = 'fixed z-50 left-1/2 -translate-x-1/2 bottom-24 bg-gray-800 text-white text-sm px-4 py-2 rounded-full shadow-lg transform transition-all duration-300 opacity-0';
            toast.textContent = message;
            document.body.appendChild(toast);

            requestAnimationFrame(function() {
                toast.classList.remove('opacity-0');
            });

            setTimeout(function() {
                toast.classList.add('opacity-0');
                setTimeout(function() {
                    toast.remove();
                }, 300);
            }, 2000);
        }

        function addToCart(id, name, price, image) {
            // 检查登录状态
            if (!isLoggedIn || !currentUser) {
                switchLoginMode('login');
                openLoginModal();
                return;
            }

            // 检查收货地址（优先使用用户信息中的地址）
            var address = null;
            if (currentUser && currentUser.address) {
                address = currentUser.address;
            }
            if (!address || !address.name || !address.phone || !address.detail) {
                openAddressPromptModal();
                return;
            }

            const cart = getCart();
            const existing = cart.find(function(item) {
                return item.id === id;
            });

            if (existing) {
                existing.quantity += 1;
            } else {
                cart.push({
                    id: id,
                    name: name,
                    price: Number(price),
                    image: image,
                    quantity: 1
                });
            }

            saveCart(cart);
            showToast('已加入购物车');
        }

        // ========== 商品规格选择弹窗 ==========
        var SIZE_OPTIONS = ['35', '35.5', '36', '36.5', '37', '37.5', '38', '38.5', '39', '40', '40.5', '41', '42', '42.5', '43', '44', '44.5', '45', '46'];
        var currentProductSelectProduct = null;
        var productSelectModal = document.getElementById('productSelectModal');
        var productSelectModalSizeList = document.getElementById('productSelectModalSizeList');
        var productSelectModalTotalQty = document.getElementById('productSelectModalTotalQty');
        var productSelectModalTotalPrice = document.getElementById('productSelectModalTotalPrice');
        var currentSelectType = 'single'; // 当前选择模式：single单买 或 batch批量

        // 渲染尺码列表（根据单买/批量模式）
        function renderSizeList(product, selectType) {
            var html = '';
            if (selectType === 'single') {
                // 单买模式：只显示尺码按钮，点击选中
                for (var i = 0; i < SIZE_OPTIONS.length; i++) {
                    var sz = SIZE_OPTIONS[i];
                    html += '<button type="button" class="product-select-size-btn px-4 py-2 rounded-lg border border-gray-300 text-gray-700 text-sm hover:border-orange-500 hover:text-orange-500" data-size="' + sz + '">' + sz + '</button>';
                }
            } else {
                // 批量模式：每个尺码都要选择数量
                for (var i = 0; i < SIZE_OPTIONS.length; i++) {
                    var sz = SIZE_OPTIONS[i];
                    html += '<div class="flex items-center gap-3 py-2 border-b border-gray-100" data-size="' + sz + '">' +
                        '<img src="' + imageUrl(product.image || '') + '" alt="" class="w-12 h-12 object-cover rounded-lg bg-gray-100 shrink-0">' +
                        '<span class="text-sm font-medium w-8">' + sz + '</span>' +
                        '<button type="button" class="product-select-size-dec w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100 shrink-0">-</button>' +
                        '<input type="number" class="product-select-size-qty w-14 text-center border border-gray-300 rounded-lg py-1.5 text-sm" value="0" min="0" max="99" data-size="' + sz + '">' +
                        '<button type="button" class="product-select-size-inc w-8 h-8 rounded-lg border border-gray-300 flex items-center justify-center text-gray-600 hover:bg-gray-100 shrink-0">+</button>' +
                        '</div>';
                }
            }
            if (productSelectModalSizeList) productSelectModalSizeList.innerHTML = html;
        }

        function openProductSelectModal(product) {
            currentProductSelectProduct = product;
            var img = document.getElementById('productSelectModalImage');
            var priceEl = document.getElementById('productSelectModalPrice');
            if (img) img.src = imageUrl(product.image || '');
            if (priceEl) priceEl.textContent = '¥' + Number(product.price || 0).toLocaleString();

            // 根据当前模式渲染尺码列表
            renderSizeList(product, currentSelectType);
            updateProductSelectTotal();
            if (productSelectModal) productSelectModal.showModal();
        }

        function closeProductSelectModal() {
            if (productSelectModal) productSelectModal.close();
            currentProductSelectProduct = null;
        }

        function getProductSelectQtys() {
            var qtys = {};
            if (!productSelectModalSizeList) return qtys;

            // 单买模式：从选中的按钮获取
            var selectedBtn = productSelectModalSizeList.querySelector('.product-select-size-btn.border-orange-500');
            if (selectedBtn) {
                var size = selectedBtn.getAttribute('data-size');
                qtys[size] = 1;
                return qtys;
            }

            // 批量模式：从输入框获取
            var inputs = productSelectModalSizeList.querySelectorAll('.product-select-size-qty');
            for (var i = 0; i < inputs.length; i++) {
                var size = inputs[i].getAttribute('data-size');
                var qty = parseInt(inputs[i].value, 10) || 0;
                if (size && qty > 0) qtys[size] = qty;
            }
            return qtys;
        }

        function updateProductSelectTotal() {
            var totalQty = 0;
            var totalPrice = 0;
            if (productSelectModalSizeList && currentProductSelectProduct) {
                // 单买模式：检查是否有选中的尺码
                var selectedBtn = productSelectModalSizeList.querySelector('.product-select-size-btn.border-orange-500');
                if (selectedBtn) {
                    totalQty = 1;
                    totalPrice = Number(currentProductSelectProduct.price || 0);
                } else {
                    // 批量模式：从输入框获取
                    var inputs = productSelectModalSizeList.querySelectorAll('.product-select-size-qty');
                    for (var i = 0; i < inputs.length; i++) {
                        var qty = parseInt(inputs[i].value, 10) || 0;
                        totalQty += qty;
                        totalPrice += qty * Number(currentProductSelectProduct.price || 0);
                    }
                }
            }
            if (productSelectModalTotalQty) productSelectModalTotalQty.textContent = totalQty;
            if (productSelectModalTotalPrice) productSelectModalTotalPrice.textContent = '¥' + totalPrice;
        }

        if (productSelectModal) {
            productSelectModal.addEventListener('click', function(e) {
                // 单买模式：点击尺码按钮选中
                var sizeBtn = e.target.closest('.product-select-size-btn');
                if (sizeBtn) {
                    e.preventDefault();
                    // 移除其他按钮的选中状态
                    productSelectModalSizeList.querySelectorAll('.product-select-size-btn').forEach(function(btn) {
                        btn.classList.remove('border-orange-500', 'text-orange-500', 'bg-orange-50');
                        btn.classList.add('border-gray-300', 'text-gray-700');
                    });
                    // 选中当前按钮
                    sizeBtn.classList.add('border-orange-500', 'text-orange-500', 'bg-orange-50');
                    sizeBtn.classList.remove('border-gray-300', 'text-gray-700');
                    updateProductSelectTotal();
                    return;
                }

                var dec = e.target.closest('.product-select-size-dec');
                var inc = e.target.closest('.product-select-size-inc');
                if (dec) {
                    e.preventDefault();
                    var row = dec.closest('[data-size]');
                    var input = row ? row.querySelector('.product-select-size-qty') : null;
                    if (input) {
                        var v = parseInt(input.value, 10) || 0;
                        if (v > 0) { input.value = v - 1; updateProductSelectTotal(); }
                    }
                }
                if (inc) {
                    e.preventDefault();
                    var row = inc.closest('[data-size]');
                    var input = row ? row.querySelector('.product-select-size-qty') : null;
                    if (input) {
                        var v = parseInt(input.value, 10) || 0;
                        if (v < 99) { input.value = v + 1; updateProductSelectTotal(); }
                    }
                }
            });
            productSelectModal.addEventListener('input', function(e) {
                if (e.target.classList && e.target.classList.contains('product-select-size-qty')) {
                    var v = parseInt(e.target.value, 10);
                    if (isNaN(v) || v < 0) e.target.value = 0;
                    if (v > 99) e.target.value = 99;
                    updateProductSelectTotal();
                }
            });
        }

        document.getElementById('productSelectModalClose') && document.getElementById('productSelectModalClose').addEventListener('click', closeProductSelectModal);

        // 立即购买：选择规格后直接跳转支付
        document.getElementById('productSelectModalBuyNow') && document.getElementById('productSelectModalBuyNow').addEventListener('click', function() {
            var qtys = getProductSelectQtys();
            var keys = Object.keys(qtys);
            if (keys.length === 0) {
                showToast(currentSelectType === 'single' ? '请先选择尺码' : '请先选择尺码和数量');
                return;
            }

            // 检查登录状态
            if (!isLoggedIn || !currentUser) {
                closeProductSelectModal();
                switchLoginMode('login');
                openLoginModal();
                return;
            }

            // 检查收货地址（优先使用用户信息中的地址）
            var address = null;
            if (currentUser && currentUser.address) {
                address = currentUser.address;
            }
            if (!address || !address.name || !address.phone || !address.detail) {
                openAddressPromptModal();
                return;
            }

            if (!currentProductSelectProduct) return;

            // 直接购买：创建临时订单并打开支付弹窗
            var total = 0;
            var orderItems = [];
            for (var i = 0; i < keys.length; i++) {
                var size = keys[i];
                var quantity = qtys[size];
                var price = Number(currentProductSelectProduct.price);
                total += price * quantity;
                orderItems.push({
                    id: currentProductSelectProduct.id,
                    name: currentProductSelectProduct.name,
                    price: price,
                    image: currentProductSelectProduct.image,
                    size: size,
                    quantity: quantity
                });
            }

            // 保存临时订单到 sessionStorage，用于支付时创建真实订单
            sessionStorage.setItem('xiaosen_pending_order', JSON.stringify(orderItems));

            // 打开支付弹窗
            var paymentModal = document.getElementById('paymentModal');
            var paymentAmount = document.getElementById('paymentAmount');
            if (paymentAmount) paymentAmount.textContent = '¥' + total.toLocaleString();
            if (typeof setPaymentMethod === 'function') setPaymentMethod('wechat');
            if (paymentModal) paymentModal.showModal();
            closeProductSelectModal();
        });

        // 加入购物车
        document.getElementById('productSelectModalAddCart') && document.getElementById('productSelectModalAddCart').addEventListener('click', function() {
            var qtys = getProductSelectQtys();
            var keys = Object.keys(qtys);
            if (keys.length === 0) {
                showToast(currentSelectType === 'single' ? '请先选择尺码' : '请先选择尺码和数量');
                return;
            }

            // 检查登录状态
            if (!isLoggedIn || !currentUser) {
                closeProductSelectModal();
                switchLoginMode('login');
                openLoginModal();
                return;
            }

            // 检查收货地址（优先使用用户信息中的地址）
            var address = null;
            if (currentUser && currentUser.address) {
                address = currentUser.address;
            }
            if (!address || !address.name || !address.phone || !address.detail) {
                openAddressPromptModal();
                return;
            }

            if (!currentProductSelectProduct) return;
            var cart = getCart();
            for (var i = 0; i < keys.length; i++) {
                var size = keys[i];
                var quantity = qtys[size];
                var existing = cart.find(function(it) { return it.id === currentProductSelectProduct.id && (it.size || '') === size; });
                if (existing) {
                    existing.quantity += quantity;
                } else {
                    cart.push({
                        id: currentProductSelectProduct.id,
                        name: currentProductSelectProduct.name,
                        price: Number(currentProductSelectProduct.price),
                        image: currentProductSelectProduct.image,
                        size: size,
                        quantity: quantity
                    });
                }
            }
            saveCart(cart);
            showToast('已加入购物车');
            closeProductSelectModal();
        });
        productSelectModal && productSelectModal.addEventListener('click', function(e) {
            if (e.target === productSelectModal) closeProductSelectModal();
        });

        document.querySelectorAll('.product-select-type').forEach(function(btn) {
            btn.addEventListener('click', function() {
                document.querySelectorAll('.product-select-type').forEach(function(b) {
                    b.classList.remove('text-brand', 'border-brand', 'font-medium');
                    b.classList.add('text-gray-600', 'border-transparent');
                });
                btn.classList.add('text-brand', 'border-brand', 'font-medium');
                btn.classList.remove('text-gray-600', 'border-transparent');

                // 切换单买/批量模式
                currentSelectType = btn.dataset.type;
                if (currentProductSelectProduct) {
                    renderSizeList(currentProductSelectProduct, currentSelectType);
                    updateProductSelectTotal();
                }
            });
        });

        // ========== 事件委托 ==========
        // 页面加载时更新角标
        updateCartBadge();

        // 页面加载时更新已收藏按钮状态
        (function() {
            var favorites = JSON.parse(localStorage.getItem('xiaosen_favorites') || '[]');
            document.querySelectorAll('.favorite-btn').forEach(function(btn) {
                var id = Number(btn.dataset.id);
                if (favorites.some(function(f) { return f.id === id; })) {
                    btn.classList.add('text-red-500');
                    btn.querySelector('svg').setAttribute('fill', 'currentColor');
                }
            });
        })();

        // 全局事件委托 - 收藏按钮
        document.addEventListener('click', function(e) {
            var favBtn = e.target.closest('.favorite-btn');
            if (favBtn) {
                e.preventDefault();
                e.stopPropagation();
                var id = Number(favBtn.dataset.id);
                var name = favBtn.dataset.name;
                var price = favBtn.dataset.price;
                var image = favBtn.dataset.image;

                var favorites = JSON.parse(localStorage.getItem('xiaosen_favorites') || '[]');
                var exists = favorites.some(function(f) { return f.id === id; });

                if (exists) {
                    showToast('已收藏');
                    return;
                }

                favorites.unshift({
                    id: id,
                    name: name,
                    price: price,
                    image: image,
                    added_at: new Date().toLocaleString('zh-CN')
                });
                localStorage.setItem('xiaosen_favorites', JSON.stringify(favorites));

                favBtn.classList.add('text-red-500');
                favBtn.querySelector('svg').setAttribute('fill', 'currentColor');
                showToast('收藏成功');
            }
        });

        // 全局事件委托 - 绿色加购按钮打开规格选择弹窗
        document.addEventListener('click', function(e) {
            var btn = e.target.closest('.add-to-cart-btn');
            if (btn) {
                e.preventDefault();
                e.stopPropagation();
                var card = btn.closest('.product-card');
                var product = {
                    id: Number(btn.dataset.id),
                    name: btn.dataset.name,
                    price: btn.dataset.price,
                    image: btn.dataset.image
                };
                if (product.id && product.name && product.price) {
                    openProductSelectModal(product);
                    btn.classList.add('bg-green-700');
                    setTimeout(function() { btn.classList.remove('bg-green-700'); }, 300);
                }
            }
        });

        // 首页其他功能
        const currentPage = '<?= $page ?>';
        if (currentPage === 'home') {
            const searchInput = document.getElementById('searchInput');
            const productGrid = document.getElementById('productGrid');
            const noResults = document.getElementById('noResults');

            // ===== 滚动位置保存和恢复（仅限从商品详情页返回） =====
            const SCROLL_KEY = 'xiaosen_home_scroll';
            const FILTER_KEY = 'xiaosen_home_filter';
            const FROM_DETAIL_KEY = 'xiaosen_from_detail';

            // 页面加载后立即清除 URL 中的 tag 参数（必须在 initialTag 逻辑执行之前）
            (function clearTagParamImmediately() {
                try {
                    var url = new URL(window.location.href);
                    if (url.searchParams.has('tag') && sessionStorage.getItem(FROM_DETAIL_KEY) === '1') {
                        url.searchParams.delete('tag');
                        window.history.replaceState({}, '', url.toString());
                    }
                } catch (_) {}
            })();

            // 保存滚动位置和筛选状态
            function saveScrollPosition() {
                sessionStorage.setItem(SCROLL_KEY, window.scrollY.toString());
                // 标记从详情页返回
                sessionStorage.setItem(FROM_DETAIL_KEY, '1');
                // 保存当前筛选状态
                const tab = (document.querySelector('.tab-btn.border-brand') || {}).dataset?.tab || 'all';
                const query = searchInput ? searchInput.value.trim() : '';
                const activeTag = document.querySelector('.tag.bg-orange-500');
                const tagFilter = activeTag ? activeTag.dataset.tag : '';
                sessionStorage.setItem(FILTER_KEY, JSON.stringify({ tab, query, tagFilter }));
            }

            // 恢复滚动位置和筛选状态
            function restoreScrollPosition() {
                const savedScroll = sessionStorage.getItem(SCROLL_KEY);
                const savedFilter = sessionStorage.getItem(FILTER_KEY);
                const fromDetail = sessionStorage.getItem(FROM_DETAIL_KEY) === '1';

                // 刷新/首次进入：不恢复滚动位置，强制回到顶部
                try {
                    const nav = performance.getEntriesByType('navigation')[0];
                    if (nav && nav.type === 'reload') {
                        sessionStorage.removeItem(FROM_DETAIL_KEY);
                        window.scrollTo(0, 0);
                        return;
                    }
                } catch (_) {}

                if (savedFilter) {
                    try {
                        const { tab, query, tagFilter } = JSON.parse(savedFilter);

                        // 恢复搜索框内容
                        if (searchInput && query) {
                            searchInput.value = query;
                        }

                        // 恢复标签筛选
                        if (tagFilter) {
                            var tagEl = document.querySelector('.tag[data-tag="' + tagFilter + '"]');
                            if (tagEl) {
                                var wrap = tagEl.closest('[data-tag-wrap]');
                                wrap && wrap.querySelectorAll('.tag').forEach(function(t) {
                                    t.classList.remove('bg-orange-500', 'text-white');
                                    t.classList.add('bg-gray-100', 'text-gray-600');
                                });
                                tagEl.classList.add('bg-orange-500', 'text-white');
                                tagEl.classList.remove('bg-gray-100', 'text-gray-600');
                            }
                        }

                        // 恢复标签页
                        if (tab && tab !== 'all') {
                            const tabBtn = document.querySelector('.tab-btn[data-tab="' + tab + '"]');
                            if (tabBtn) {
                                document.querySelectorAll('.tab-btn').forEach(function(b) {
                                    b.classList.remove('text-brand', 'border-brand', 'font-medium');
                                    b.classList.add('text-gray-500');
                                });
                                tabBtn.classList.add('text-brand', 'font-medium', 'border-b-2', 'border-brand', 'pb-2', '-mb-px');
                                tabBtn.classList.remove('text-gray-500');
                            }
                        }

                        // 应用筛选
                        setTimeout(function() {
                            applyFilters(tagFilter);
                            if (tagFilter) {
                                showClearFilterBtn(tagFilter);
                            }
                        }, 0);
                    } catch (e) {
                        console.error('恢复筛选状态失败:', e);
                    }
                }

                // 恢复滚动位置
                if (fromDetail && savedScroll) {
                    setTimeout(function() {
                        window.scrollTo(0, parseInt(savedScroll, 10));
                    }, 100);
                    // 用一次就清掉，避免之后刷新也恢复
                    sessionStorage.removeItem(FROM_DETAIL_KEY);
                } else {
                    // 非“从详情返回”的进入方式：回到顶部
                    window.scrollTo(0, 0);
                }
            }

            // 监听点击商品链接时保存位置（使用事件委托）
            document.addEventListener('click', function(e) {
                var productLink = e.target.closest('.product-card > a');
                if (productLink) {
                    saveScrollPosition();
                }
            });

            // 页面加载时恢复位置
            restoreScrollPosition();
            // ===== 滚动位置保存和恢复结束 =====

            const productsData = <?= json_encode(array_map(function ($p) {
                $firstImg = is_array($p['image'] ?? null) ? ($p['image'][0] ?? '') : (strpos($p['image'] ?? '', ',') !== false ? trim(explode(',', $p['image'])[0]) : ($p['image'] ?? ''));
                return ['id' => $p['id'], 'name' => $p['name'], 'price' => $p['price'], 'image' => $firstImg, 'tags' => $p['tags'], 'is_new' => $p['is_new'], 'jump_tag' => $p['jump_tag'] ?? ''];
            }, $products)) ?>;
            
            const cards = productGrid ? productGrid.querySelectorAll('.product-card') : [];

            function matchProduct(card, query, tab, tagFilter) {
                const id = parseInt(card.dataset.id, 10);
                const prod = productsData.find(function(p) { return p.id === id; });
                if (!prod) return false;
                const nameMatch = !query || prod.name.toLowerCase().indexOf(query.toLowerCase()) >= 0;
                // 同时匹配 tags 和 jump_tag
                const tagMatch = !tagFilter || (prod.tags && prod.tags.indexOf(tagFilter) >= 0) || (prod.jump_tag && prod.jump_tag.indexOf(tagFilter) >= 0);
                let tabMatch = true;
                if (tab === 'new') tabMatch = prod.is_new === true;
                if (tab === 'video' || tab === 'gallery') tabMatch = false;
                return nameMatch && tagMatch && tabMatch;
            }

            function applyFilters(tagFilter) {
                const query = (searchInput && searchInput.value) ? searchInput.value.trim() : '';
                const tab = (document.querySelector('.tab-btn.border-brand') || {}).dataset?.tab || 'all';
                let visible = 0;
                cards.forEach(function(card) {
                    const show = matchProduct(card, query, tab, tagFilter);
                    card.classList.toggle('hidden', !show);
                    if (show) visible++;
                });
                if (noResults) noResults.classList.toggle('hidden', visible > 0);
            }

            if (searchInput) {
                searchInput.addEventListener('input', function() {
                    applyFilters('');
                });
            }

            const tabNav = document.getElementById('tabNav');
            if (tabNav) {
                tabNav.addEventListener('click', function(e) {
                    const btn = e.target.closest('.tab-btn');
                    if (!btn) return;
                    tabNav.querySelectorAll('.tab-btn').forEach(function(b) {
                        b.classList.remove('text-brand', 'border-brand', 'font-medium');
                        b.classList.add('text-gray-500');
                    });
                    btn.classList.add('text-brand', 'font-medium', 'border-b-2', 'border-brand', 'pb-2', '-mb-px');
                    btn.classList.remove('text-gray-500');
                    applyFilters('');
                });
            }

            // URL标签筛选（从分类页跳转过来时生效）
            const initialTag = '<?= htmlspecialchars($initialTag) ?>';
            const clearFilterBtn = document.getElementById('clearFilterBtn');

            // 检查是否需要恢复保存的筛选状态
            function shouldRestoreFilter() {
                const savedFilter = sessionStorage.getItem(FILTER_KEY);
                const fromDetail = sessionStorage.getItem(FROM_DETAIL_KEY) === '1';
                return fromDetail && savedFilter;
            }

            // ===== 分类标签（已移除 UI，保留变量供其它逻辑使用） =====
            const categoryTabs = null;
            const seriesSlugs = <?= json_encode($allSeriesSlugs) ?>;

            // 滚动到商品网格区域
            function scrollToProductGrid() {
                var productGrid = document.getElementById('productGrid');
                if (productGrid) {
                    setTimeout(function() {
                        productGrid.scrollIntoView({ behavior: 'smooth', block: 'start' });
                    }, 300);
                }
            }

            // 处理热门型号跳转按钮点击
            document.querySelectorAll('.jump-tag-btn').forEach(function(btn) {
                btn.addEventListener('click', function(e) {
                    // 这是 <a> 链接：不阻止默认行为会导致页面跳转刷新
                    e.preventDefault();
                    var tag = this.dataset.tag;
                    if (tag) {
                        // 更新分类标签高亮
                        if (categoryTabs) {
                            categoryTabs.querySelectorAll('.category-tab-btn').forEach(function(b) {
                                b.classList.remove('bg-gray-800', 'text-white');
                                b.classList.add('bg-gray-100', 'text-gray-600');
                            });
                        }
                        // 触发筛选
                        applyFilters(tag);
                        showClearFilterBtn(tag);
                        // 滚动到对应系列
                        scrollToProductGrid();

                        // 可选：同步 URL 参数但不刷新（方便分享/返回）
                        try {
                            var url = new URL(window.location.href);
                            url.searchParams.set('page', 'home');
                            url.searchParams.set('tag', tag);
                            window.history.replaceState({}, '', url.toString());
                        } catch (_) {}
                    }
                });
            });

            function showClearFilterBtn(tagFilter) {
                if (clearFilterBtn) {
                    clearFilterBtn.classList.toggle('hidden', !tagFilter);
                }
            }

            if (initialTag && !shouldRestoreFilter()) {
                setTimeout(function() {
                    var tagEl = document.querySelector('.tag[data-tag="' + initialTag + '"]');
                    if (tagEl) {
                        var wrap = tagEl.closest('[data-tag-wrap]');
                        wrap && wrap.querySelectorAll('.tag').forEach(function(t) {
                            t.classList.remove('bg-orange-500', 'text-white');
                            t.classList.add('bg-gray-100', 'text-gray-600');
                        });
                        tagEl.classList.add('bg-orange-500', 'text-white');
                        tagEl.classList.remove('bg-gray-100', 'text-gray-600');
                        applyFilters(initialTag);
                        showClearFilterBtn(initialTag);
                        // 滚动到商品网格
                        scrollToProductGrid();
                    }
                }, 0);
            }

            // 清除筛选按钮点击事件
            if (clearFilterBtn) {
                clearFilterBtn.addEventListener('click', function() {
                    // 清除所有标签的高亮状态
                    document.querySelectorAll('.tag').forEach(function(t) {
                        t.classList.remove('bg-orange-500', 'text-white');
                        t.classList.add('bg-gray-100', 'text-gray-600');
                    });
                    // 重置分类标签状态
                    if (categoryTabs) {
                        categoryTabs.querySelectorAll('.category-tab-btn').forEach(function(b) {
                            b.classList.remove('bg-gray-800', 'text-white');
                            b.classList.add('bg-gray-100', 'text-gray-600');
                        });
                        var allBtn = categoryTabs.querySelector('.category-tab-btn[data-section=""]');
                        if (allBtn) {
                            allBtn.classList.remove('bg-gray-100', 'text-gray-600');
                            allBtn.classList.add('bg-gray-800', 'text-white');
                        }
                    }
                    // 重置筛选
                    applyFilters('');
                    showClearFilterBtn('');
                    // 清除URL参数
                    if (window.history.replaceState) {
                        var url = window.location.href.split('?')[0];
                        window.history.replaceState({}, '', url);
                    }
                });
            }

            // 回到顶部
            var backToTopBtn = document.getElementById('backToTopBtn');
            if (backToTopBtn) {
                backToTopBtn.addEventListener('click', function() {
                    window.scrollTo({ top: 0, behavior: 'smooth' });
                });
            }

            // 没找到商品按钮点击
            var notFoundBtn = document.getElementById('notFoundBtn');
            var notFoundModal = document.getElementById('notFoundModal');
            var notFoundModalClose = document.getElementById('notFoundModalClose');
            var copyWechatBtn = document.getElementById('copyWechatBtn');

            if (notFoundBtn && notFoundModal) {
                notFoundBtn.addEventListener('click', function() {
                    notFoundModal.showModal();
                });
            }

            if (notFoundModalClose) {
                notFoundModalClose.addEventListener('click', function() {
                    notFoundModal.close();
                });
            }

            if (notFoundModal) {
                notFoundModal.addEventListener('click', function(e) {
                    if (e.target === notFoundModal) notFoundModal.close();
                });
            }

            if (copyWechatBtn) {
                copyWechatBtn.addEventListener('click', function() {
                    navigator.clipboard.writeText('INKT_Love').then(function() {
                        showToast('微信号已复制');
                    }).catch(function() {
                        showToast('复制失败，请手动复制');
                    });
                });
            }
        } else if (currentPage === 'cart') {
            // 购物车页面
            updateCartBadge();

            const cartEmpty = document.getElementById('cartEmpty');
            const cartHasItems = document.getElementById('cartHasItems');
            const cartList = document.getElementById('cartList');
            const cartTotal = document.getElementById('cartTotal');
            const checkoutBtn = document.getElementById('checkoutBtn');

            function renderCart() {
                const cart = getCart();
                if (cart.length === 0) {
                    cartEmpty && cartEmpty.classList.remove('hidden');
                    cartHasItems && cartHasItems.classList.add('hidden');
                    var totalQtyEl = document.getElementById('cartTotalQty');
                    if (totalQtyEl) totalQtyEl.textContent = '0';
                } else {
                    cartEmpty && cartEmpty.classList.add('hidden');
                    cartHasItems && cartHasItems.classList.remove('hidden');

                    let total = 0;
                    let totalQty = 0;
                    cartList && (cartList.innerHTML = cart.map(function(item) {
                        var qty = item.quantity || 1;
                        total += item.price * qty;
                        totalQty += qty;
                        var itemSize = item.size !== undefined ? item.size : '';
                        return '<li class="flex items-center gap-3 p-3" data-id="' + item.id + '" data-size="' + itemSize + '">' +
                            '<img src="' + imageUrl(item.image || '') + '" alt="" class="w-20 h-20 object-cover rounded-lg bg-gray-100">' +
                            '<div class="flex-1 min-w-0">' +
                            '<h3 class="text-sm font-medium text-black truncate">' + (item.name || '') + '</h3>' +
                            '<p class="text-xs text-gray-500 mt-0.5">尺码: ' + (itemSize || '默认') + '</p>' +
                            '<p class="text-red-600 font-bold mt-1">¥' + (item.price || 0) + '</p>' +
                            '<div class="flex items-center gap-2 mt-2">' +
                            '<button type="button" class="qty-btn w-7 h-7 flex items-center justify-center rounded border border-gray-300 text-gray-600" data-action="dec">-</button>' +
                            '<span class="text-sm w-6 text-center qty-display">' + qty + '</span>' +
                            '<button type="button" class="qty-btn w-7 h-7 flex items-center justify-center rounded border border-gray-300 text-gray-600" data-action="inc">+</button>' +
                            '<button type="button" class="remove-btn ml-auto text-red-500 text-sm">删除</button>' +
                            '</div></div></li>';
                    }).join(''));
                    cartTotal && (cartTotal.textContent = '¥' + total);
                    var totalQtyEl = document.getElementById('cartTotalQty');
                    if (totalQtyEl) totalQtyEl.textContent = totalQty;
                }
            }

            if (cartList) {
                cartList.addEventListener('click', function(e) {
                    const qtyBtn = e.target.closest('.qty-btn');
                    const removeBtn = e.target.closest('.remove-btn');

                    if (qtyBtn) {
                        e.preventDefault();
                        const li = qtyBtn.closest('li');
                        const id = parseInt(li.dataset.id, 10);
                        const size = li.dataset.size || '';
                        const action = qtyBtn.dataset.action;
                        let cart = getCart();
                        const item = cart.find(function(it) { return it.id === id && (it.size || '') === size; });
                        if (item) {
                            if (action === 'inc') {
                                item.quantity = (item.quantity || 1) + 1;
                            } else if (action === 'dec') {
                                if (item.quantity > 1) {
                                    item.quantity = item.quantity - 1;
                                } else {
                                    if (confirm('确定要删除这件商品吗？')) {
                                        cart = cart.filter(function(it) { return !(it.id === id && (it.size || '') === size); });
                                        saveCart(cart);
                                        renderCart();
                                        return;
                                    }
                                }
                            }
                            saveCart(cart);
                            renderCart();
                        }
                        return;
                    }

                    if (removeBtn) {
                        e.preventDefault();
                        const li = removeBtn.closest('li');
                        const id = parseInt(li.dataset.id, 10);
                        const size = li.dataset.size || '';
                        let cart = getCart();
                        cart = cart.filter(function(it) { return !(it.id === id && (it.size || '') === size); });
                        saveCart(cart);
                        renderCart();
                    }
                });
            }

            if (checkoutBtn) {
                checkoutBtn.addEventListener('click', function() {
                    var cart = getCart();
                    if (cart.length === 0) {
                        showToast('购物车是空的');
                        return;
                    }

                    // 检查登录状态
                    if (!isLoggedIn || !currentUser) {
                        switchLoginMode('login');
                        openLoginModal();
                        return;
                    }

                    var total = 0;
                    for (var i = 0; i < cart.length; i++) {
                        total += Number(cart[i].price || 0) * Number(cart[i].quantity || 1);
                    }
                    var paymentModal = document.getElementById('paymentModal');
                    var paymentAmount = document.getElementById('paymentAmount');
                    if (paymentAmount) paymentAmount.textContent = '¥' + total.toLocaleString();
                    if (typeof setPaymentMethod === 'function') setPaymentMethod('wechat');
                    if (paymentModal) paymentModal.showModal();
                });
            }

            renderCart();
        }

        // 监听跨页面购物车变化
        window.addEventListener('storage', function(e) {
            if (e.key === 'xiaosen_cart') {
                updateCartBadge();
            }
        });

        // ========== 付款弹窗逻辑 ==========
        var paymentModal = document.getElementById('paymentModal');
        var paymentConfirmBtn = document.getElementById('paymentConfirmBtn');
        var paymentModalClose = document.getElementById('paymentModalClose');
        var paymentQrImage = document.getElementById('paymentQrImage');
        var paymentQrHint = document.getElementById('paymentQrHint');
        var payMethodWechat = document.getElementById('payMethodWechat');
        var payMethodAlipay = document.getElementById('payMethodAlipay');
        var currentPayMethod = 'wechat';
        var PAY_QR = { wechat: 'assets/wechat-pay-qr.png', alipay: 'assets/alipay-pay-qr.png' };
        var PAY_HINT = { wechat: '请使用微信扫描上方二维码完成支付', alipay: '请使用支付宝扫描上方二维码完成支付' };

        function setPaymentMethod(method) {
            currentPayMethod = method;
            if (paymentQrImage) paymentQrImage.src = PAY_QR[method] || PAY_QR.wechat;
            if (paymentQrHint) paymentQrHint.textContent = PAY_HINT[method] || PAY_HINT.wechat;
            if (payMethodWechat) {
                payMethodWechat.classList.toggle('border-green-500', method === 'wechat');
                payMethodWechat.classList.toggle('bg-green-50', method === 'wechat');
                payMethodWechat.classList.toggle('text-green-700', method === 'wechat');
                payMethodWechat.classList.toggle('border-gray-200', method !== 'wechat');
                payMethodWechat.classList.toggle('bg-gray-50', method !== 'wechat');
                payMethodWechat.classList.toggle('text-gray-600', method !== 'wechat');
            }
            if (payMethodAlipay) {
                payMethodAlipay.classList.toggle('border-blue-500', method === 'alipay');
                payMethodAlipay.classList.toggle('bg-blue-50', method === 'alipay');
                payMethodAlipay.classList.toggle('text-blue-700', method === 'alipay');
                payMethodAlipay.classList.toggle('border-gray-200', method !== 'alipay');
                payMethodAlipay.classList.toggle('bg-gray-50', method !== 'alipay');
                payMethodAlipay.classList.toggle('text-gray-600', method !== 'alipay');
            }
        }

        if (payMethodWechat) payMethodWechat.addEventListener('click', function() { setPaymentMethod('wechat'); });
        if (payMethodAlipay) payMethodAlipay.addEventListener('click', function() { setPaymentMethod('alipay'); });

        // ========== 分享弹窗逻辑 ==========
        var shareModal = document.getElementById('shareModal');
        var shareModalClose = document.getElementById('shareModalClose');
        var shareQrImage = document.getElementById('shareQrImage');
        var SHARE_URL = 'https://xscw.inktandwkx.love:1145';

        if (shareQrImage) {
            shareQrImage.src = 'https://api.qrserver.com/v1/create-qr-code/?size=200x200&data=' + encodeURIComponent(SHARE_URL);
        }

        if (document.getElementById('shareBtn')) {
            document.getElementById('shareBtn').addEventListener('click', function() {
                if (shareModal) shareModal.showModal();
            });
        }

        function closeShareModal() {
            if (shareModal) shareModal.close();
        }

        if (shareModalClose) {
            shareModalClose.addEventListener('click', closeShareModal);
        }

        if (shareModal) {
            shareModal.addEventListener('click', function(e) {
                if (e.target === shareModal) closeShareModal();
            });
        }

        // ========== 收货地址提示弹窗逻辑 ==========
        var addressPromptModal = document.getElementById('addressPromptModal');
        var addressPromptGoBtn = document.getElementById('addressPromptGoBtn');

        function openAddressPromptModal() {
            if (addressPromptModal) addressPromptModal.showModal();
        }

        function closeAddressPromptModal() {
            if (addressPromptModal) addressPromptModal.close();
        }

        if (addressPromptGoBtn) {
            addressPromptGoBtn.addEventListener('click', function() {
                closeAddressPromptModal();
                window.location.href = '?page=me';
            });
        }

        if (addressPromptModal) {
            addressPromptModal.addEventListener('click', function(e) {
                if (e.target === addressPromptModal) closeAddressPromptModal();
            });
        }

        function closePaymentModal() {
            if (paymentModal) paymentModal.close();
        }

        if (paymentModalClose) {
            paymentModalClose.addEventListener('click', closePaymentModal);
        }

        // 支付审核弹窗关闭
        var paymentVerifyModalClose = document.getElementById('paymentVerifyModalClose');
        var paymentVerifyModal = document.getElementById('paymentVerifyModal');
        if (paymentVerifyModalClose && paymentVerifyModal) {
            paymentVerifyModalClose.addEventListener('click', function() {
                paymentVerifyModal.close();
            });
            paymentVerifyModal.addEventListener('click', function(e) {
                if (e.target === paymentVerifyModal) paymentVerifyModal.close();
            });
        }

        if (paymentConfirmBtn) {
            paymentConfirmBtn.addEventListener('click', function() {
                // 检查是否有直接购买的订单
                var pendingOrderJson = sessionStorage.getItem('xiaosen_pending_order');
                var isDirectBuy = !!pendingOrderJson;
                var orderItems = [];

                if (isDirectBuy) {
                    // 直接购买：使用临时订单
                    try {
                        orderItems = JSON.parse(pendingOrderJson);
                    } catch (e) {
                        orderItems = [];
                    }
                } else {
                    // 购物车购买
                    var cart = getCart();
                    if (cart.length === 0) {
                        showToast('购物车是空的');
                        return;
                    }
                    orderItems = cart;
                }

                if (orderItems.length === 0) {
                    showToast('订单为空');
                    return;
                }

                var _this = this;
                _this.disabled = true;
                _this.textContent = '处理中...';

                var xhr = new XMLHttpRequest();
                xhr.open('POST', 'api/create_order.php', true);
                xhr.setRequestHeader('Content-Type', 'application/json');
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4) {
                        _this.disabled = false;
                        _this.textContent = '完成支付';

                        var response;
                        try {
                            response = JSON.parse(xhr.responseText);
                        } catch (e) {
                            response = { success: false, message: '创建订单失败' };
                        }

                        if (response.success) {
                            if (isDirectBuy) {
                                // 直接购买成功，清除临时订单
                                sessionStorage.removeItem('xiaosen_pending_order');
                            } else {
                                // 购物车购买成功，清空购物车
                                localStorage.setItem('xiaosen_cart', JSON.stringify([]));
                                updateCartBadge();
                            }
                            closePaymentModal();
                            // 显示审核提示弹窗
                            var paymentVerifyModal = document.getElementById('paymentVerifyModal');
                            if (paymentVerifyModal) {
                                paymentVerifyModal.showModal();
                            }
                            // 刷新购物车
                            if (typeof renderCart === 'function') {
                                renderCart();
                            }
                        } else {
                            showToast(response.message || '创建订单失败');
                        }
                    }
                };
                var savedAddress = null;
                if (currentUser && currentUser.address) {
                    savedAddress = currentUser.address;
                }
                if (!savedAddress) {
                    try {
                        savedAddress = JSON.parse(localStorage.getItem('xiaosen_address') || 'null');
                    } catch (e) {
                        savedAddress = null;
                    }
                }

                var customerName = (savedAddress && savedAddress.name) ? savedAddress.name : '游客';

                var payload = {
                    items: orderItems,
                    customer: {
                        name: customerName,
                        username: currentUser ? currentUser.username : 'guest',
                        phone: savedAddress && savedAddress.phone ? savedAddress.phone : '',
                        address: savedAddress && savedAddress.detail ? savedAddress.detail : ''
                    },
                    payment_type: currentPayMethod || 'wechat'
                };
                xhr.send(JSON.stringify(payload));
            });
        }

        if (paymentModal) {
            paymentModal.addEventListener('click', function(e) {
                if (e.target === paymentModal) closePaymentModal();
            });
        }
    })();
    </script>
</body>
</html>
