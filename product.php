<?php
$p = $detailProduct;

// 支持多图：优先用 images 数组，其次用 image 字段（逗号分隔的多图或单图）
$imageField = $p['image'] ?? '';
if (isset($p['images']) && is_array($p['images']) && count($p['images']) > 0) {
    $images = $p['images'];
} elseif (!empty($imageField) && strpos($imageField, ',') !== false) {
    // 逗号分隔的多图
    $imageList = array_map('trim', explode(',', $imageField));
    $images = array_map(function ($url) use ($p) {
        return ['url' => $url, 'alt' => $p['name'] ?? ''];
    }, $imageList);
} else {
    // 单图
    $images = [['url' => $imageField, 'alt' => $p['name'] ?? '']];
}

// 兼容旧格式：images 可能是字符串数组
if (!empty($images) && is_string($images[0] ?? '')) {
    $images = array_map(function ($url) use ($p) {
        return ['url' => $url, 'alt' => $p['name'] ?? ''];
    }, $images);
}
$description = $p['description'] ?? '';
?>
<header class="sticky top-0 z-20 bg-white/95 backdrop-blur border-b border-gray-100 flex items-center gap-2 px-3 py-2">
    <a href="?page=home" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-gray-100 text-gray-600" aria-label="返回">
        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/></svg>
    </a>
    <h1 class="flex-1 text-center text-lg font-bold text-black truncate pr-10">商品详情</h1>
</header>
<main class="flex-1 pb-40">
    <!-- 商品主图/轮播图 -->
    <section class="bg-white">
        <div id="productDetailGallery" class="relative w-full overflow-hidden touch-pan-y">
            <div class="flex transition-transform duration-300 ease-out" id="gallerySlides">
                <?php foreach ($images as $i => $img): ?>
                    <?php $url = image_url(is_array($img) ? ($img['url'] ?? '') : $img); ?>
                    <div class="product-detail-image w-full flex-shrink-0 aspect-square bg-gray-100" data-index="<?= $i ?>">
                        <img src="<?= htmlspecialchars($url) ?>" alt="<?= htmlspecialchars(is_array($img) ? ($img['alt'] ?? $p['name']) : $p['name']) ?>" class="w-full h-full object-contain" draggable="false">
                    </div>
                <?php endforeach; ?>
            </div>
            <?php if (count($images) > 1): ?>
                <div class="absolute bottom-3 right-3 bg-black/50 text-white text-xs px-2 py-1 rounded-full" id="galleryCounter">
                    <span id="currentSlide">1</span>/<?= count($images) ?>
                </div>
                <div class="absolute bottom-3 left-0 right-0 flex justify-center gap-1.5 pointer-events-none">
                    <?php foreach ($images as $i => $img): ?>
                        <span class="gallery-dot w-2 h-2 rounded-full transition-colors <?= $i === 0 ? 'bg-white' : 'bg-white/40' ?>" data-index="<?= $i ?>"></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <!-- 名称、价格、标签 -->
    <section class="bg-white mt-2 px-4 py-4">
        <h2 class="text-lg font-bold text-black leading-snug"><?= htmlspecialchars($p['name']) ?></h2>
        <p class="mt-2 text-red-600 font-bold text-xl">¥<?= number_format((int)($p['price'] ?? 0)) ?></p>
        <?php if (!empty($p['tags'])): ?>
            <div class="flex flex-wrap gap-2 mt-3">
                <?php foreach ($p['tags'] as $tag): ?>
                    <span class="text-xs px-2.5 py-1 rounded-full bg-gray-100 text-gray-600"><?= htmlspecialchars($tag) ?></span>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </section>

    <!-- 商品详情（文字介绍） -->
    <section class="bg-white mt-2 px-4 py-4 border-t border-gray-100">
        <h3 class="text-sm font-medium text-gray-500 mb-2">商品介绍</h3>
        <div class="text-sm text-gray-700 whitespace-pre-wrap"><?= $description !== '' ? nl2br(htmlspecialchars($description)) : '<span class="text-gray-400">暂无文字介绍</span>' ?></div>
    </section>
    <!-- 底部留白，避免被固定栏遮挡 -->
    <div class="h-24 bg-white"></div>
</main>

<!-- 底部加购栏（在底部导航上方） -->
<div class="fixed left-0 right-0 mx-auto bg-white border-t border-gray-200 p-4 z-20" style="max-width: 640px; bottom: 56px;">
    <div class="flex items-center gap-3">
        <button type="button"
            class="add-to-cart-btn flex-1 py-3 rounded-xl bg-green-600 text-white font-medium hover:bg-green-700 active:bg-green-800"
            data-id="<?= (int)$p['id'] ?>"
            data-name="<?= htmlspecialchars($p['name']) ?>"
            data-price="<?= (int)($p['price'] ?? 0) ?>"
            data-image="<?= htmlspecialchars($p['image'] ?? '') ?>">
            加入购物车
        </button>
    </div>
</div>

<script>
(function() {
    var gallery = document.getElementById('productDetailGallery');
    var slidesContainer = document.getElementById('gallerySlides');
    if (!gallery || !slidesContainer) return;
    var slides = slidesContainer.querySelectorAll('.product-detail-image');
    var dots = gallery.querySelectorAll('.gallery-dot');
    var counter = document.getElementById('currentSlide');
    if (slides.length <= 1) return;
    var current = 0;
    var isDragging = false;
    var startX = 0;
    var currentX = 0;
    function updateDots() {
        dots.forEach(function(el, j) {
            el.classList.toggle('bg-white', j === current);
            el.classList.toggle('bg-white/40', j !== current);
        });
        if (counter) counter.textContent = current + 1;
    }
    function goTo(i, animate) {
        // 循环：最后一张往后翻到第一张，第一张往前翻到最后一张
        if (i < 0) i = slides.length - 1;
        if (i >= slides.length) i = 0;
        current = i;
        var translateX = -i * 100;
        slidesContainer.style.transition = animate ? 'transform 0.3s ease-out' : 'none';
        slidesContainer.style.transform = 'translateX(' + translateX + '%)';
        updateDots();
    }
    dots.forEach(function(dot) {
        dot.addEventListener('click', function() {
            var i = parseInt(dot.getAttribute('data-index'), 10);
            if (!isNaN(i)) goTo(i, true);
        });
    });
    // 触摸滑动
    gallery.addEventListener('touchstart', function(e) {
        startX = e.touches[0].clientX;
        isDragging = true;
    }, { passive: true });
    gallery.addEventListener('touchmove', function(e) {
        if (!isDragging) return;
        currentX = e.touches[0].clientX;
        var deltaX = currentX - startX;
        var percent = (deltaX / gallery.offsetWidth) * 100;
        var translateX = -current * 100 + percent;
        slidesContainer.style.transition = 'none';
        slidesContainer.style.transform = 'translateX(' + translateX + '%)';
    }, { passive: true });
    gallery.addEventListener('touchend', function(e) {
        if (!isDragging) return;
        isDragging = false;
        var deltaX = startX - currentX;
        if (Math.abs(deltaX) > 50) {
            if (deltaX > 0) goTo(current + 1, true); // 往左滑，下一张（循环）
            else goTo(current - 1, true); // 往右滑，上一张（循环）
        } else {
            goTo(current, true);
        }
    });
    // 鼠标拖动（桌面端）
    gallery.addEventListener('mousedown', function(e) {
        startX = e.clientX;
        isDragging = true;
    });
    gallery.addEventListener('mousemove', function(e) {
        if (!isDragging) return;
        currentX = e.clientX;
        var deltaX = currentX - startX;
        var percent = (deltaX / gallery.offsetWidth) * 100;
        var translateX = -current * 100 + percent;
        slidesContainer.style.transition = 'none';
        slidesContainer.style.transform = 'translateX(' + translateX + '%)';
    });
    gallery.addEventListener('mouseup', function(e) {
        if (!isDragging) return;
        isDragging = false;
        var deltaX = startX - currentX;
        if (Math.abs(deltaX) > 50) {
            if (deltaX > 0) goTo(current + 1, true);
            else goTo(current - 1, true);
        } else {
            goTo(current, true);
        }
    });
    gallery.addEventListener('mouseleave', function() {
        if (isDragging) {
            isDragging = false;
            goTo(current, true);
        }
    });
})();
</script>
