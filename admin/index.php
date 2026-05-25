<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理后台 - 小森潮鞋</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link {
            transition: all 0.2s;
        }
        .sidebar-link:hover, .sidebar-link.active {
            background: rgba(249, 115, 22, 0.1);
            border-right: 3px solid #f97316;
        }
        .sidebar-link.active {
            color: #f97316;
        }
        .sidebar-overlay {
            display: none;
        }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                z-index: 50;
                transition: left 0.3s ease;
            }
            .sidebar.open {
                left: 0;
            }
            .sidebar-overlay {
                display: block;
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.5);
                z-index: 40;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }
        /* 固定顶部栏 */
        .page-header {
            position: sticky;
            top: 0;
            z-index: 30;
            background: white;
        }
    </style>
</head>
<body class="bg-gray-100">
    <!-- 移动端遮罩 -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="flex h-screen">
        <!-- 侧边栏 -->
        <aside class="sidebar w-64 bg-gray-900 text-white flex flex-col" id="sidebar">
            <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                <h1 class="text-lg font-bold text-orange-500">
                    <i class="fas fa-shoe-prints mr-2"></i>小森潮鞋管理
                </h1>
                <button class="md:hidden text-gray-400 hover:text-white" onclick="toggleSidebar()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>

            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="index.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="dashboard">
                    <i class="fas fa-home w-6"></i>
                    <span>控制台</span>
                </a>
                <a href="products.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="products">
                    <i class="fas fa-box w-6"></i>
                    <span>商品管理</span>
                </a>
                <a href="series.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="series">
                    <i class="fas fa-tags w-6"></i>
                    <span>系列管理</span>
                </a>
                <a href="users.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="users">
                    <i class="fas fa-users w-6"></i>
                    <span>用户管理</span>
                </a>
                <a href="orders.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="orders">
                    <i class="fas fa-shopping-cart w-6"></i>
                    <span>订单管理</span>
                </a>
            </nav>

            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm" id="adminName">管理员</span>
                    <button onclick="logout()" class="text-gray-400 hover:text-red-400 transition">
                        <i class="fas fa-sign-out-alt"></i>
                    </button>
                </div>
            </div>
        </aside>

        <!-- 主内容区 -->
        <main class="flex-1 overflow-auto">
            <!-- 移动端顶部栏 -->
            <header class="bg-white shadow-sm px-4 py-3 flex items-center gap-3 md:hidden page-header">
                <button onclick="toggleSidebar()" class="p-2 -ml-2 text-gray-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-lg font-semibold text-gray-800" id="pageTitle">控制台</h2>
            </header>

            <!-- 桌面端顶部栏 -->
            <header class="bg-white shadow-sm px-4 md:px-8 py-4 hidden md:block page-header">
                <h2 class="text-xl font-semibold text-gray-800" id="pageTitle">控制台</h2>
            </header>

            <div class="p-4 md:p-8" id="mainContent">
                <!-- 统计卡片 -->
                <div class="grid grid-cols-2 md:grid-cols-2 lg:grid-cols-4 gap-3 md:gap-6 mb-6 md:mb-8">
                    <div class="bg-white rounded-lg shadow p-3 md:p-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-12 md:h-12 bg-orange-100 rounded-full flex items-center justify-center mr-2 md:mr-4">
                                <i class="fas fa-box text-orange-600 text-sm md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs md:text-sm">商品总数</p>
                                <p class="text-lg md:text-2xl font-bold text-gray-800" id="statProducts">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-3 md:p-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-12 md:h-12 bg-blue-100 rounded-full flex items-center justify-center mr-2 md:mr-4">
                                <i class="fas fa-users text-blue-600 text-sm md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs md:text-sm">用户总数</p>
                                <p class="text-lg md:text-2xl font-bold text-gray-800" id="statUsers">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-3 md:p-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-12 md:h-12 bg-green-100 rounded-full flex items-center justify-center mr-2 md:mr-4">
                                <i class="fas fa-shopping-cart text-green-600 text-sm md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs md:text-sm">订单总数</p>
                                <p class="text-lg md:text-2xl font-bold text-gray-800" id="statOrders">-</p>
                            </div>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-3 md:p-6">
                        <div class="flex items-center">
                            <div class="w-8 h-8 md:w-12 md:h-12 bg-purple-100 rounded-full flex items-center justify-center mr-2 md:mr-4">
                                <i class="fas fa-yen-sign text-purple-600 text-sm md:text-xl"></i>
                            </div>
                            <div>
                                <p class="text-gray-500 text-xs md:text-sm">总销售额</p>
                                <p class="text-lg md:text-2xl font-bold text-gray-800" id="statSales">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 订单状态概览 -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 md:gap-6 mb-6 md:mb-8">
                    <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-yellow-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">待支付</p>
                                <p class="text-2xl md:text-3xl font-bold text-gray-800" id="statPending">-</p>
                            </div>
                            <i class="fas fa-clock text-yellow-500 text-2xl md:text-3xl opacity-50"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-green-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">已支付</p>
                                <p class="text-2xl md:text-3xl font-bold text-gray-800" id="statPaid">-</p>
                            </div>
                            <i class="fas fa-check-circle text-green-500 text-2xl md:text-3xl opacity-50"></i>
                        </div>
                    </div>
                    <div class="bg-white rounded-lg shadow p-4 md:p-6 border-l-4 border-blue-500">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-gray-500 text-sm">已发货</p>
                                <p class="text-2xl md:text-3xl font-bold text-gray-800" id="statShipped">-</p>
                            </div>
                            <i class="fas fa-truck text-blue-500 text-2xl md:text-3xl opacity-50"></i>
                        </div>
                    </div>
                </div>

                <!-- 最近订单 -->
                <div class="bg-white rounded-lg shadow">
                    <div class="px-6 py-4 border-b flex justify-between items-center">
                        <h3 class="font-semibold text-gray-800">最近订单</h3>
                        <a href="orders.php" class="text-orange-600 hover:text-orange-700 text-sm">查看全部 <i class="fas fa-arrow-right ml-1"></i></a>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">订单号</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">商品</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">总价</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">状态</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">时间</th>
                                </tr>
                            </thead>
                            <tbody id="recentOrdersTable" class="divide-y divide-gray-200">
                                <tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">加载中...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </main>
    </div>

    <script>
        // 移动端侧边栏开关
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }

        // 关闭侧边栏
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }

        // 点击遮罩关闭侧边栏
        document.getElementById('sidebarOverlay').addEventListener('click', closeSidebar);

        // 检查登录状态
        async function checkAuth() {
            try {
                const res = await fetch('../../api/admin/check.php');
                const data = await res.json();
                if (!data.success) {
                    window.location.href = 'login.php';
                } else {
                    document.getElementById('adminName').textContent = data.user.username;
                    initSidebar();
                    loadDashboardData();
                }
            } catch (e) {
                window.location.href = 'login.php';
            }
        }

        async function loadDashboardData() {
            // 加载统计数据
            const statsRes = await fetch('../../api/admin/stats.php?action=stats');
            const statsData = await statsRes.json();
            if (statsData.success) {
                const s = statsData.stats;
                document.getElementById('statProducts').textContent = s.totalProducts;
                document.getElementById('statUsers').textContent = s.totalUsers;
                document.getElementById('statOrders').textContent = s.totalOrders;
                document.getElementById('statSales').textContent = '¥' + s.totalSales.toFixed(2);
                document.getElementById('statPending').textContent = s.pendingOrders;
                document.getElementById('statPaid').textContent = s.paidOrders;
                document.getElementById('statShipped').textContent = s.shippedOrders;
            }

            // 加载最近订单
            const ordersRes = await fetch('../../api/admin/stats.php?action=recent_orders');
            const ordersData = await ordersRes.json();
            if (ordersData.success) {
                renderRecentOrders(ordersData.orders);
            }
        }

        function renderRecentOrders(orders) {
            const tbody = document.getElementById('recentOrdersTable');
            const statusMap = {
                'paid': { text: '已支付', class: 'bg-green-100 text-green-800' },
                'pending': { text: '待支付', class: 'bg-yellow-100 text-yellow-800' },
                'shipped': { text: '已发货', class: 'bg-blue-100 text-blue-800' },
                'completed': { text: '已完成', class: 'bg-purple-100 text-purple-800' },
                'cancelled': { text: '已取消', class: 'bg-red-100 text-red-800' }
            };

            if (orders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">暂无订单</td></tr>';
                return;
            }

            tbody.innerHTML = orders.map(o => {
                const items = (o.items || []).map(i => i.name || i.id).join(', ') || '无商品';
                const status = statusMap[o.status] || { text: o.status, class: 'bg-gray-100 text-gray-800' };
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-sm">${o.order_id}</td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="${items}">${items}</td>
                        <td class="px-6 py-4 font-bold text-orange-600">¥${o.total}</td>
                        <td class="px-6 py-4"><span class="${status.class} px-2 py-1 rounded text-xs">${status.text}</span></td>
                        <td class="px-6 py-4 text-gray-500 text-sm">${o.created_at || '-'}</td>
                    </tr>
                `;
            }).join('');
        }

        function initSidebar() {
            const currentPage = window.location.pathname.split('/').pop().replace('.php', '');
            const links = document.querySelectorAll('.sidebar-link');
            const pageTitle = document.getElementById('pageTitle');

            const titles = {
                'index': '控制台',
                'products': '商品管理',
                'users': '用户管理',
                'orders': '订单管理'
            };

            links.forEach(link => {
                const page = link.dataset.page;
                if (page === currentPage || (currentPage === '' && page === 'dashboard')) {
                    link.classList.add('active');
                }
            });

            if (currentPage && titles[currentPage]) {
                pageTitle.textContent = titles[currentPage];
            }
        }

        async function logout() {
            if (confirm('确定要退出登录吗？')) {
                await fetch('../../api/admin/logout.php');
                window.location.href = 'login.php';
            }
        }

        // 页面加载时检查
        checkAuth();
    </script>
</body>
</html>
