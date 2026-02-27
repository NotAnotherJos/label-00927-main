# ThinkPHP8 后端服务

基于 ThinkPHP 8.0 的多应用分层架构后端 API 服务。

## 环境要求

- PHP >= 8.0
- MySQL 8.0
- Redis 7
- Composer 2.x

## 快速启动

### 方式一：Docker 启动（推荐）

```bash
# 在项目根目录执行，一键启动所有服务
docker-compose up --build -d

# 查看服务状态
docker-compose ps

# 访问 API
curl http://localhost:8081/admin/login -X POST \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

### 方式二：本地开发启动

```bash
# 1. 安装依赖
composer install

# 2. 配置环境
cp .env.example .env
# 编辑 .env 配置数据库和 Redis

# 3. 导入数据库
mysql -u root -p < database/schema.sql

# 4. 启动开发服务器
php think run

# 或指定端口
php think run -p 8080
```

服务启动后访问：http://localhost:8000

## 安装

```bash
# 安装依赖
composer install

# 复制环境配置
cp .env.example .env

# 修改 .env 中的数据库和 Redis 配置
```

## 目录结构

```
backend/                    # 后端项目根目录
├── app/                    # 应用目录
│   ├── admin/             # 后台管理应用
│   │   ├── Controller/    # 控制器
│   │   ├── Service/       # 服务层
│   │   ├── Model/         # 模型
│   │   ├── Middleware/    # 应用中间件
│   │   ├── Dto/           # 数据传输对象
│   │   ├── Enum/          # 枚举类
│   │   ├── Exception/     # 异常类
│   │   └── Validate/      # 验证器
│   ├── home/              # 前台应用
│   ├── user/              # 用户控制台应用
│   ├── common/            # 公共模块
│   │   ├── exception/     # 全局异常处理
│   │   ├── helper/        # 工具类
│   │   ├── model/         # 公共模型
│   │   └── service/       # 公共服务
│   └── command/           # 控制台命令
├── middleware/            # 全局中间件
├── config/                # 配置文件
├── crontab/               # 定时任务类
├── route/                 # 路由定义（按应用拆分）
│   ├── admin.php          # 后台路由
│   ├── home.php           # 前台路由
│   └── user.php           # 用户路由
├── database/              # 数据库文件
├── extend/                # 扩展类库 (JWT工具等)
├── public/                # Web根目录
├── runtime/               # 运行时目录
├── vendor/                # Composer依赖
├── tests/                 # 测试用例
├── docker/                # Docker配置
├── Dockerfile             # Docker构建文件
└── composer.json          # 依赖配置
```

## 核心依赖

| 依赖包 | 版本 | 用途 |
|-------|------|------|
| topthink/framework | ^8.0 | ThinkPHP 框架 |
| topthink/think-orm | ^3.0 | ORM 数据库操作 |
| topthink/think-multi-app | ^1.0 | 多应用支持 |
| topthink/think-cache | ^3.0 | 缓存扩展 |
| topthink/think-crontab | ^1.0 | 定时任务 |
| firebase/php-jwt | ^6.0 | JWT 认证 |
| zircote/swagger-php | ^4.0 | API 文档生成 |

## 运行测试

```bash
# 运行所有测试
composer test
# 或
php vendor/bin/phpunit

# 运行单元测试
php vendor/bin/phpunit tests/Unit

# 运行功能测试
php vendor/bin/phpunit tests/Feature

# 生成覆盖率报告
composer test-coverage
```

## 定时任务

```bash
# 手动执行清理缓存
php think crontab:clear-cache

# 手动执行日志归档
php think crontab:archive-logs

# 启动定时任务调度器 (生产环境)
php think crontab:run
```

## API 接口

### 认证
- `POST /admin/login` - 登录
- `POST /admin/refresh` - 刷新 Token

### 用户管理
- `GET /admin/users` - 用户列表
- `POST /admin/users` - 创建用户
- `PUT /admin/users/:id` - 更新用户
- `DELETE /admin/users/:id` - 删除用户
- `POST /admin/users/:id/status` - 设置状态

### 角色管理
- `GET /admin/roles` - 角色列表
- `POST /admin/roles` - 创建角色
- `PUT /admin/roles/:id` - 更新角色
- `DELETE /admin/roles/:id` - 删除角色

### 部门管理
- `GET /admin/departments` - 部门列表
- `POST /admin/departments` - 创建部门
- `PUT /admin/departments/:id` - 更新部门
- `DELETE /admin/departments/:id` - 删除部门

### 菜单管理
- `GET /admin/menus` - 菜单列表
- `POST /admin/menus` - 创建菜单
- `PUT /admin/menus/:id` - 更新菜单
- `DELETE /admin/menus/:id` - 删除菜单

### 权限管理
- `GET /admin/permissions` - 权限列表
- `POST /admin/permissions` - 创建权限
- `PUT /admin/permissions/:id` - 更新权限
- `DELETE /admin/permissions/:id` - 删除权限

### 个人中心
- `GET /admin/profile` - 获取个人信息
- `PUT /admin/profile` - 更新个人信息
- `PUT /admin/profile/password` - 修改密码

### 操作日志
- `GET /admin/logs` - 日志列表
- `GET /admin/logs/:id` - 日志详情

### API 文档
- `GET /admin/swagger` - Swagger JSON
- `GET /admin/swagger/ui` - Swagger UI

## 代码分层

```
Controller (控制器)
    ↓ 参数验证、请求处理
Service (服务层)
    ↓ 业务逻辑
Model (模型层)
    ↓ 数据操作
Database (数据库)
```

## 许可证

Apache-2.0
