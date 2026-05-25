<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>用户管理 - 小森潮鞋</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .sidebar-link { transition: all 0.2s; }
        .sidebar-link:hover, .sidebar-link.active { background: rgba(249, 115, 22, 0.1); border-right: 3px solid #f97316; }
        .sidebar-link.active { color: #f97316; }
        .sidebar-overlay { display: none; }
        @media (max-width: 768px) {
            .sidebar {
                position: fixed;
                left: -280px;
                top: 0;
                bottom: 0;
                z-index: 50;
                transition: left 0.3s ease;
            }
            .sidebar.open { left: 0; }
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
            .sidebar-overlay.show { opacity: 1; visibility: visible; }
            .table-wrapper { overflow-x: auto; -webkit-overflow-scrolling: touch; }
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
    <div class="sidebar-overlay" id="sidebarOverlay"></div>
    <div class="flex h-screen">
        <aside class="sidebar w-64 bg-gray-900 text-white flex flex-col" id="sidebar">
            <div class="p-4 border-b border-gray-800 flex items-center justify-between">
                <h1 class="text-lg font-bold text-orange-500"><i class="fas fa-shoe-prints mr-2"></i>小森潮鞋管理</h1>
                <button class="md:hidden text-gray-400 hover:text-white" onclick="toggleSidebar()">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            <nav class="flex-1 p-4 space-y-2 overflow-y-auto">
                <a href="index.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="dashboard"><i class="fas fa-home w-6"></i><span>控制台</span></a>
                <a href="products.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="products"><i class="fas fa-box w-6"></i><span>商品管理</span></a>
                <a href="series.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="series"><i class="fas fa-tags w-6"></i><span>系列管理</span></a>
                <a href="users.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300 active" data-page="users"><i class="fas fa-users w-6"></i><span>用户管理</span></a>
                <a href="orders.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="orders"><i class="fas fa-shopping-cart w-6"></i><span>订单管理</span></a>
            </nav>
            <div class="p-4 border-t border-gray-800">
                <div class="flex items-center justify-between">
                    <span class="text-gray-400 text-sm" id="adminName">管理员</span>
                    <button onclick="logout()" class="text-gray-400 hover:text-red-400"><i class="fas fa-sign-out-alt"></i></button>
                </div>
            </div>
        </aside>

        <main class="flex-1 overflow-auto">
            <!-- 移动端顶部栏 -->
            <header class="bg-white shadow-sm px-4 py-3 flex items-center gap-3 md:hidden page-header">
                <button onclick="toggleSidebar()" class="p-2 -ml-2 text-gray-600">
                    <i class="fas fa-bars text-xl"></i>
                </button>
                <h2 class="text-lg font-semibold text-gray-800">用户管理</h2>
            </header>

            <!-- 桌面端顶部栏 -->
            <header class="bg-white shadow-sm px-4 md:px-8 py-4 hidden md:block page-header">
                <h2 class="text-xl font-semibold text-gray-800">用户管理</h2>
            </header>

            <div class="p-4 md:p-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- 桌面端表格 -->
                    <div class="hidden md:block table-wrapper">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">用户名</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">角色</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">注册时间</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                                </tr>
                            </thead>
                            <tbody id="userTable" class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                    <!-- 移动端卡片列表 -->
                    <div id="userCards" class="md:hidden divide-y divide-gray-200">
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- 编辑弹窗 -->
    <div id="userModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-4xl max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">编辑用户</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="userForm" class="p-6 space-y-4">
                <input type="hidden" id="userId">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">用户名</label>
                        <input type="text" id="userUsername" disabled class="w-full px-3 py-2 border bg-gray-100 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">角色</label>
                        <select id="userRole" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                            <option value="user">普通用户</option>
                            <option value="admin">管理员</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">新密码（留空则不修改）</label>
                    <input type="password" id="userPassword" placeholder="请输入新密码" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">取消</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">保存</button>
                </div>
            </form>

            <!-- 用户订单记录 -->
            <div class="border-t">
                <div class="p-6">
                    <h4 class="font-semibold text-gray-800 mb-4">订单记录</h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">订单号</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">商品</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">总价</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">状态</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">时间</th>
                                </tr>
                            </thead>
                            <tbody id="userOrdersTable" class="divide-y divide-gray-200">
                                <tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">暂无订单</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        let users = [];
        let allOrders = [];

        // 移动端侧边栏开关
        function toggleSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.toggle('open');
            overlay.classList.toggle('show');
        }
        function closeSidebar() {
            const sidebar = document.getElementById('sidebar');
            const overlay = document.getElementById('sidebarOverlay');
            sidebar.classList.remove('open');
            overlay.classList.remove('show');
        }
        document.getElementById('sidebarOverlay')?.addEventListener('click', closeSidebar);

        async function checkAuth() {
            const res = await fetch('../../api/admin/check.php');
            const data = await res.json();
            if (!data.success) window.location.href = 'login.php';
            document.getElementById('adminName').textContent = data.user.username;
        }

        async function loadUsers() {
            const res = await fetch('../../api/admin/users.php?action=list');
            const data = await res.json();
            users = data.users || [];
            renderUsers(users);
        }

        async function loadAllOrders() {
            const res = await fetch('../../api/admin/orders.php?action=list');
            const data = await res.json();
            allOrders = data.orders || [];
        }

        function renderUsers(list) {
            // 渲染桌面端表格
            const tbody = document.getElementById('userTable');
            if (list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-6 py-8 text-center text-gray-500">暂无用户</td></tr>';
            } else {
                tbody.innerHTML = list.map(u => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">${u.id}</td>
                        <td class="px-6 py-4 font-medium">${u.username}</td>
                        <td class="px-6 py-4">${u.role === 'admin' ? '<span class="bg-purple-100 text-purple-800 px-2 py-1 rounded text-xs">管理员</span>' : '<span class="bg-blue-100 text-blue-800 px-2 py-1 rounded text-xs">普通用户</span>'}</td>
                        <td class="px-6 py-4 text-gray-500">${u.created_at || '-'}</td>
                        <td class="px-6 py-4">
                            <button onclick="editUser(${u.id})" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteUser(${u.id})" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `).join('');
            }

            // 渲染移动端卡片
            const cards = document.getElementById('userCards');
            if (list.length === 0) {
                cards.innerHTML = '<div class="p-4 text-center text-gray-500">暂无用户</div>';
            } else {
                cards.innerHTML = list.map(u => `
                    <div class="p-4 flex items-center justify-between">
                        <div>
                            <div class="font-medium text-gray-800">${u.username}</div>
                            <div class="text-sm text-gray-500 mt-1">ID: ${u.id} · ${u.created_at || '-'}</div>
                        </div>
                        <div class="flex items-center gap-2">
                            <span class="${u.role === 'admin' ? 'bg-purple-100 text-purple-800' : 'bg-blue-100 text-blue-800'} px-2 py-1 rounded text-xs">${u.role === 'admin' ? '管理员' : '普通用户'}</span>
                            <button onclick="editUser(${u.id})" class="text-blue-600 hover:text-blue-800 p-2"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteUser(${u.id})" class="text-red-600 hover:text-red-800 p-2"><i class="fas fa-trash"></i></button>
                        </div>
                    </div>
                `).join('');
            }
        }

        function editUser(id) {
            const user = users.find(u => u.id === id);
            if (user) {
                document.getElementById('userModal').classList.remove('hidden');
                document.getElementById('userModal').classList.add('flex');
                document.getElementById('userId').value = user.id;
                document.getElementById('userUsername').value = user.username;
                document.getElementById('userRole').value = user.role;
                document.getElementById('userPassword').value = '';

                // 加载该用户的订单
                renderUserOrders(user.id);
            }
        }

        function renderUserOrders(userId) {
            const tbody = document.getElementById('userOrdersTable');
            const userOrders = allOrders.filter(o => o.user_id == userId);

            const statusMap = {
                'paid': { text: '已支付', class: 'bg-green-100 text-green-800' },
                'pending': { text: '待支付', class: 'bg-yellow-100 text-yellow-800' },
                'shipped': { text: '已发货', class: 'bg-blue-100 text-blue-800' },
                'completed': { text: '已完成', class: 'bg-purple-100 text-purple-800' },
                'cancelled': { text: '已取消', class: 'bg-red-100 text-red-800' }
            };

            if (userOrders.length === 0) {
                tbody.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">暂无订单</td></tr>';
                return;
            }

            tbody.innerHTML = userOrders.map(o => {
                const items = (o.items || []).map(i => i.name || i.id).join(', ') || '无商品';
                const status = statusMap[o.status] || { text: o.status, class: 'bg-gray-100 text-gray-800' };
                return `
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2 font-mono text-xs">${o.order_id}</td>
                        <td class="px-3 py-2 text-gray-600 max-w-xs truncate" title="${items}">${items}</td>
                        <td class="px-3 py-2 font-bold text-orange-600">¥${o.total}</td>
                        <td class="px-3 py-2"><span class="${status.class} px-2 py-0.5 rounded text-xs">${status.text}</span></td>
                        <td class="px-3 py-2 text-gray-500 text-xs">${o.created_at || '-'}</td>
                    </tr>
                `;
            }).join('');
        }

        function closeModal() {
            document.getElementById('userModal').classList.add('hidden');
            document.getElementById('userModal').classList.remove('flex');
        }

        async function deleteUser(id) {
            if (!confirm('确定要删除该用户吗？')) return;
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);

            const res = await fetch('../../api/admin/users.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                loadUsers();
            } else {
                alert(data.message || '删除失败');
            }
        }

        document.getElementById('userForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('id', document.getElementById('userId').value);
            const password = document.getElementById('userPassword').value;
            if (password) formData.append('password', password);
            formData.append('role', document.getElementById('userRole').value);

            const res = await fetch('../../api/admin/users.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                closeModal();
                loadUsers();
            } else {
                alert(data.message || '保存失败');
            }
        });

        async function logout() {
            if (confirm('确定要退出登录吗？')) {
                await fetch('../../api/admin/logout.php');
                window.location.href = 'login.php';
            }
        }

        checkAuth();
        loadUsers();
        loadAllOrders();
    </script>
</body>
</html>
