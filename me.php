<main class="flex-1 pb-20">
    <div id="profileHeader" class="bg-gradient-to-br from-orange-400 to-orange-600 rounded-b-2xl px-4 pt-6 pb-8 mx-4 mt-0 cursor-pointer">
        <div class="flex items-start gap-4">
            <img id="profileAvatar" src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=100" alt="" class="w-16 h-16 rounded-full object-cover border-2 border-white shadow">
            <div class="flex-1 min-w-0">
                <p id="profileName" class="font-bold text-black text-lg">小森的潮物</p>
                <div id="profileAddress" class="mt-2 text-sm text-gray-800 opacity-90">
                    <span class="text-xs bg-white/30 px-2 py-0.5 rounded">收货地址</span>
                    <span id="addressDisplay" class="block mt-1">点击填写收货地址</span>
                </div>
            </div>
            <svg class="w-5 h-5 text-gray-600 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
        </div>
    </div>
    <section class="bg-white mx-4 rounded-xl shadow-sm mt-4 overflow-hidden">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <h2 class="font-medium text-black">我买的</h2>
            <a href="javascript:void(0)" id="orderBackBtn" class="text-gray-400 text-sm hidden">&lt; 返回</a>
        </div>
        <div class="grid grid-cols-3 py-4" id="orderTabs">
            <a href="javascript:void(0)" class="order-tab flex flex-col items-center text-gray-600" data-status="verifying">
                <span class="w-10 h-10 flex items-center justify-center mb-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <span class="text-xs">待验证</span>
            </a>
            <a href="javascript:void(0)" class="order-tab flex flex-col items-center text-gray-600" data-status="paid">
                <span class="w-10 h-10 flex items-center justify-center mb-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg></span>
                <span class="text-xs">待收货</span>
            </a>
            <a href="javascript:void(0)" class="order-tab flex flex-col items-center text-gray-600" data-status="completed">
                <span class="w-10 h-10 flex items-center justify-center mb-1"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>
                <span class="text-xs">已完成</span>
            </a>
        </div>
    </section>

    <div id="orderListSection" class="hidden">
        <div id="orderList" class="mt-2"></div>
    </div>

    <div id="couponListSection" class="hidden">
        <div id="couponList" class="mt-2 px-4"></div>
    </div>

    <div id="couponEmpty" class="hidden px-4 mt-8 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
        <p class="text-sm">暂无可用卡券</p>
    </div>

    <div id="orderEmpty" class="hidden px-4 mt-8 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
        <p class="text-sm">暂无订单</p>
    </div>

    <div id="favoritesSection" class="hidden">
        <div id="favoritesList" class="mt-2 px-4"></div>
    </div>

    <div id="favoritesEmpty" class="hidden px-4 mt-8 text-center text-gray-400">
        <svg class="w-16 h-16 mx-auto mb-3 opacity-50" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/></svg>
        <p class="text-sm">暂无收藏</p>
    </div>

    <section class="bg-white mx-4 rounded-xl shadow-sm mt-4 overflow-hidden">
        <a href="javascript:void(0)" id="couponLink" class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <span class="flex items-center gap-3"><span class="text-red-500"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M20 4H4c-1.1 0-2 .9-2 2v12c0 1.1.9 2 2 2h16c1.1 0 2-.9 2-2V6c0-1.1-.9-2-2-2zm0 14H4V6h16v12z"/></svg></span>我的卡券</span>
            <span>&gt;</span>
        </a>
        <a href="javascript:void(0)" id="favoritesLink" class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <span class="flex items-center gap-3"><span class="text-red-500"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/></svg></span>我的收藏</span>
            <span>&gt;</span>
        </a>
        <a href="javascript:void(0)" id="contactServiceLink" class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <span class="flex items-center gap-3"><span class="text-blue-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg></span>联系客服</span>
            <span>&gt;</span>
        </a>
        <a href="javascript:void(0)" id="disclaimerLink" class="flex items-center justify-between px-4 py-3 border-b border-gray-100">
            <span class="flex items-center gap-3"><span class="text-gray-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></span>免责声明</span>
            <span>&gt;</span>
        </a>
    </section>
    <div class="px-4 mt-4">
        <button type="button" id="clearTestDataBtn" class="w-full py-2 rounded-xl border border-red-300 text-red-500 text-sm hover:bg-red-50">清空数据</button>
    </div>
    <p class="text-center text-gray-400 text-xs py-8">墨羽科技提供技术支持</p>

    <!-- 编辑资料弹窗 -->
    <div id="profileModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-80 mx-4 p-6">
            <h3 class="text-lg font-bold text-center mb-4">编辑资料</h3>
            <div class="flex flex-col items-center mb-4">
                <div class="relative">
                    <img id="profileAvatarPreview" src="https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=100" alt="" class="w-20 h-20 rounded-full object-cover border-2 border-gray-200">
                    <label for="profileAvatarInput" class="absolute bottom-0 right-0 w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center text-white cursor-pointer hover:bg-orange-600">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                    </label>
                    <input type="file" id="profileAvatarInput" accept="image/*" class="hidden">
                </div>
                <p class="text-xs text-gray-400 mt-2">点击更换头像</p>
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-2">用户名</label>
                <input type="text" id="profileUsernameInput" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:outline-none" placeholder="请填写用户名">
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-2">昵称</label>
                <input type="text" id="profileNameInput" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:outline-none" placeholder="请填写您的微信名">
            </div>
            <div class="flex gap-3">
                <button type="button" id="cancelProfileBtn" class="flex-1 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50">取消</button>
                <button type="button" id="saveProfileBtn" class="flex-1 py-3 rounded-xl bg-orange-500 text-white hover:bg-orange-600">保存</button>
            </div>
        </div>
    </div>

    <!-- 收货地址弹窗 -->
    <div id="addressModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-80 mx-4 p-6">
            <h3 class="text-lg font-bold text-center mb-4">收货地址</h3>
            <div class="mb-3">
                <label class="block text-sm text-gray-600 mb-2">收货人</label>
                <input type="text" id="addressNameInput" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:outline-none" placeholder="请填写收货人姓名">
            </div>
            <div class="mb-3">
                <label class="block text-sm text-gray-600 mb-2">联系电话</label>
                <input type="tel" id="addressPhoneInput" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:outline-none" placeholder="请填写联系电话">
            </div>
            <div class="mb-4">
                <label class="block text-sm text-gray-600 mb-2">详细地址</label>
                <textarea id="addressDetailInput" class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:border-orange-500 focus:outline-none resize-none" rows="2" placeholder="请填写详细地址"></textarea>
            </div>
            <div class="flex gap-3">
                <button type="button" id="cancelAddressBtn" class="flex-1 py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50">取消</button>
                <button type="button" id="saveAddressBtn" class="flex-1 py-3 rounded-xl bg-orange-500 text-white hover:bg-orange-600">保存</button>
            </div>
        </div>
    </div>

    <!-- 联系客服弹窗 -->
    <div id="contactModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-80 mx-4 p-6">
            <h3 class="text-lg font-bold text-center mb-4">联系客服</h3>
            <p class="text-center text-gray-600 mb-4">添加微信请备注「买鞋」</p>
            <div class="flex items-center justify-center gap-2 mb-4">
                <span id="wechatId" class="text-xl font-mono text-gray-800">INKT_Love</span>
                <button type="button" id="copyWechatBtn" class="px-3 py-1.5 rounded-lg bg-orange-500 text-white text-sm hover:bg-orange-600">复制</button>
            </div>
            <button type="button" id="closeContactModalBtn" class="w-full py-3 rounded-xl border border-gray-300 text-gray-600 hover:bg-gray-50">关闭</button>
        </div>
    </div>

    <!-- 免责声明弹窗 -->
    <div id="disclaimerModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-2xl w-80 mx-4 p-6">
            <h3 class="text-lg font-bold text-center mb-4">免责声明</h3>
            <p class="text-center text-gray-600 mb-6">本店非质量问题不可退货哦~</p>
            <button type="button" id="closeDisclaimerModalBtn" class="w-full py-3 rounded-xl bg-orange-500 text-white hover:bg-orange-600">我知道了</button>
        </div>
    </div>
</main>

<script>
(function() {
    var currentStatus = '';
    var orderTabs = document.querySelectorAll('.order-tab');
    var orderListSection = document.getElementById('orderListSection');
    var orderList = document.getElementById('orderList');
    var orderEmpty = document.getElementById('orderEmpty');
    var orderBackBtn = document.getElementById('orderBackBtn');

    // ========== 编辑资料功能 ==========
    var profileHeader = document.getElementById('profileHeader');
    var profileAvatar = document.getElementById('profileAvatar');
    var profileName = document.getElementById('profileName');
    var profileModal = document.getElementById('profileModal');
    var profileAvatarPreview = document.getElementById('profileAvatarPreview');
    var profileAvatarInput = document.getElementById('profileAvatarInput');
    var profileUsernameInput = document.getElementById('profileUsernameInput');
    var profileNameInput = document.getElementById('profileNameInput');
    var cancelProfileBtn = document.getElementById('cancelProfileBtn');
    var saveProfileBtn = document.getElementById('saveProfileBtn');
    var currentAvatarData = null;

    // 获取用户信息（从 index.php 相同的存储中读取）
    function getStoredUser() {
        try {
            return JSON.parse(localStorage.getItem('xiaosen_user') || 'null');
        } catch (e) {
            return null;
        }
    }

    // 从服务器验证并同步用户信息
    function syncUserFromServer(callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'api/user/get_info.php', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success && response.user) {
                            // 使用服务器返回的最新用户信息更新本地存储
                            localStorage.setItem('xiaosen_user', JSON.stringify(response.user));
                            if (callback) callback(response.user);
                            return;
                        }
                    } catch (e) {}
                }
                // 服务器验证失败，清除本地用户信息
                localStorage.removeItem('xiaosen_user');
                if (callback) callback(null);
            }
        };
        xhr.send();
    }

    function loadProfile() {
        var user = getStoredUser();
        if (user) {
            if (user.avatar) {
                profileAvatar.src = user.avatar;
                profileAvatarPreview.src = user.avatar;
                currentAvatarData = user.avatar;
            }
            var displayName = user.nickname || user.username || '小森的潮物';
            profileName.textContent = displayName;
        } else {
            profileName.textContent = '未登录';
            profileAvatar.src = 'https://images.unsplash.com/photo-1543466835-00a7907e9de1?w=100';
        }
    }

    // 页面加载时从服务器同步用户信息
    syncUserFromServer(function(user) {
        loadProfile();
    });

    // 点击头像/昵称区域打开资料编辑弹窗（需要登录）
    profileHeader.addEventListener('click', function() {
        var user = getStoredUser();
        if (!user) {
            alert('请先登录');
            return;
        }
        profileUsernameInput.value = user && user.username ? user.username : '';
        profileNameInput.value = user && user.nickname ? user.nickname : '';
        profileAvatarPreview.src = currentAvatarData || profileAvatar.src;
        profileModal.classList.remove('hidden');
        profileModal.classList.add('flex');
    });

    profileAvatarInput.addEventListener('change', function(e) {
        var file = e.target.files[0];
        if (file) {
            var reader = new FileReader();
            reader.onload = function(event) {
                currentAvatarData = event.target.result;
                profileAvatarPreview.src = currentAvatarData;
            };
            reader.readAsDataURL(file);
        }
    });

    function closeProfileModal() {
        profileModal.classList.add('hidden');
        profileModal.classList.remove('flex');
    }

    cancelProfileBtn.addEventListener('click', closeProfileModal);

    profileModal.addEventListener('click', function(e) {
        if (e.target === profileModal) {
            closeProfileModal();
        }
    });

    // 保存资料到后端
    saveProfileBtn.addEventListener('click', function() {
        var username = profileUsernameInput.value.trim();
        var nickname = profileNameInput.value.trim();
        var avatar = currentAvatarData || profileAvatar.src;

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/user/update_info.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // 使用服务器返回的最新用户信息更新本地存储
                        var newUser = response.user;
                        localStorage.setItem('xiaosen_user', JSON.stringify(newUser));

                        // 更新页面显示
                        var displayName = newUser.nickname || newUser.username || '小森的潮物';
                        profileName.textContent = displayName;
                        profileAvatar.src = newUser.avatar || avatar;
                        profileAvatarPreview.src = newUser.avatar || avatar;
                        currentAvatarData = newUser.avatar || '';
                        closeProfileModal();
                    } else {
                        alert(response.message || '保存失败');
                    }
                } catch (e) {
                    alert('保存失败，请稍后重试');
                }
            }
        };
        xhr.send(JSON.stringify({ username: username, nickname: nickname, avatar: avatar }));
    });

    // ========== 收货地址功能 ==========
    var addressModal = document.getElementById('addressModal');
    var addressDisplay = document.getElementById('addressDisplay');
    var addressNameInput = document.getElementById('addressNameInput');
    var addressPhoneInput = document.getElementById('addressPhoneInput');
    var addressDetailInput = document.getElementById('addressDetailInput');
    var cancelAddressBtn = document.getElementById('cancelAddressBtn');
    var saveAddressBtn = document.getElementById('saveAddressBtn');

    function getAddress() {
        var user = getStoredUser();
        if (user && user.address && user.address.name) {
            return user.address;
        }
        // 只有未登录用户才从 xiaosen_address 读取
        if (!user) {
            try {
                return JSON.parse(localStorage.getItem('xiaosen_address') || 'null');
            } catch (e) {
                return null;
            }
        }
        return null;
    }

    function loadAddress() {
        var user = getStoredUser();
        if (user && user.address && user.address.name) {
            var address = user.address;
            var displayText = address.name + ' ' + address.phone.substring(0, 3) + '****' + address.phone.substring(7) + ' ' + address.detail;
            addressDisplay.textContent = displayText;
        } else {
            addressDisplay.textContent = '点击填写收货地址';
        }
    }

    loadAddress();

    function openAddressModal() {
        var address = getAddress();
        addressNameInput.value = address && address.name ? address.name : '';
        addressPhoneInput.value = address && address.phone ? address.phone : '';
        addressDetailInput.value = address && address.detail ? address.detail : '';
        addressModal.classList.remove('hidden');
        addressModal.classList.add('flex');
    }

    function closeAddressModal() {
        addressModal.classList.add('hidden');
        addressModal.classList.remove('flex');
    }

    cancelAddressBtn.addEventListener('click', closeAddressModal);

    addressModal.addEventListener('click', function(e) {
        if (e.target === addressModal) {
            closeAddressModal();
        }
    });

    // 保存收货地址到后端
    saveAddressBtn.addEventListener('click', function() {
        var name = addressNameInput.value.trim();
        var phone = addressPhoneInput.value.trim();
        var detail = addressDetailInput.value.trim();

        if (!name) {
            alert('请填写收货人姓名');
            return;
        }
        if (!phone) {
            alert('请填写联系电话');
            return;
        }
        if (!detail) {
            alert('请填写详细地址');
            return;
        }

        var address = {
            name: name,
            phone: phone,
            detail: detail
        };

        var xhr = new XMLHttpRequest();
        xhr.open('POST', 'api/user/update_info.php', true);
        xhr.setRequestHeader('Content-Type', 'application/json');
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                try {
                    var response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        // 更新本地存储
                        var user = getStoredUser() || {};
                        user.address = address;
                        localStorage.setItem('xiaosen_user', JSON.stringify(user));

                        // 更新页面显示
                        var displayText = name + ' ' + phone.substring(0, 3) + '****' + phone.substring(7) + ' ' + detail;
                        addressDisplay.textContent = displayText;

                        closeAddressModal();
                    } else {
                        alert(response.message || '保存失败');
                    }
                } catch (e) {
                    alert('保存失败，请稍后重试');
                }
            }
        };
        xhr.send(JSON.stringify({ address: address }));
    });

    // 点击收货地址区域打开地址弹窗（需要登录）
    document.getElementById('profileAddress').addEventListener('click', function(e) {
        var user = getStoredUser();
        if (!user) {
            alert('请先登录');
            return;
        }
        e.stopPropagation();
        openAddressModal();
    });

    // 从服务器获取订单
    function fetchOrdersFromServer(callback) {
        var xhr = new XMLHttpRequest();
        xhr.open('GET', 'api/user/orders.php?action=list', true);
        xhr.onreadystatechange = function() {
            if (xhr.readyState === 4) {
                if (xhr.status === 200) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        if (response.success) {
                            callback(response.orders || []);
                        } else {
                            callback([]);
                        }
                    } catch (e) {
                        callback([]);
                    }
                } else {
                    callback([]);
                }
            }
        };
        xhr.send();
    }

    function getOrders() {
        // 未登录时使用本地数据
        try {
            return JSON.parse(localStorage.getItem('xiaosen_orders') || '[]');
        } catch (e) {
            return [];
        }
    }

    function saveOrders(orders) {
        localStorage.setItem('xiaosen_orders', JSON.stringify(orders));
    }

    function showOrders(status) {
        currentStatus = status;

        // 隐藏卡券和收藏区域
        couponListSection.classList.add('hidden');
        couponEmpty.classList.add('hidden');
        favoritesSection.classList.add('hidden');
        favoritesEmpty.classList.add('hidden');

        orderListSection.classList.remove('hidden');
        orderEmpty.classList.add('hidden');

        orderTabs.forEach(function(tab) {
            tab.classList.remove('text-brand', 'font-medium');
            if (tab.dataset.status === status) {
                tab.classList.add('text-brand', 'font-medium');
            }
        });

        var user = getStoredUser();
        if (!user) {
            // 未登录使用本地数据
            var orders = getOrders();
            // 待收货 tab 显示：待发货(paid)、已发货/待收货(shipped)
            if (status === 'paid') {
                var filteredOrders = orders.filter(function(o) { return o.status === 'paid' || o.status === 'shipped'; });
                renderOrders(filteredOrders);
            } else {
                renderOrders(orders.filter(function(o) { return o.status === status; }));
            }
            return;
        }

        // 从服务器获取订单
        fetchOrdersFromServer(function(orders) {
            // 待收货 tab 显示：待发货(paid)、已发货/待收货(shipped)
            if (status === 'paid') {
                var filteredOrders = orders.filter(function(o) { return o.status === 'paid' || o.status === 'shipped'; });
                renderOrders(filteredOrders);
            } else {
                renderOrders(orders.filter(function(o) { return o.status === status; }));
            }
        });
    }

    function renderOrders(filtered) {
        if (filtered.length === 0) {
            orderList.innerHTML = '';
            orderEmpty.classList.remove('hidden');
            return;
        }

        var html = '';
        filtered.forEach(function(order, idx) {
            var statusText = '已完成';
            var statusClass = 'text-green-500';
            if (order.status === 'verifying') {
                statusText = '待验证';
                statusClass = 'text-yellow-500';
            } else if (order.status === 'paid') {
                statusText = '待发货';
                statusClass = 'text-orange-500';
            } else if (order.status === 'shipped') {
                statusText = '待收货';
                statusClass = 'text-orange-500';
            }
            var itemsHtml = '';

            order.items.forEach(function(item) {
                itemsHtml += '<div class="flex items-center gap-3 py-2">' +
                    '<img src="' + (item.image || 'https://via.placeholder.com/60') + '" alt="" class="w-14 h-14 rounded-lg object-cover bg-gray-100">' +
                    '<div class="flex-1 min-w-0">' +
                        '<p class="text-sm font-medium text-black truncate">' + (item.name || '商品') + '</p>' +
                        '<p class="text-xs text-gray-500">' + (item.size || '') + ' x ' + (item.quantity || 1) + '</p>' +
                    '</div>' +
                    '<p class="text-sm font-medium text-black">¥' + (item.price || 0) + '</p>' +
                '</div>';
            });

            var actionBtn = '';
            // 待验证显示"等待审核"，待发货显示"等待发货"，待收货(shipped)显示"确认收货"，已完成不显示按钮
            if (order.status === 'verifying') {
                actionBtn = '<span class="px-4 py-2 rounded-lg bg-yellow-100 text-yellow-600 text-sm font-medium">等待审核</span>';
            } else if (order.status === 'paid') {
                actionBtn = '<span class="px-4 py-2 rounded-lg bg-gray-100 text-gray-400 text-sm font-medium">等待发货</span>';
            } else if (order.status === 'shipped') {
                actionBtn = '<button type="button" class="confirm-receipt-btn px-4 py-2 rounded-lg bg-orange-500 text-white text-sm font-medium hover:bg-orange-600" data-idx="' + idx + '">确认收货</button>';
            }

            html += '<div class="bg-white mx-4 mt-2 rounded-xl shadow-sm overflow-hidden">' +
                '<div class="flex items-center justify-between px-4 py-3 border-b border-gray-100">' +
                    '<span class="text-xs text-gray-400">订单号：' + order.order_id + '</span>' +
                    '<span class="' + statusClass + ' text-xs font-medium">' + statusText + '</span>' +
                '</div>' +
                '<div class="px-4">' + itemsHtml + '</div>' +
                '<div class="flex items-center justify-between px-4 py-3 border-t border-gray-100">' +
                    '<span class="text-xs text-gray-400">' + order.created_at + '</span>' +
                    '<div class="flex items-center gap-2">' +
                        '<span class="text-sm font-bold text-black">合计：¥' + order.total + '</span>' +
                        actionBtn +
                    '</div>' +
                '</div>' +
            '</div>';
        });

        orderList.innerHTML = html;

        document.querySelectorAll('.confirm-receipt-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var idx = parseInt(this.dataset.idx);
                var user = getStoredUser();
                if (!user) {
                    alert('请先登录');
                    return;
                }

                // 从当前显示的订单中获取
                fetchOrdersFromServer(function(orders) {
                    // 待收货 tab 显示的是 shipped 状态的订单
                    var shippedOrders = orders.filter(function(o) { return o.status === 'shipped'; });
                    var orderToConfirm = shippedOrders[idx];
                    if (orderToConfirm) {
                        // 调用 API 更新订单状态为已完成
                        var xhr = new XMLHttpRequest();
                        xhr.open('POST', 'api/user/orders.php?action=update_status', true);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState === 4 && xhr.status === 200) {
                                try {
                                    var response = JSON.parse(xhr.responseText);
                                    if (response.success) {
                                        showOrders('paid');
                                    } else {
                                        alert(response.message || '操作失败');
                                    }
                                } catch (e) {
                                    alert('操作失败');
                                }
                            }
                        };
                        xhr.send('order_id=' + encodeURIComponent(orderToConfirm.order_id) + '&status=completed');
                    }
                });
            });
        });
    }

    orderTabs.forEach(function(tab) {
        tab.addEventListener('click', function() {
            showOrders(this.dataset.status);
        });
    });

    orderBackBtn.addEventListener('click', function() {
        currentStatus = '';
        orderListSection.classList.add('hidden');
        orderEmpty.classList.add('hidden');
        orderBackBtn.classList.add('hidden');
        favoritesSection.classList.add('hidden');
        favoritesEmpty.classList.add('hidden');
        couponListSection.classList.add('hidden');
        couponEmpty.classList.add('hidden');
        orderTabs.forEach(function(tab) {
            tab.classList.remove('text-brand', 'font-medium');
        });
    });

    window.addEventListener('storage', function(e) {
        if (e.key === 'xiaosen_orders' && currentStatus) {
            showOrders(currentStatus);
        }
    });

    // ========== 卡券功能（变量提前）==========
    var couponLink = document.getElementById('couponLink');
    var couponListSection = document.getElementById('couponListSection');
    var couponList = document.getElementById('couponList');
    var couponEmpty = document.getElementById('couponEmpty');

    // ========== 收藏功能 ==========
    var favoritesLink = document.getElementById('favoritesLink');
    var favoritesSection = document.getElementById('favoritesSection');
    var favoritesList = document.getElementById('favoritesList');
    var favoritesEmpty = document.getElementById('favoritesEmpty');
    var favoritesBackBtn = document.getElementById('orderBackBtn');

    function getFavorites() {
        try {
            return JSON.parse(localStorage.getItem('xiaosen_favorites') || '[]');
        } catch (e) {
            return [];
        }
    }

    function saveFavorites(favorites) {
        localStorage.setItem('xiaosen_favorites', JSON.stringify(favorites));
    }

    function showFavorites() {
        var favorites = getFavorites();

        orderListSection.classList.add('hidden');
        orderEmpty.classList.add('hidden');
        couponListSection.classList.add('hidden');
        couponEmpty.classList.add('hidden');
        favoritesSection.classList.remove('hidden');

        favoritesBackBtn.classList.remove('hidden');

        if (favorites.length === 0) {
            favoritesList.innerHTML = '';
            favoritesEmpty.classList.remove('hidden');
            return;
        }

        favoritesEmpty.classList.add('hidden');

        var html = '';
        favorites.forEach(function(item, idx) {
            html += '<div class="bg-white mt-2 rounded-xl shadow-sm overflow-hidden">' +
                '<div class="flex items-center gap-3 p-3">' +
                    '<img src="' + (item.image || 'https://via.placeholder.com/80') + '" alt="" class="w-20 h-20 rounded-lg object-cover bg-gray-100">' +
                    '<div class="flex-1 min-w-0">' +
                        '<p class="text-sm font-medium text-black line-clamp-2">' + (item.name || '商品') + '</p>' +
                        '<p class="text-red-600 font-bold mt-1">¥' + (item.price || 0) + '</p>' +
                        '<p class="text-xs text-gray-400 mt-1">' + (item.added_at || '') + '</p>' +
                    '</div>' +
                '</div>' +
                '<div class="flex justify-end px-3 pb-3">' +
                    '<button type="button" class="remove-favorite-btn px-4 py-2 rounded-lg border border-gray-300 text-gray-600 text-sm hover:bg-gray-100" data-idx="' + idx + '">取消收藏</button>' +
                '</div>' +
            '</div>';
        });

        favoritesList.innerHTML = html;

        document.querySelectorAll('.remove-favorite-btn').forEach(function(btn) {
            btn.addEventListener('click', function() {
                var idx = parseInt(this.dataset.idx);
                var favorites = getFavorites();
                favorites.splice(idx, 1);
                saveFavorites(favorites);
                showFavorites();
            });
        });
    }

    favoritesLink.addEventListener('click', function() {
        showFavorites();
    });

    // ========== 卡券功能（函数定义）==========
    function getCoupons() {
        try {
            return JSON.parse(localStorage.getItem('xiaosen_coupons') || '[]');
        } catch (e) {
            return [];
        }
    }

    function showCoupons() {
        var coupons = getCoupons();

        orderListSection.classList.add('hidden');
        orderEmpty.classList.add('hidden');
        favoritesSection.classList.add('hidden');
        favoritesEmpty.classList.add('hidden');
        couponListSection.classList.remove('hidden');
        favoritesBackBtn.classList.remove('hidden');

        if (coupons.length === 0) {
            couponList.innerHTML = '';
            couponEmpty.classList.remove('hidden');
            return;
        }

        couponEmpty.classList.add('hidden');

        var html = '';
        coupons.forEach(function(coupon, idx) {
            var discount = coupon.discount || 0;
            var minSpend = coupon.min_spend || 0;
            html += '<div class="bg-white mt-2 rounded-xl shadow-sm overflow-hidden">' +
                '<div class="flex items-center gap-3 p-3">' +
                    '<div class="w-16 h-16 rounded-lg bg-gradient-to-br from-red-500 to-orange-500 flex flex-col items-center justify-center text-white">' +
                        '<span class="text-2xl font-bold">' + discount + '</span>' +
                        '<span class="text-xs">元</span>' +
                    '</div>' +
                    '<div class="flex-1 min-w-0">' +
                        '<p class="text-sm font-medium text-black">' + (coupon.name || '优惠券') + '</p>' +
                        '<p class="text-xs text-gray-400 mt-1">满' + minSpend + '元可用</p>' +
                        '<p class="text-xs text-gray-400 mt-1">有效期：' + (coupon.expire_date || '永久有效') + '</p>' +
                    '</div>' +
                '</div>' +
                '<div class="flex justify-end px-3 pb-3">' +
                    '<button type="button" class="use-coupon-btn px-4 py-2 rounded-lg bg-red-500 text-white text-sm hover:bg-red-600" data-idx="' + idx + '">立即使用</button>' +
                '</div>' +
            '</div>';
        });

        couponList.innerHTML = html;
    }

    couponLink.addEventListener('click', function() {
        showCoupons();
    });

    // ========== 联系客服弹窗 ==========
    var contactServiceLink = document.getElementById('contactServiceLink');
    var contactModal = document.getElementById('contactModal');
    var closeContactModalBtn = document.getElementById('closeContactModalBtn');
    var copyWechatBtn = document.getElementById('copyWechatBtn');
    var wechatId = document.getElementById('wechatId').textContent;

    contactServiceLink.addEventListener('click', function() {
        contactModal.classList.remove('hidden');
        contactModal.classList.add('flex');
    });

    function closeContactModal() {
        contactModal.classList.add('hidden');
        contactModal.classList.remove('flex');
    }

    closeContactModalBtn.addEventListener('click', closeContactModal);

    contactModal.addEventListener('click', function(e) {
        if (e.target === contactModal) {
            closeContactModal();
        }
    });

    // ========== 免责声明弹窗逻辑 ==========
    var disclaimerLink = document.getElementById('disclaimerLink');
    var disclaimerModal = document.getElementById('disclaimerModal');
    var closeDisclaimerModalBtn = document.getElementById('closeDisclaimerModalBtn');

    if (disclaimerLink) {
        disclaimerLink.addEventListener('click', function() {
            disclaimerModal.classList.remove('hidden');
            disclaimerModal.classList.add('flex');
        });
    }

    function closeDisclaimerModal() {
        disclaimerModal.classList.add('hidden');
        disclaimerModal.classList.remove('flex');
    }

    if (closeDisclaimerModalBtn) {
        closeDisclaimerModalBtn.addEventListener('click', closeDisclaimerModal);
    }

    if (disclaimerModal) {
        disclaimerModal.addEventListener('click', function(e) {
            if (e.target === disclaimerModal) {
                closeDisclaimerModal();
            }
        });
    }

    copyWechatBtn.addEventListener('click', function() {
        navigator.clipboard.writeText(wechatId).then(function() {
            copyWechatBtn.textContent = '已复制';
            setTimeout(function() {
                copyWechatBtn.textContent = '复制';
            }, 2000);
        });
    });

    // ========== 清空数据 ==========
    var clearTestDataBtn = document.getElementById('clearTestDataBtn');
    if (clearTestDataBtn) {
        clearTestDataBtn.addEventListener('click', function() {
            if (confirm('确定要清空当前账户的所有数据吗？')) {
                localStorage.removeItem('xiaosen_address');
                
                var user = getStoredUser();
                if (user) {
                    // 已登录用户：从服务器删除订单
                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'api/user/orders.php?action=delete_all', true);
                    xhr.onreadystatechange = function() {
                        localStorage.removeItem('xiaosen_cart');
                        localStorage.removeItem('xiaosen_favorites');
                        localStorage.removeItem('xiaosen_coupons');
                        location.reload();
                    };
                    xhr.send();
                } else {
                    // 未登录用户：只清空本地数据
                    var allOrders = getOrders();
                    var otherOrders = allOrders.filter(function(o) {
                        return o.username && o.username !== 'guest';
                    });
                    localStorage.setItem('xiaosen_orders', JSON.stringify(otherOrders));
                    localStorage.removeItem('xiaosen_cart');
                    localStorage.removeItem('xiaosen_favorites');
                    localStorage.removeItem('xiaosen_coupons');
                    location.reload();
                }
            }
        });
    }
})();
</script>
