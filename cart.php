<header class="sticky top-0 z-10 bg-white border-b border-gray-100">
    <h1 class="text-center text-lg font-bold py-3 text-black">购物车</h1>
</header>
<main class="flex-1 pb-20">
    <div id="cartEmpty" class="flex flex-col items-center justify-center py-16 px-6">
        <div class="w-40 h-40 flex items-center justify-center text-gray-300">
            <svg class="w-full h-full" viewBox="0 0 120 120" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
                <path d="M20 45 L20 85 L100 85 L100 45 Z" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M35 45 L35 30 L50 30 L50 45" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <path d="M70 45 L70 30 L85 30 L85 45" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="60" cy="62" r="8" stroke="currentColor" stroke-width="1.5" fill="none"/>
                <path d="M56 62 L58 64 L64 58" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                <circle cx="55" cy="50" r="2" fill="currentColor" opacity="0.6"/>
                <circle cx="72" cy="48" r="2" fill="currentColor" opacity="0.6"/>
            </svg>
        </div>
        <p class="mt-6 text-gray-600 text-center">购物车是空的哦~</p>
    </div>
    <div id="cartHasItems" class="hidden pb-4">
        <ul id="cartList" class="divide-y divide-gray-100 bg-white"></ul>
        <div class="sticky bottom-20 bg-white border-t border-gray-200 px-4 py-3">
            <div class="flex items-center justify-between mb-2">
                <span class="text-gray-500 text-sm">共 <span id="cartTotalQty" class="text-black font-medium">0</span> 件商品</span>
            </div>
            <div class="flex items-center justify-between">
                <span class="font-bold">合计：<span id="cartTotal" class="text-red-600 text-lg">¥0</span></span>
                <button type="button" id="checkoutBtn" class="px-6 py-2.5 bg-orange-500 text-white rounded-lg font-medium">去结算</button>
            </div>
        </div>
    </div>
</main>
