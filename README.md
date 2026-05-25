# 小森的潮物 - 潮鞋电商网站

一个基于 PHP + Tailwind CSS 构建的潮鞋电商网站，支持商品展示、购物车、订单管理等功能。

![版本](https://img.shields.io/badge/版本-1.0.0-blue)
![PHP](https://img.shields.io/badge/PHP-7.4+-green)
![License](https://img.shields.io/badge/License-MIT-yellow)

---

## 📋 目录

- [功能特性](#功能特性)
- [技术栈](#技术栈)
- [环境要求](#环境要求)
- [安装部署](#安装部署)
- [使用说明](#使用说明)
- [管理员后台](#管理员后台)
- [修改管理员账户和密码](#修改管理员账户和密码) ⭐
- [项目结构](#项目结构)
- [数据说明](#数据说明)
- [常见问题](#常见问题)
- [更新日志](#更新日志)

---

## ✨ 功能特性

### 用户端功能
- 🏠 **首页展示** - 按系列分组展示商品（Nike、Adidas、Converse）
- 🔍 **商品搜索** - 支持按名称搜索商品
- 🏷️ **标签筛选** - 点击标签快速筛选商品
- 📱 **商品详情** - 多图轮播、详细介绍、价格展示
- 🛒 **购物车** - 添加商品、数量调整、一键结算
- 📂 **分类浏览** - 按标签分类查看商品
- 👤 **个人中心**
  - 用户信息编辑（头像、昵称）
  - 收货地址管理
  - 订单查看与管理
  - 商品收藏
  - 联系客服

### 管理后台功能
- 📊 **控制台** - 数据统计（商品数、用户数、订单数、销售额）
- 📦 **商品管理** - 添加、编辑、删除商品，支持多图上传
- 🏷️ **系列管理** - 配置商品系列和热门标签
- 👥 **用户管理** - 查看注册用户列表
- 📋 **订单管理** - 查看订单、更新订单状态

---

## 🛠️ 技术栈

| 层级 | 技术 |
|------|------|
| 后端 | PHP (原生，无框架) |
| 前端 | Tailwind CSS + 原生 JavaScript |
| 数据存储 | JSON 文件 |
| UI 图标 | Font Awesome |
| 响应式 | 移动端优先设计 |

---

## 📦 环境要求

- **PHP**: 7.4 或更高版本
- **Web 服务器**: Apache / Nginx / IIS
- **浏览器**: Chrome、Firefox、Safari、Edge 等现代浏览器

### 推荐配置
- PHP 8.0+
- 开启 `file_uploads` (用于图片上传)
- 开启 `session` 支持

---

## 🚀 安装部署

### 方法一：直接部署到 Web 服务器

1. **下载代码**
   ```bash
   git clone https://github.com/你的用户名/仓库名.git
   cd 仓库名
   ```

2. **配置 Web 服务器**
   - 将项目目录设置为网站根目录
   - 确保 PHP 已正确配置

3. **设置目录权限**（Linux/Mac）
   ```bash
   chmod -R 755 data/
   chmod -R 755 uploads/
   ```

4. **访问网站**
   - 前台：`http://你的域名/`
   - 后台：`http://你的域名/admin/login.php`

### 方法二：本地开发环境（XAMPP/WAMP）

1. 安装 [XAMPP](https://www.apachefriends.org/) 或 [WAMP](https://www.wampserver.com/)
2. 将项目文件夹复制到 `htdocs` 目录
3. 启动 Apache 服务
4. 访问 `http://localhost/项目文件夹名/`

### 方法三：Docker 部署

```dockerfile
FROM php:8.0-apache
COPY . /var/www/html/
RUN chown -R www-data:www-data /var/www/html/data /var/www/html/uploads
```

---

## 📖 使用说明

### 普通用户

#### 1. 浏览商品
- 打开首页即可看到所有商品
- 点击顶部标签可筛选特定系列商品
- 点击商品卡片进入详情页

#### 2. 添加到购物车
- 在商品详情页点击"加入购物车"
- 购物车数据保存在浏览器本地

#### 3. 结算下单
- 进入购物车页面
- 确认商品和数量
- 点击"去结算"
- 填写收货信息并选择支付方式
- 提交订单

#### 4. 查看订单
- 进入"我的"页面
- 点击"我买的"
- 查看订单状态（待验证、待收货、已完成）

#### 5. 个人设置
- 点击头像区域编辑个人资料
- 点击收货地址区域管理地址

### 订单状态说明

| 状态 | 说明 |
|------|------|
| 待验证 | 订单已提交，等待管理员确认 |
| 待发货 | 订单已确认，准备发货 |
| 待收货 | 商品已发货，等待收货 |
| 已完成 | 订单已完成 |

---

## 🔐 管理员后台

### 登录信息

| 项目 | 值 |
|------|-----|
| 后台地址 | `http://你的域名/admin/login.php` |
| 用户名 | `inkt` |
| 密码 | `inkt114514` |

### 后台功能详解

#### 1. 控制台
- 查看网站整体数据
- 商品总数、用户总数、订单总数
- 总销售额统计
- 订单状态分布
- 最近订单列表

#### 2. 商品管理
- **添加商品**
  - 填写商品名称、价格
  - 上传多张商品图片
  - 设置标签和跳转标签
  - 填写详细描述
- **编辑商品** - 修改已有商品信息
- **删除商品** - 移除商品
- **清理图片** - 删除未使用的上传图片

#### 3. 系列管理
- 配置商品系列（如 Nike、Adidas）
- 设置系列热门标签
- 启用/禁用系列

#### 4. 用户管理
- 查看注册用户列表
- 查看用户注册时间

#### 5. 订单管理
- 查看所有订单
- 更新订单状态
- 查看订单详情

---

## 📁 项目结构

```
├── index.php              # 网站首页
├── cart.php               # 购物车页面
├── category.php           # 分类页面
├── product.php            # 商品详情页
├── me.php                 # 个人中心
├── admin/                 # 管理后台
│   ├── index.php          # 控制台
│   ├── login.php          # 登录页
│   ├── products.php       # 商品管理
│   ├── series.php         # 系列管理
│   ├── users.php          # 用户管理
│   └── orders.php         # 订单管理
├── api/                   # API 接口
│   ├── data.php           # 数据操作函数
│   ├── create_order.php   # 创建订单
│   ├── orders.php         # 订单查询
│   ├── admin/             # 管理员 API
│   └── user/              # 用户 API
├── assets/                # 静态资源
│   ├── style.css          # 自定义样式
│   └── *.png              # 支付二维码等
├── data/                  # 数据文件（JSON）
│   ├── products.json      # 商品数据
│   ├── series.json        # 系列配置
│   ├── users.json         # 用户数据
│   ├── orders.json        # 订单数据
│   └── users/             # 用户详情目录
├── uploads/               # 上传的图片
└── README.md              # 本文件
```

---

## 💾 数据说明

### 数据存储方式
本项目使用 JSON 文件存储数据，无需数据库。

### 主要数据文件

| 文件 | 说明 | 初始状态 |
|------|------|----------|
| `data/products.json` | 商品数据 | 包含 65 款示例商品 |
| `data/series.json` | 系列配置 | Nike、Adidas、Converse |
| `data/users.json` | 用户数据 | 仅管理员账号 |
| `data/orders.json` | 订单数据 | 空数组 |
| `data/users/*.json` | 用户详情 | 仅管理员 |

### 商品数据结构
```json
{
  "id": 1,
  "name": "【小森精选】Nike AJ1 高帮 爱马仕",
  "price": 318,
  "image": "/uploads/img_xxx.jpg,/uploads/img_yyy.jpg",
  "tags": ["AJ1", "Nike", "高帮", "板鞋"],
  "is_new": false,
  "jump_tag": "AJ1高",
  "description": "商品详细描述..."
}
```

### 管理员账号
```json
{
  "id": 1,
  "username": "inkt",
  "password": "inkt114514",
  "role": "admin"
}
```

---

## 🔧 修改管理员账户和密码

### 方法一：直接编辑文件（推荐）

#### 1. 找到用户数据文件
打开 `data/users.json` 文件，内容如下：

```json
[
    {
        "id": 1,
        "username": "inkt",
        "password": "inkt114514",
        "role": "admin",
        "created_at": "2026-01-01 00:00:00"
    }
]
```

#### 2. 修改用户名
将 `"username": "inkt"` 中的 `inkt` 改为你的新用户名，例如：
```json
"username": "yourname"
```

#### 3. 修改密码
将 `"password": "inkt114514"` 中的 `inkt114514` 改为你的新密码，例如：
```json
"password": "yourpassword123"
```

#### 4. 保存文件
保存修改后的 `data/users.json` 文件即可生效。

---

### 方法二：通过管理后台（仅修改个人信息）

目前后台暂不支持直接修改登录用户名和密码，请使用**方法一**修改。

---

### 完整修改示例

**修改前：**
```json
[
    {
        "id": 1,
        "username": "inkt",
        "password": "inkt114514",
        "role": "admin",
        "created_at": "2026-01-01 00:00:00"
    }
]
```

**修改后：**
```json
[
    {
        "id": 1,
        "username": "xiaosen",
        "password": "MySecurePassword2026!",
        "role": "admin",
        "created_at": "2026-01-01 00:00:00"
    }
]
```

---

### ⚠️ 安全建议

1. **立即修改默认密码** - 默认密码 `inkt114514` 是公开的，请务必修改
2. **使用强密码** - 建议包含大小写字母、数字和特殊符号，长度至少 8 位
3. **定期更换密码** - 建议每 3-6 个月更换一次密码
4. **不要泄露账号** - 管理员账号具有最高权限，请妥善保管

---

### 修改管理员个人资料（昵称、头像）

如需修改管理员的昵称、头像等个人信息：

1. 打开 `data/users/1.json` 文件
2. 修改相应字段：

```json
{
    "id": 1,
    "username": "inkt",
    "nickname": "小森",           // 修改昵称
    "avatar": "图片URL地址",      // 修改头像URL
    "address": {
        "name": "收件人姓名",
        "phone": "13800138000",
        "detail": "详细收货地址"
    },
    "created_at": "2026-01-01 00:00:00"
}
```

---

## ❓ 常见问题

### Q1: 修改后无法登录？
- 检查 JSON 格式是否正确（注意逗号、引号）
- 确保文件保存为 UTF-8 编码
- 检查是否有语法错误，可使用 [JSONLint](https://jsonlint.com/) 验证

### Q2: 忘记密码怎么办？
直接编辑 `data/users.json` 文件，将密码字段改为你能记住的新密码。

### Q3: 如何添加多个管理员？
在 `data/users.json` 数组中添加新用户对象：

```json
[
    {
        "id": 1,
        "username": "admin1",
        "password": "password1",
        "role": "admin",
        "created_at": "2026-01-01 00:00:00"
    },
    {
        "id": 2,
        "username": "admin2",
        "password": "password2",
        "role": "admin",
        "created_at": "2026-01-02 00:00:00"
    }
]
```

### Q4: 如何添加新商品？
1. 登录管理后台
2. 进入"商品管理"
3. 点击"添加商品"
4. 填写信息并上传图片

### Q3: 图片上传失败怎么办？
- 检查 `uploads/` 目录是否有写入权限
- 检查 PHP 的 `upload_max_filesize` 配置
- 确保上传的图片格式正确（jpg、jpeg、png）

### Q4: 如何备份数据？
直接复制 `data/` 目录和 `uploads/` 目录即可。

### Q5: 如何清空所有数据？
- 订单：`data/orders.json` 设置为 `[]`
- 用户（保留管理员）：删除 `data/users/` 下除 `1.json` 外的文件
- 商品：清空 `data/products.json`

### Q6: 网站显示乱码？
确保服务器和 PHP 配置使用 UTF-8 编码。

---

## 📝 更新日志

### v1.0.0 (2026-01-01)
- ✅ 初始版本发布
- ✅ 商品展示功能
- ✅ 购物车功能
- ✅ 订单管理功能
- ✅ 用户系统
- ✅ 管理后台
- ✅ 多图上传支持

---

## 🤝 贡献指南

欢迎提交 Issue 和 Pull Request！

1. Fork 本仓库
2. 创建你的特性分支 (`git checkout -b feature/AmazingFeature`)
3. 提交你的修改 (`git commit -m 'Add some AmazingFeature'`)
4. 推送到分支 (`git push origin feature/AmazingFeature`)
5. 打开一个 Pull Request

---

## 📄 开源协议

本项目基于 [MIT](LICENSE) 协议开源。


---

## 🙏 致谢

- [Tailwind CSS](https://tailwindcss.com/) - 优秀的 CSS 框架
- [Font Awesome](https://fontawesome.com/) - 图标库
- [Unsplash](https://unsplash.com/) - 示例图片

---

> 💡 **提示**：首次使用前请务必修改默认管理员密码！
