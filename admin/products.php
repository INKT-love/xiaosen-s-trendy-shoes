<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>商品管理 - 小森潮鞋</title>
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
                <a href="products.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300 active" data-page="products"><i class="fas fa-box w-6"></i><span>商品管理</span></a>
                <a href="series.php" class="sidebar-link flex items-center px-4 py-3 rounded-lg text-gray-300" data-page="series"><i class="fas fa-tags w-6"></i><span>系列管理</span></a>
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
                    <h2 class="text-lg font-semibold text-gray-800">商品管理</h2>
                </div>
                <div class="flex gap-2">
                    <button onclick="cleanupImages()" class="bg-gray-600 hover:bg-gray-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                        <i class="fas fa-trash-alt mr-1"></i>清理图片
                    </button>
                    <button onclick="showModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-3 py-1.5 rounded-lg text-sm transition">
                        <i class="fas fa-plus mr-1"></i>添加
                    </button>
                </div>
            </header>

            <!-- 桌面端顶部栏 -->
            <header class="bg-white shadow-sm px-4 md:px-8 py-4 hidden md:flex justify-between items-center page-header">
                <h2 class="text-xl font-semibold text-gray-800">商品管理</h2>
                <div class="flex gap-3">
                    <button onclick="cleanupImages()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-trash-alt mr-2"></i>清理图片
                    </button>
                    <button onclick="showModal()" class="bg-orange-600 hover:bg-orange-700 text-white px-4 py-2 rounded-lg transition">
                        <i class="fas fa-plus mr-2"></i>添加商品
                    </button>
                </div>
            </header>

            <div class="p-4 md:p-8">
                <!-- 搜索 -->
                <div class="mb-4 md:mb-6 flex gap-2 md:gap-4">
                    <input type="text" id="searchInput" placeholder="搜索商品名称..."
                        class="px-3 md:px-4 py-2 border rounded-lg w-full md:w-64 focus:outline-none focus:border-orange-500 text-sm">
                </div>

                <!-- 商品列表 - 移动端卡片式，桌面端表格 -->
                <div class="bg-white rounded-lg shadow overflow-hidden">
                    <!-- 桌面端表格 -->
                    <div class="hidden md:block table-wrapper">
                        <table class="w-full">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">图片</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">名称</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">价格</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">跳转标签</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">状态</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                                </tr>
                            </thead>
                            <tbody id="productTable" class="divide-y divide-gray-200">
                            </tbody>
                        </table>
                    </div>
                    <!-- 移动端卡片列表 -->
                    <div id="productCards" class="md:hidden divide-y divide-gray-200">
                    </div>
                </div>
            </div>
        </main>
    </div>

    <!-- 添加/编辑弹窗 -->
    <div id="productModal" class="fixed inset-0 bg-black/50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg w-full max-w-2xl max-h-[90vh] overflow-y-auto m-4">
            <div class="p-6 border-b flex justify-between items-center">
                <h3 class="text-lg font-semibold" id="modalTitle">添加商品</h3>
                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-600"><i class="fas fa-times text-xl"></i></button>
            </div>
            <form id="productForm" class="p-6 space-y-4">
                <input type="hidden" id="productId">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">商品名称</label>
                    <input type="text" id="productName" required class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">价格</label>
                        <input type="number" id="productPrice" required min="0" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">商品图片（可多选）</label>
                        <div class="flex items-center gap-2">
                            <label class="cursor-pointer bg-gray-50 border border-dashed border-gray-300 rounded-lg px-3 py-2 hover:bg-gray-100 transition text-center text-sm text-gray-600">
                                <i class="fas fa-images mr-1"></i>选择图片
                                <input type="file" id="productImageFile" accept="image/*" multiple class="hidden">
                            </label>
                            <button type="button" onclick="uploadAllImages()" id="uploadAllBtn" class="hidden px-3 py-2 bg-orange-500 text-white rounded-lg text-sm hover:bg-orange-600">
                                <i class="fas fa-cloud-upload-alt mr-1"></i>上传全部
                            </button>
                        </div>
                        <div id="imagePreview" class="mt-2 hidden">
                            <div class="flex flex-wrap gap-2" id="imagePreviewList"></div>
                            <button type="button" onclick="clearAllImages()" class="mt-2 text-red-500 text-xs">清空全部</button>
                        </div>
                        <input type="hidden" id="productImage">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">标签（逗号分隔）</label>
                    <input type="text" id="productTags" placeholder="如: Nike, AJ, 高帮" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <div class="flex items-center gap-2">
                    <input type="checkbox" id="productIsNew" class="w-4 h-4 text-orange-600 rounded">
                    <label for="productIsNew" class="text-sm text-gray-700">新品</label>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">跳转标签（后台管理用，不在前端显示）</label>
                    <input type="text" id="productJumpTag" placeholder="如: hot, recommend, sale" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">商品详情（文字介绍，显示在详情页底部）</label>
                    <textarea id="productDescription" rows="5" placeholder="输入商品文字介绍，支持换行" class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:border-orange-500 resize-y"></textarea>
                </div>
                <div class="flex justify-end gap-3 pt-4">
                    <button type="button" onclick="closeModal()" class="px-4 py-2 border rounded-lg hover:bg-gray-50">取消</button>
                    <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-lg hover:bg-orange-700">保存</button>
                </div>
            </form>
        </div>
    </div>

    <?php
        $adminDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        $siteBase = str_replace('\\', '/', rtrim(dirname($adminDir), '/'));
        if ($siteBase === '' || $siteBase === '/' || $siteBase === '.') $siteBase = '';
    ?>
    <script>
        var SITE_BASE = <?php echo json_encode($siteBase); ?>;
        let products = [];

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

        async function loadProducts() {
            const res = await fetch('../../api/admin/products.php?action=list');
            const data = await res.json();
            products = data.products || [];
            renderProducts(products);
        }

        // 图片地址：支持相对路径、根路径、完整 URL；子目录部署时用 SITE_BASE
        function imageUrl(s) {
            if (!s || typeof s !== 'string') return '';
            const url = (typeof s === 'string' && s.indexOf(',') >= 0 ? s.split(',')[0] : s).trim();
            if (!url) return '';
            if (url.startsWith('http')) return url;
            const path = url.startsWith('/') ? url : '/' + url;
            return (SITE_BASE || '') + path;
        }

        function renderProducts(list) {
            const search = document.getElementById('searchInput').value.toLowerCase();
            const filtered = list.filter(p => p.name.toLowerCase().includes(search));

            // 渲染桌面端表格
            const tbody = document.getElementById('productTable');
            if (filtered.length === 0) {
                tbody.innerHTML = '<tr><td colspan="8" class="px-6 py-8 text-center text-gray-500">暂无商品</td></tr>';
            } else {
                tbody.innerHTML = filtered.map(p => `
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4">${p.id}</td>
                        <td class="px-6 py-4"><img src="${imageUrl(p.image)}" class="w-16 h-16 object-cover rounded" alt=""></td>
                        <td class="px-6 py-4 font-medium">${p.name}</td>
                        <td class="px-6 py-4 text-orange-600 font-bold">¥${p.price}</td>
                        <td class="px-6 py-4">${(p.tags || []).map(t => `<span class="inline-block bg-gray-100 px-2 py-0.5 rounded text-xs mr-1">${t}</span>`).join('')}</td>
                        <td class="px-6 py-4">${p.jump_tag ? `<span class="inline-block bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs">${p.jump_tag}</span>` : '<span class="text-gray-400 text-xs">-</span>'}</td>
                        <td class="px-6 py-4">${p.is_new ? '<span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs">新品</span>' : '<span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">普通</span>'}</td>
                        <td class="px-6 py-4">
                            <button onclick="editProduct(${p.id})" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fas fa-edit"></i></button>
                            <button onclick="deleteProduct(${p.id})" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                        </td>
                    </tr>
                `).join('');
            }

            // 渲染移动端卡片
            const cards = document.getElementById('productCards');
            if (filtered.length === 0) {
                cards.innerHTML = '<div class="p-4 text-center text-gray-500">暂无商品</div>';
            } else {
                cards.innerHTML = filtered.map(p => `
                    <div class="p-4 flex gap-4">
                        <img src="${imageUrl(p.image)}" class="w-20 h-20 object-cover rounded-lg shrink-0" alt="">
                        <div class="flex-1 min-w-0">
                            <div class="font-medium text-gray-800 truncate">${p.name}</div>
                            <div class="text-orange-600 font-bold mt-1">¥${p.price}</div>
                            <div class="flex flex-wrap gap-1 mt-2">${(p.tags || []).map(t => `<span class="bg-gray-100 px-2 py-0.5 rounded text-xs">${t}</span>`).join('')}</div>
                            ${p.jump_tag ? `<div class="mt-2"><span class="bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs">跳转: ${p.jump_tag}</span></div>` : ''}
                            <div class="flex items-center justify-between mt-2">
                                <span class="${p.is_new ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-600'} px-2 py-1 rounded text-xs">${p.is_new ? '新品' : '普通'}</span>
                                <div>
                                    <button onclick="editProduct(${p.id})" class="text-blue-600 hover:text-blue-800 mr-3"><i class="fas fa-edit"></i></button>
                                    <button onclick="deleteProduct(${p.id})" class="text-red-600 hover:text-red-800"><i class="fas fa-trash"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                `).join('');
            }
        }

        document.getElementById('searchInput').addEventListener('input', () => renderProducts(products));

        function showModal(product = null) {
            document.getElementById('productModal').classList.remove('hidden');
            document.getElementById('productModal').classList.add('flex');
            document.getElementById('modalTitle').textContent = product ? '编辑商品' : '添加商品';
            clearAllImages(); // 先清除图片预览

            if (product) {
                document.getElementById('productId').value = product.id;
                document.getElementById('productName').value = product.name;
                document.getElementById('productPrice').value = product.price;

                // 支持多张图片（逗号分隔）
                const images = product.image ? product.image.split(',').filter(img => img.trim()) : [];
                uploadedUrls = images;
                document.getElementById('productImage').value = images.join(',');
                if (images.length > 0) {
                    document.getElementById('imagePreview').classList.remove('hidden');
                    renderUploadedImages();
                }

                document.getElementById('productTags').value = (product.tags || []).join(', ');
                document.getElementById('productIsNew').checked = product.is_new;
                document.getElementById('productJumpTag').value = product.jump_tag || '';
                document.getElementById('productDescription').value = product.description || '';
            } else {
                document.getElementById('productForm').reset();
                document.getElementById('productId').value = '';
            }
        }

        function closeModal() {
            document.getElementById('productModal').classList.add('hidden');
            document.getElementById('productModal').classList.remove('flex');
            clearImage();
        }

        function editProduct(id) {
            const product = products.find(p => p.id === id);
            if (product) showModal(product);
        }

        async function deleteProduct(id) {
            if (!confirm('确定要删除这个商品吗？')) return;
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('id', id);
            const res = await fetch('../../api/admin/products.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                loadProducts();
            } else {
                alert(data.message || '删除失败');
            }
        }

        async function cleanupImages() {
            if (!confirm('将删除 uploads 目录下不被任何商品引用的图片，确定要继续吗？')) return;
            
            const btn = event.target;
            const originalText = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>清理中...';
            
            try {
                const res = await fetch('../../api/admin/cleanup_images.php');
                const data = await res.json();
                
                if (data.success) {
                    alert('清理完成！已删除 ' + data.deleted + ' 个文件，释放 ' + data.freed);
                } else {
                    alert(data.message || '清理失败');
                }
            } catch (e) {
                alert('清理失败: ' + e.message);
            } finally {
                btn.disabled = false;
                btn.innerHTML = originalText;
            }
        }

        // 图片上传处理 - 批量上传
        let pendingFiles = []; // 待上传的文件
        let uploadedUrls = []; // 已上传成功的URL

        document.getElementById('productImageFile').addEventListener('change', async function(e) {
            const files = Array.from(e.target.files);
            if (files.length === 0) return;

            // 追加到现有待上传文件（保留之前选择的顺序）
            pendingFiles = [...pendingFiles, ...files];

            // 显示预览区域
            document.getElementById('imagePreview').classList.remove('hidden');

            // 显示待上传按钮
            if (pendingFiles.length > 0) {
                document.getElementById('uploadAllBtn').classList.remove('hidden');
            }

            renderPendingImages();
        });

        // 渲染待上传的图片（带拖拽排序）
        function renderPendingImages() {
            const previewList = document.getElementById('imagePreviewList');
            previewList.innerHTML = '';
            pendingFiles.forEach((file, index) => {
                const reader = new FileReader();
                reader.onload = function(evt) {
                    const div = document.createElement('div');
                    div.className = 'relative cursor-move';
                    div.draggable = true;
                    div.dataset.index = index;
                    div.innerHTML = `
                        <img src="${evt.target.result}" class="w-20 h-20 object-cover rounded-lg border">
                        <span class="absolute top-0 right-0 bg-orange-500 text-white text-xs px-1 rounded">待上传</span>
                        <span class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs px-1 rounded">${index + 1}</span>
                    `;
                    addDragEvents(div);
                    previewList.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }

        // 拖拽排序功能
        let dragSrcEl = null;

        function addDragEvents(div) {
            div.addEventListener('dragstart', function(e) {
                dragSrcEl = this;
                this.classList.add('opacity-50');
                e.dataTransfer.effectAllowed = 'move';
            });

            div.addEventListener('dragover', function(e) {
                if (e.preventDefault) e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                return false;
            });

            div.addEventListener('dragend', function() {
                this.classList.remove('opacity-50');
                document.querySelectorAll('#imagePreviewList > div').forEach(el => el.classList.remove('opacity-50'));
            });

            div.addEventListener('drop', function(e) {
                if (e.stopPropagation) e.stopPropagation();
                if (dragSrcEl !== this) {
                    const fromIndex = parseInt(dragSrcEl.dataset.index);
                    const toIndex = parseInt(this.dataset.index);
                    
                    // 交换数组中的位置
                    const item = pendingFiles.splice(fromIndex, 1)[0];
                    pendingFiles.splice(toIndex, 0, item);
                    
                    renderPendingImages();
                }
                return false;
            });

            div.addEventListener('dragenter', function() {
                this.classList.add('ring-2', 'ring-orange-500');
            });

            div.addEventListener('dragleave', function() {
                this.classList.remove('ring-2', 'ring-orange-500');
            });
        }

        // 上传全部图片
        async function uploadAllImages() {
            if (pendingFiles.length === 0) return;

            const uploadBtn = document.getElementById('uploadAllBtn');
            uploadBtn.disabled = true;
            uploadBtn.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>上传中...';

            const formData = new FormData();
            formData.append('action', 'upload_images');
            pendingFiles.forEach(file => {
                formData.append('images[]', file);
            });

            try {
                const res = await fetch('../../api/admin/upload.php', { method: 'POST', body: formData });
                const text = await res.text();
                let data;
                try {
                    data = JSON.parse(text);
                } catch (e) {
                    alert('服务器返回异常（非 JSON），可能是 PHP 报错。请检查 api/admin/upload.php 或服务器错误日志。');
                    console.error('Upload response:', text);
                    return;
                }

                if (data.success) {
                    uploadedUrls = data.urls;
                    document.getElementById('productImage').value = uploadedUrls.join(',');
                    renderUploadedImages();
                    alert(data.message || '上传成功');
                } else {
                    alert(data.message || '上传失败');
                }
            } catch (err) {
                alert('上传失败: ' + err.message);
            }

            pendingFiles = [];
            uploadBtn.disabled = false;
            uploadBtn.innerHTML = '<i class="fas fa-cloud-upload-alt mr-1"></i>上传全部';
            uploadBtn.classList.add('hidden');
        }

        // 渲染已上传的图片（带拖拽排序）
        function renderUploadedImages() {
            const previewList = document.getElementById('imagePreviewList');
            previewList.innerHTML = '';
            uploadedUrls.forEach((url, index) => {
                const div = document.createElement('div');
                div.className = 'relative cursor-move';
                div.draggable = true;
                div.dataset.index = index;
                div.innerHTML = `
                    <img src="${imageUrl(url)}" class="w-20 h-20 object-cover rounded-lg border">
                    <button type="button" onclick="removeImage(${index})" class="absolute -top-1 -right-1 w-5 h-5 bg-red-500 text-white rounded-full text-xs flex items-center justify-center">×</button>
                    <span class="absolute bottom-0 left-0 bg-black bg-opacity-50 text-white text-xs px-1 rounded">${index + 1}</span>
                `;
                addDragEventsUploaded(div);
                previewList.appendChild(div);
            });
        }

        // 已上传图片的拖拽排序
        function addDragEventsUploaded(div) {
            div.addEventListener('dragstart', function(e) {
                dragSrcEl = this;
                this.classList.add('opacity-50');
                e.dataTransfer.effectAllowed = 'move';
            });

            div.addEventListener('dragover', function(e) {
                if (e.preventDefault) e.preventDefault();
                e.dataTransfer.dropEffect = 'move';
                return false;
            });

            div.addEventListener('dragend', function() {
                this.classList.remove('opacity-50');
                document.querySelectorAll('#imagePreviewList > div').forEach(el => el.classList.remove('opacity-50'));
            });

            div.addEventListener('drop', function(e) {
                if (e.stopPropagation) e.stopPropagation();
                if (dragSrcEl !== this) {
                    const fromIndex = parseInt(dragSrcEl.dataset.index);
                    const toIndex = parseInt(this.dataset.index);
                    
                    const item = uploadedUrls.splice(fromIndex, 1)[0];
                    uploadedUrls.splice(toIndex, 0, item);
                    
                    document.getElementById('productImage').value = uploadedUrls.join(',');
                    renderUploadedImages();
                }
                return false;
            });

            div.addEventListener('dragenter', function() {
                this.classList.add('ring-2', 'ring-orange-500');
            });

            div.addEventListener('dragleave', function() {
                this.classList.remove('ring-2', 'ring-orange-500');
            });
        }

        // 移除已上传的图片
        function removeImage(index) {
            uploadedUrls.splice(index, 1);
            document.getElementById('productImage').value = uploadedUrls.join(',');
            renderUploadedImages();
        }

        // 清空全部图片
        function clearAllImages() {
            pendingFiles = [];
            uploadedUrls = [];
            document.getElementById('productImageFile').value = '';
            document.getElementById('productImage').value = '';
            document.getElementById('imagePreview').classList.add('hidden');
            document.getElementById('uploadAllBtn').classList.add('hidden');
            document.getElementById('imagePreviewList').innerHTML = '';
        }

        // 保留旧函数名兼容
        function clearImage() {
            clearAllImages();
        }

        document.getElementById('productForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const id = document.getElementById('productId').value;
            const formData = new FormData();
            formData.append('action', id ? 'update' : 'create');
            if (id) formData.append('id', id);
            formData.append('name', document.getElementById('productName').value);
            formData.append('price', document.getElementById('productPrice').value);
            formData.append('image', document.getElementById('productImage').value);
            formData.append('tags', document.getElementById('productTags').value);
            formData.append('is_new', document.getElementById('productIsNew').checked ? '1' : '0');
            formData.append('jump_tag', document.getElementById('productJumpTag').value);
            formData.append('description', document.getElementById('productDescription').value);

            const res = await fetch('../../api/admin/products.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (data.success) {
                closeModal();
                loadProducts();
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
        loadProducts();
    </script>
</body>
</html>
