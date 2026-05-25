<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>系列管理 - 小森潮鞋</title>
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
        }
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
                <a href="series.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300 active" data-page="series"><i class="fas fa-tags w-6"></i><span>系列管理</span></a>
                <a href="users.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="users"><i class="fas fa-users w-6"></i><span>用户管理</span></a>
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
            <header class="bg-white shadow-sm px-4 py-3 flex items-center justify-between md:hidden page-header">
                <div class="flex items-center gap-3">
                    <button onclick="toggleSidebar()" class="p-2 -ml-2 text-gray-600">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    <h2 class="text-lg font-semibold text-gray-800">系列管理</h2>
                </div>
                <button onclick="showModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                    <i class="fas fa-plus mr-1"></i>添加
                </button>
            </header>

            <!-- 桌面端顶部栏 -->
            <header class="bg-white shadow-sm px-4 md:px-8 py-4 hidden md:flex justify-between items-center page-header">
                <h2 class="text-xl font-semibold text-gray-800">系列管理</h2>
                <button onclick="showModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition">
                    <i class="fas fa-plus mr-2"></i>添加系列
                </button>
            </header>

            <div class="p-4 md:p-8">
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <div class="p-4 md:p-6">
                        <p class="text-sm text-gray-500 mb-4">
                            <i class="fas fa-info-circle mr-1"></i>
                            每个系列对应前端首页的一个黑色标题区块。热门标签将显示在该系列下方的白框中。
                        </p>
                    </div>
                    
                    <!-- 桌面端表格 -->
                    <div class="hidden md:block">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">系列标识</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">系列名称</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">英文标题</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">中文标题</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">热门标签</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">状态</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                                </tr>
                            </thead>
                            <tbody id="seriesTable" class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- 移动端卡片列表 -->
                    <div id="seriesCards" class="md:hidden">
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- 添加/编辑弹窗 -->
    <div id="seriesModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-lg max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold" id="modalTitle">添加系列</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="seriesForm" class="p-6 space-y-4">
                <input type="hidden" id="seriesSlug">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">系列标识 (slug) <span class="text-red-500">*</span></label>
                    <input type="text" id="seriesSlugInput" required placeholder="如: nike, adidas" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                    <p class="text-xs text-gray-500 mt-1">用于URL和内部识别，建议使用英文缩写</p>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">系列名称 <span class="text-red-500">*</span></label>
                    <input type="text" id="seriesName" required placeholder="如: Nike" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">英文标题</label>
                    <input type="text" id="seriesDisplay" placeholder="如: Nike SERIES" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">中文标题</label>
                    <input type="text" id="seriesDisplayCn" placeholder="如: 耐克系列" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">热门标签（逗号分隔）</label>
                    <input type="text" id="seriesHotTags" placeholder="如: AJ1, Dunk, AF1" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                    <p class="text-xs text-gray-500 mt-1">点击这些标签将筛选对应商品</p>
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="seriesEnabled" class="w-4 h-4 text-orange-600 rounded" checked>
                    <label for="seriesEnabled" class="text-sm text-gray-700">启用该系列</label>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">取消</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">保存</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        let seriesList = [];

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

        async function loadSeries() {
            const res = await fetch('../../api/admin/series.php?action=list');
            const data = await res.json();
            if (data.success) {
                seriesList = data.series || [];
                renderSeries(seriesList);
            }
        }

        function renderSeries(list) {
            // 桌面端表格
            const tbody = document.getElementById('seriesTable');
            if (list.length === 0) {
                tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-8 text-center text-gray-500">暂无系列</td></tr>';
            } else {
                tbody.innerHTML = list.map(s => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-sm">${s.slug}</td>
                        <td class="px-6 py-4 font-medium">${s.name}</td>
                        <td class="px-6 py-4 text-gray-600">${s.display || '-'}</td>
                        <td class="px-6 py-4 text-gray-600">${s.display_cn || '-'}</td>
                        <td class="px-6 py-4">
                            ${(s.hot_tags || []).length > 0 
                                ? s.hot_tags.map(t => `<span class="inline-block bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs mr-1 mb-1">${t}</span>`).join('')
                                : '<span class="text-gray-400 text-xs">-</span>'}
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="toggleSeries('${s.slug}')" class="px-2 py-1 rounded text-xs font-medium ${s.enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}">
                                ${s.enabled ? '启用' : '禁用'}
                            </button>
                        </td>
                        <td class="px-6 py-4">
                            <button onclick="editSeries('${s.slug}')" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteSeries('${s.slug}')" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `).join('');
            }

            // 移动端卡片
            const cards = document.getElementById('seriesCards');
            if (list.length === 0) {
                cards.innerHTML = '<div class="p-4 text-center text-gray-500">暂无系列</div>';
            } else {
                cards.innerHTML = list.map(s => `
                    <div class="p-4 border-t border-gray-200">
                        <div class="flex items-center justify-between mb-2">
                            <div>
                                <span class="font-medium text-gray-800">${s.name}</span>
                                <span class="ml-2 text-xs text-gray-400">${s.slug}</span>
                            </div>
                            <button onclick="toggleSeries('${s.slug}')" class="px-2 py-1 rounded text-xs font-medium ${s.enabled ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500'}">
                                ${s.enabled ? '启用' : '禁用'}
                            </button>
                        </div>
                        <div class="text-sm text-gray-500 mb-2">
                            ${s.display || ''} / ${s.display_cn || ''}
                        </div>
                        ${(s.hot_tags || []).length > 0 
                            ? `<div class="flex flex-wrap gap-1 mb-2">${s.hot_tags.map(t => `<span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs">${t}</span>`).join('')}</div>`
                            : ''}
                        <div class="flex justify-end gap-2">
                            <button onclick="editSeries('${s.slug}')" class="text-blue-600 hover:text-blue-800 text-sm"><i class="fas fa-edit mr-1"></i>编辑</button>
                            <button onclick="deleteSeries('${s.slug}')" class="text-red-600 hover:text-red-800 text-sm"><i class="fas fa-trash mr-1"></i>删除</button>
                        </div>
                    </div>
                `).join('');
            }
        }

        function showModal(series = null) {
            document.getElementById('seriesModal').classList.remove('hidden');
            document.getElementById('seriesModal').classList.add('flex');
            document.getElementById('modalTitle').textContent = series ? '编辑系列' : '添加系列';
            
            if (series) {
                document.getElementById('seriesSlug').value = series.slug;
                document.getElementById('seriesSlugInput').value = series.slug;
                document.getElementById('seriesSlugInput').disabled = true;
                document.getElementById('seriesName').value = series.name;
                document.getElementById('seriesDisplay').value = series.display || '';
                document.getElementById('seriesDisplayCn').value = series.display_cn || '';
                document.getElementById('seriesHotTags').value = (series.hot_tags || []).join(', ');
                document.getElementById('seriesEnabled').checked = series.enabled !== false;
            } else {
                document.getElementById('seriesForm').reset();
                document.getElementById('seriesSlug').value = '';
                document.getElementById('seriesSlugInput').disabled = false;
                document.getElementById('seriesEnabled').checked = true;
            }
        }

        function closeModal() {
            document.getElementById('seriesModal').classList.add('hidden');
            document.getElementById('seriesModal').classList.remove('flex');
        }

        function editSeries(slug) {
            const series = seriesList.find(s => s.slug === slug);
            if (series) showModal(series);
        }

        async function deleteSeries(slug) {
            if (!confirm('确定要删除这个系列吗？')) return;
            
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('slug', slug);
            
            const res = await fetch('../../api/admin/series.php', { method: 'POST', body: formData });
            const data = await res.json();
            
            if (data.success) {
                loadSeries();
            } else {
                alert(data.message || '删除失败');
            }
        }

        async function toggleSeries(slug) {
            const formData = new FormData();
            formData.append('action', 'toggle');
            formData.append('slug', slug);
            
            const res = await fetch('../../api/admin/series.php', { method: 'POST', body: formData });
            const data = await res.json();
            
            if (data.success) {
                loadSeries();
            } else {
                alert(data.message || '操作失败');
            }
        }

        document.getElementById('seriesForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const slugInput = document.getElementById('seriesSlugInput');
            const slug = slugInput.value.trim().toLowerCase().replace(/[^a-z0-9-]/g, '');
            const name = document.getElementById('seriesName').value.trim();
            const display = document.getElementById('seriesDisplay').value.trim();
            const display_cn = document.getElementById('seriesDisplayCn').value.trim();
            const hot_tags = document.getElementById('seriesHotTags').value
                .split(',')
                .map(t => t.trim())
                .filter(t => t);
            const enabled = document.getElementById('seriesEnabled').checked;
            
            if (!slug || !name) {
                alert('请填写必填项');
                return;
            }
            
            const formData = new FormData();
            const isEdit = document.getElementById('seriesSlug').value;
            formData.append('action', isEdit ? 'update' : 'create');
            if (isEdit) {
                formData.append('id', isEdit);
            }
            formData.append('slug', slug);
            formData.append('name', name);
            formData.append('display', display);
            formData.append('display_cn', display_cn);
            formData.append('hot_tags', hot_tags.join(','));
            formData.append('enabled', enabled ? '1' : '0');
            
            const res = await fetch('../../api/admin/series.php', { method: 'POST', body: formData });
            const data = await res.json();
            
            if (data.success) {
                closeModal();
                loadSeries();
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
        loadSeries();
    </script>
</body>
</html>
