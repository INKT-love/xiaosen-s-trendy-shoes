<!DOCTYPE html>
<html lang="zh-CN">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>管理员登录 - 小森潮鞋</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body {
            background: linear-gradient(135deg, #1a1a2e 0%, #16213e 100%);
            min-height: 100vh;
        }
    </style>
</head>
<body class="flex items-center justify-center p-4">
    <div class="w-full max-w-md">
        <div class="bg-white/10 backdrop-blur-md rounded-2xl shadow-2xl p-6 md:p-8 border border-white/20">
            <div class="text-center mb-6 md:mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-white mb-2">管理员登录</h1>
                <p class="text-gray-400 text-sm md:text-base">小森潮鞋管理系统</p>
            </div>

            <form id="loginForm" class="space-y-4 md:space-y-6">
                <div>
                    <label class="block text-gray-300 mb-2 text-sm md:text-base">用户名</label>
                    <input type="text" name="username" required
                        class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition text-sm md:text-base">
                </div>

                <div>
                    <label class="block text-gray-300 mb-2 text-sm md:text-base">密码</label>
                    <input type="password" name="password" required
                        class="w-full px-3 md:px-4 py-2.5 md:py-3 rounded-lg bg-white/10 border border-white/20 text-white placeholder-gray-400 focus:outline-none focus:border-orange-500 focus:ring-1 focus:ring-orange-500 transition text-sm md:text-base">
                </div>

                <div id="errorMsg" class="hidden text-red-400 text-sm text-center bg-red-500/20 rounded-lg py-2"></div>

                <button type="submit"
                    class="w-full py-2.5 md:py-3 px-4 bg-orange-600 hover:bg-orange-700 text-white font-semibold rounded-lg transition duration-200 transform hover:scale-[1.02] active:scale-[0.98] text-sm md:text-base">
                    登 录
                </button>
            </form>

            <div class="mt-4 md:mt-6 text-center">
                <a href="../../index.php" class="text-gray-400 hover:text-orange-400 text-sm transition">
                    返回首页
                </a>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('loginForm').addEventListener('submit', async function(e) {
            e.preventDefault();
            const formData = new FormData(this);
            const errorMsg = document.getElementById('errorMsg');
            errorMsg.classList.add('hidden');

            try {
                const response = await fetch('../../api/admin/login.php', {
                    method: 'POST',
                    body: formData
                });
                const data = await response.json();

                if (data.success) {
                    window.location.href = 'index.php';
                } else {
                    errorMsg.textContent = data.message || '登录失败';
                    errorMsg.classList.remove('hidden');
                }
            } catch (err) {
                errorMsg.textContent = '网络错误，请稍后重试';
                errorMsg.classList.remove('hidden');
            }
        });
    </script>
</body>
</html>
