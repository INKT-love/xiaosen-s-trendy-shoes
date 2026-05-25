<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>订单管理 - 小森潮鞋</title>
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
                <a href="users.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="users"><i class="fas fa-users w-6"></i><span>用户管理</span></a>
                <a href="orders.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300 active" data-page="orders"><i class="fas fa-shopping-cart w-6"></i><span>订单管理</span></a>
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
            <header class="bg-white shadow-sm px-4 py-3 flex items-center justify-between md:hidden page-header">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="p-2 -ml-2 text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg font-semibold text-gray-800">订单管理</h2>
                </div>
                <button onclick="loadOrders()" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                    <i class="fas fa-sync-alt"></i>
                </button>
            </header>

            <!-- 桌面端顶部栏 -->
            <header class="bg-white shadow-sm px-4 md:px-8 py-4 hidden md:block page-header">
                <div class="flex items-center justify-between">
                    <h2 class="text-xl font-semibold text-gray-800">订单管理</h2>
                    <button onclick="loadOrders()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-sync-alt mr-2"></i>刷新
                    </button>
                </div>
            </header>

            <div class="p-4 md:p-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- 桌面端表格 -->
                    <div class="hidden md:block table-wrapper">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">订单号</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">商品</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">总价</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">支付方式</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">状态</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">下单时间</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                                </tr>
                            </thead>
                            <tbody id="orderTable" class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                    <!-- 移动端卡片列表 -->
                    <div id="orderCards" class="md:hidden divide-y divide-gray-200">
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- 状态编辑弹窗 -->
    <div id="statusModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-md m-4">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">修改订单状态</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="statusForm" class="p-6 space-y-4">
                <input type="hidden" id="orderId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">订单状态</label>
                    <select id="orderStatus" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                        <option value="verifying">验证中</option>
                        <option value="paid">待发货</option>
                        <option value="shipped">待收货</option>
                        <option value="completed">已完成</option>
                        <option value="cancelled">已取消</option>
                    </select>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">取消</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">保存</button>
                </div>
            </form>
        </div>
    </div>

    <!-- 订单详情弹窗 -->
    <div id="detailModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold">订单详情</h3>
                <button onclick="closeDetailModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <div class="p-6 space-y-6">
                <!-- 买家信息 -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-user text-orange-500 mr-2"></i>买家信息
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">收货人：</span>
                            <span id="detailUsername" class="text-gray-800 font-medium">-</span>
                        </div>
                        <div>
                            <span class="text-gray-500">联系电话：</span>
                            <span id="detailPhone" class="text-gray-800 font-medium">-</span>
                        </div>
                        <div class="col-span-2">
                            <span class="text-gray-500">收货地址：</span>
                            <span id="detailAddress" class="text-gray-800 font-medium">-</span>
                        </div>
                    </div>
                </div>

                <!-- 商品信息 -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-box text-orange-500 mr-2"></i>商品信息
                    </h4>
                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">商品名称</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">规格</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">数量</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">单价</th>
                                    <th class="px-3 py-2 text-left text-xs font-medium text-gray-500">小计</th>
                                </tr>
                            </thead>
                            <tbody id="detailItems" class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- 订单信息 -->
                <div>
                    <h4 class="font-semibold text-gray-800 mb-3 flex items-center">
                        <i class="fas fa-file-alt text-orange-500 mr-2"></i>订单信息
                    </h4>
                    <div class="bg-gray-50 rounded-lg p-4 grid grid-cols-2 gap-4 text-sm">
                        <div>
                            <span class="text-gray-500">订单号：</span>
                            <span id="detailOrderId" class="text-gray-800 font-mono">-</span>
                        </div>
                        <div>
                            <span class="text-gray-500">订单状态：</span>
                            <span id="detailStatus">-</span>
                        </div>
                        <div>
                            <span class="text-gray-500">下单时间：</span>
                            <span id="detailCreatedAt" class="text-gray-800">-</span>
                        </div>
                        <div>
                            <span class="text-gray-500">订单总价：</span>
                            <span id="detailTotal" class="text-orange-600 font-bold">-</span>
                        </div>
                        <div>
                            <span class="text-gray-500">支付方式：</span>
                            <span id="detailPaymentType" class="font-medium">-</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="p-6 border-t bg-gray-50">
                <button type="button" onclick="closeDetailModal()" class="w-full py-3 bg-orange-600 text-white rounded-lg hover:bg-orange-700">关闭</button>
            </div>
        </div>
    </div>

    <script>
        let orders = [];
        const statusMap = {
            'verifying': { text: '验证中', class: 'bg-yellow-100 text-yellow-800' },
            'paid': { text: '待发货', class: 'bg-orange-100 text-orange-800' },
            'shipped': { text: '待收货', class: 'bg-blue-100 text-blue-800' },
            'completed': { text: '已完成', class: 'bg-purple-100 text-purple-800' },
            'cancelled': { text: '已取消', class: 'bg-red-100 text-red-800' }
        };

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

        async function loadOrders() {
            const res = await fetch('../../api/admin/orders.php?action=list');
            const data = await res.json();
            orders = data.orders || [];
            renderOrders(orders);
        }

        function renderOrders(list) {
            // 渲染桌面端表格
            const tbody = document.getElementById('orderTable');
            if (list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-8 text-center text-gray-500">暂无订单</td></tr>';
            } else {
                tbody.innerHTML = list.map(o => {
                    const items = (o.items || []).map(i => i.name || i.id).join(', ') || '无商品';
                    const status = statusMap[o.status] || { text: o.status, class: 'bg-gray-100 text-gray-800' };
                    const payMethod = o.payment_type === 'alipay' ? '支付宝' : '微信';
                    const payClass = o.payment_type === 'alipay' ? 'text-blue-600' : 'text-green-600';
                    return `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 font-mono text-sm">${o.order_id}</td>
                            <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="${items}">${items}</td>
                            <td class="px-6 py-4 font-bold text-orange-600">¥${o.total}</td>
                            <td class="px-6 py-4 ${payClass} text-sm"><i class="fas ${o.payment_type === 'alipay' ? 'fa-alipay' : 'fa-weixin'} mr-1"></i>${payMethod}</td>
                            <td class="px-6 py-4"><span class="${status.class} px-2 py-1 rounded text-xs">${status.text}</span></td>
                            <td class="px-6 py-4 text-gray-500 text-sm">${o.created_at || '-'}</td>
                            <td class="px-6 py-4">
                                <button onclick="viewDetail('${o.order_id}')" class="text-green-600 hover:text-green-800 mr-3" title="查看详情"><i class="fas fa-eye"></i></button>
                                <button onclick="editStatus('${o.order_id}')" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fas fa-edit"></i></button>
                                <button onclick="deleteOrder('${o.order_id}')" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            // 渲染移动端卡片
            const cards = document.getElementById('orderCards');
            if (list.length === 0) {
                cards.innerHTML = '<div class="p-4 text-center text-gray-500">暂无订单</div>';
            } else {
                cards.innerHTML = list.map(o => {
                    const items = (o.items || []).map(i => i.name || i.id).join(', ') || '无商品';
                    const status = statusMap[o.status] || { text: o.status, class: 'bg-gray-100 text-gray-800' };
                    const payMethod = o.payment_type === 'alipay' ? '支付宝' : '微信';
                    const payClass = o.payment_type === 'alipay' ? 'text-blue-600' : 'text-green-600';
                    return `
                        <div class="p-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-mono text-sm text-gray-500">${o.order_id}</span>
                                <span class="${status.class} px-2 py-1 rounded text-xs">${status.text}</span>
                            </div>
                            <div class="text-sm text-gray-600 truncate mb-2" title="${items}">${items}</div>
                            <div class="flex items-center justify-between">
                                <span class="font-bold text-orange-600">¥${o.total}</span>
                                <span class="${payClass} text-sm"><i class="fas ${o.payment_type === 'alipay' ? 'fa-alipay' : 'fa-weixin'} mr-1"></i>${payMethod}</span>
                            </div>
                            <div class="flex items-center justify-between mt-2">
                                <div class="text-xs text-gray-400">${o.created_at || '-'}</div>
                                <div class="flex items-center gap-2">
                                    <button onclick="viewDetail('${o.order_id}')" class="text-green-600 hover:text-green-800 p-2" title="查看详情"><i class="fas fa-eye"></i></button>
                                    <button onclick="editStatus('${o.order_id}')" class="text-blue-600 hover:text-blue-800 p-2"><i class="fas fa-edit"></i></button>
                                    <button onclick="deleteOrder('${o.order_id}')" class="text-red-600 hover:text-red-800 p-2"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    `;
                }).join('');
            }
        }

        function editStatus(orderId) {
            const order = orders.find(o => o.order_id === orderId);
            if (order) {
                document.getElementById('statusModal').classList.remove('hidden');
                document.getElementById('statusModal').classList.add('flex');
                document.getElementById('orderId').value = orderId;
                document.getElementById('orderStatus').value = order.status;
            }
        }

        function viewDetail(orderId) {
            const order = orders.find(o => o.order_id === orderId);
            if (!order) return;

            // 买家信息
            document.getElementById('detailUsername').textContent = order.recipient || order.username || order.name || '-';
            document.getElementById('detailPhone').textContent = order.phone || '-';
            document.getElementById('detailAddress').textContent = order.address || '-';

            // 订单信息
            document.getElementById('detailOrderId').textContent = order.order_id;
            const status = statusMap[order.status] || { text: order.status, class: 'bg-gray-100 text-gray-800' };
            document.getElementById('detailStatus').innerHTML = `<span class="${status.class} px-2 py-1 rounded text-xs">${status.text}</span>`;
            document.getElementById('detailCreatedAt').textContent = order.created_at || '-';
            document.getElementById('detailTotal').textContent = '¥' + order.total;

            // 支付方式
            const paymentTypeEl = document.getElementById('detailPaymentType');
            if (order.payment_type === 'alipay') {
                paymentTypeEl.innerHTML = '<span class="text-blue-600"><i class="fas fa-alipay mr-1"></i>支付宝</span>';
            } else {
                paymentTypeEl.innerHTML = '<span class="text-green-600"><i class="fas fa-weixin mr-1"></i>微信</span>';
            }

            // 商品信息
            const itemsBody = document.getElementById('detailItems');
            if (order.items && order.items.length > 0) {
                itemsBody.innerHTML = order.items.map(item => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-3 py-2">${item.name || '-'}</td>
                        <td class="px-3 py-2 text-gray-600">${item.size || '-'} / ${item.color || '-'}</td>
                        <td class="px-3 py-2">${item.quantity || 1}</td>
                        <td class="px-3 py-2">¥${item.price}</td>
                        <td class="px-3 py-2 font-bold text-orange-600">¥${(item.price * (item.quantity || 1)).toFixed(2)}</td>
                    </tr>
                `).join('');
            } else {
                itemsBody.innerHTML = '<tr><td colspan="5" class="px-3 py-4 text-center text-gray-500">无商品信息</td></tr>';
            }

            document.getElementById('detailModal').classList.remove('hidden');
            document.getElementById('detailModal').classList.add('flex');
        }

        function closeDetailModal() {
            document.getElementById('detailModal').classList.add('hidden');
            document.getElementById('detailModal').classList.remove('flex');
        }

        function closeModal() {
            document.getElementById('statusModal').classList.add('hidden');
            document.getElementById('statusModal').classList.remove('flex');
        }

        document.getElementById('statusForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData();
            formData.append('action', 'update');
            formData.append('order_id', document.getElementById('orderId').value);
            formData.append('status', document.getElementById('orderStatus').value);

            const res = await fetch('../../api/admin/orders.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                closeModal();
                loadOrders();
            } else {
                alert(data.message || '更新失败');
            }
        });

        async function deleteOrder(orderId) {
            if (!confirm('确定要删除这个订单吗？')) return;
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('order_id', orderId);

            const res = await fetch('../../api/admin/orders.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                loadOrders();
            } else {
                alert(data.message || '删除失败');
            }
        }

        async function logout() {
            if (confirm('确定要退出登录吗？')) {
                await fetch('../../api/admin/logout.php');
                window.location.href = 'login.php';
            }
        }

        checkAuth();
        loadOrders();
    </script>
</body>
</html>
