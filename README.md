# ThinkPHP8 多应用分层架构通用系统

基于ThinkPHP8框架构建的企业级多应用分层架构系统，实现了完整的RBAC权限管理、数据权限控制、JWT认证、操作审计、缓存优化等功能。

## How to Run

### 前置要求
- Docker 20.10+
- Docker Compose 2.0+

### 一键启动

```bash
# 克隆项目
git clone <repository-url>
cd project

# 一键启动所有服务（自动构建、初始化数据库、安装依赖）
docker-compose up --build -d

# 查看服务状态
docker-compose ps
```

启动完成后访问：
- 后端 API：http://localhost:8081
- API 文档：http://localhost:8081/admin/swagger/ui

### 本地开发启动（不使用 Docker）

```bash
cd backend

# 安装依赖
composer install

# 配置环境
cp .env.example .env
# 编辑 .env 配置数据库和 Redis 连接

# 导入数据库
mysql -u root -p your_database < database/schema.sql

# 启动开发服务器
php think run -p 8081
```

### 停止服务
```bash
docker-compose down
```

### 重启服务
```bash
docker-compose restart
```

### 清理所有数据（包括数据库）
```bash
docker-compose down -v
```

## Services

| 服务名称 | 容器名称 | 访问地址 | 说明 |
|---------|---------|---------|------|
| 后端API | thinkphp_backend | http://localhost:8081 | ThinkPHP8 API服务 |
| MySQL | thinkphp_mysql | localhost:3306 | MySQL 8.0 数据库 |
| Redis | thinkphp_redis | localhost:6379 | Redis 7 缓存服务 |

### 服务端口映射
- **8081**: 后端API服务
- **3306**: MySQL数据库
- **6379**: Redis缓存

## 测试账号

### 后台管理员
- **用户名**: `admin`
- **密码**: `password`
- **权限**: 超级管理员（所有权限）

### 登录方式
使用POST请求访问登录接口：
```bash
curl -X POST http://localhost:8081/admin/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

返回的Token需要在后续请求的Header中携带：
```
Authorization: Bearer {token}
```

## 题目内容

### 核心指令
从空文件夹开始，基于ThinkPHP8框架创建一个多应用分层架构的通用系统。

### 技术要求
- **PHP版本**: >=8.0
- **ThinkPHP版本**: 8.0.x
- **必需依赖**:
  - topthink/think-crontab（定时任务）
  - firebase/php-jwt（JWT认证）
  - zircote/swagger-php（接口文档）
  - topthink/think-cache（框架缓存）
  - topthink/think-orm（ORM核心）

### 核心功能
1. **多应用架构**: 后台（Admin）、前台（Home）、用户控制台（User）
2. **JWT认证**: 全程使用JWT实现身份认证，禁用Session
3. **全局异常处理**: 统一异常捕获，标准化JSON响应
4. **Swagger文档**: 自动生成接口文档，支持在线调试
5. **操作审计**: 记录关键操作日志
6. **缓存优化**: Redis缓存高频数据
7. **定时任务**: 基于think-crontab实现
8. **RBAC权限**: 菜单/按钮级权限 + 数据权限（全部/本部门/子部门/本人/自定义）

### 项目特点
- ✅ 严格的分层架构（Controller → Service → Model）
- ✅ 完整的RBAC权限管理系统
- ✅ 多维度数据权限控制
- ✅ JWT无状态认证
- ✅ Redis缓存优化
- ✅ 操作审计日志
- ✅ 定时任务支持
- ✅ Swagger API文档
- ✅ Docker容器化部署
- ✅ 跨平台支持（ARM/X86）

## 项目结构

```
project/                        # 项目根目录
├── backend/                    # 后端项目 (ThinkPHP8)
│   ├── app/                    # 应用目录
│   │   ├── admin/             # 后台管理应用
│   │   │   ├── Controller/    # 控制器
│   │   │   ├── Service/       # 服务层
│   │   │   ├── Model/         # 模型
│   │   │   ├── Middleware/    # 应用中间件
│   │   │   ├── Dto/           # 数据传输对象
│   │   │   ├── Enum/          # 枚举类
│   │   │   ├── Exception/     # 异常类
│   │   │   └── Validate/      # 验证器
│   │   ├── home/              # 前台应用
│   │   ├── user/              # 用户控制台应用
│   │   ├── common/            # 公共模块
│   │   │   ├── exception/     # 全局异常处理
│   │   │   ├── helper/        # 工具类
│   │   │   ├── model/         # 公共模型
│   │   │   └── service/       # 公共服务
│   │   └── command/           # 控制台命令
│   ├── middleware/            # 全局中间件
│   ├── config/                # 配置文件
│   ├── crontab/               # 定时任务类
│   ├── route/                 # 路由定义（按应用拆分）
│   │   ├── admin.php          # 后台路由
│   │   ├── home.php           # 前台路由
│   │   └── user.php           # 用户路由
│   ├── database/              # 数据库文件
│   ├── extend/                # 扩展类库
│   ├── public/                # Web根目录
│   ├── runtime/               # 运行时目录
│   ├── vendor/                # Composer依赖
│   ├── tests/                 # 测试用例
│   ├── docker/                # Docker配置
│   ├── Dockerfile             # Docker构建文件
│   └── composer.json          # PHP依赖配置
├── docs/                       # 项目文档
├── docker-compose.yml          # Docker编排配置
├── .gitignore                  # Git忽略配置
└── README.md                   # 项目说明文档
```

## API接口

### 认证接口
- `POST /admin/login` - 用户登录
- `POST /admin/refresh` - 刷新Token

### 用户管理
- `GET /admin/users` - 用户列表
- `POST /admin/users` - 创建用户
- `PUT /admin/users/:id` - 更新用户
- `DELETE /admin/users/:id` - 删除用户
- `POST /admin/users/:id/status` - 设置用户状态

### 角色管理
- `GET /admin/roles` - 角色列表
- `POST /admin/roles` - 创建角色
- `PUT /admin/roles/:id` - 更新角色
- `DELETE /admin/roles/:id` - 删除角色

### 操作日志
- `GET /admin/logs` - 日志列表
- `GET /admin/logs/:id` - 日志详情

### 文档
- `GET /admin/swagger` - Swagger JSON
- `GET /admin/swagger/ui` - Swagger UI

## 技术栈

- **后端框架**: ThinkPHP 8.0
- **数据库**: MySQL 8.0
- **缓存**: Redis 7 + topthink/think-cache
- **认证**: JWT (firebase/php-jwt)
- **文档**: Swagger (zircote/swagger-php)
- **定时任务**: topthink/think-crontab
- **容器化**: Docker + Docker Compose

## 开发说明

### 添加新的应用

1. 在`app/`目录下创建新的应用目录
2. 创建对应的路由文件在`route/`目录
3. 如需独立配置，在`config/`目录创建应用配置文件

### 数据库迁移

数据库结构定义在`backend/database/schema.sql`中，修改后需要重新构建数据库。

### 日志查看

```bash
# 查看所有服务日志
docker-compose logs -f

# 查看特定服务日志
docker-compose logs -f backend
docker-compose logs -f mysql
```

## 质检测试指南

本节提供一站式测试命令，用于验证 Docker 环境配置和系统功能是否正常。

### 一、快速启动并验证

```bash
# 一键启动所有服务（自动构建镜像、初始化数据库、安装依赖）
docker-compose up --build -d

# 检查所有容器状态（应全部为 running，等待约30秒）
docker-compose ps
```

### 二、运行单元测试

```bash
# 进入后端容器运行完整测试套件
docker-compose exec backend php vendor/bin/phpunit

# 或者运行指定测试目录
docker-compose exec backend php vendor/bin/phpunit tests/Unit
docker-compose exec backend php vendor/bin/phpunit tests/Feature
```

预期结果：所有测试通过，输出类似 `OK (xx tests, xx assertions)`

### 三、API 接口测试（curl 命令）

#### 3.1 健康检查 - 验证服务是否启动

```bash
# 检查后端服务响应
curl -s http://localhost:8081/ | head -20
```

#### 3.2 用户登录 - 获取 JWT Token

```bash
# 登录获取 Token
curl -s -X POST http://localhost:8081/admin/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}'
```

预期返回：
```json
{
  "code": 200,
  "msg": "success",
  "data": {
    "token": "eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9...",
    "expire_time": 1234567890
  }
}
```

#### 3.3 一键测试脚本

将以下脚本保存为 `test_api.sh` 并执行：

```bash
#!/bin/bash
set -e

BASE_URL="http://localhost:8081"
echo "========== API 接口测试 =========="

# 1. 登录获取 Token
echo -e "\n[1] 测试登录接口..."
LOGIN_RESPONSE=$(curl -s -X POST "$BASE_URL/admin/login" \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}')
echo "$LOGIN_RESPONSE"

TOKEN=$(echo $LOGIN_RESPONSE | grep -o '"token":"[^"]*"' | cut -d'"' -f4)
if [ -z "$TOKEN" ]; then
  echo "❌ 登录失败，无法获取 Token"
  exit 1
fi
echo "✅ 登录成功，Token 已获取"

# 2. 获取用户列表
echo -e "\n[2] 测试用户列表接口..."
curl -s -X GET "$BASE_URL/admin/users" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo -e "\n✅ 用户列表获取成功"

# 3. 获取角色列表
echo -e "\n[3] 测试角色列表接口..."
curl -s -X GET "$BASE_URL/admin/roles" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo -e "\n✅ 角色列表获取成功"

# 4. 获取部门列表
echo -e "\n[4] 测试部门列表接口..."
curl -s -X GET "$BASE_URL/admin/departments" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo -e "\n✅ 部门列表获取成功"

# 5. 获取菜单列表
echo -e "\n[5] 测试菜单列表接口..."
curl -s -X GET "$BASE_URL/admin/menus" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo -e "\n✅ 菜单列表获取成功"

# 6. 获取权限列表
echo -e "\n[6] 测试权限列表接口..."
curl -s -X GET "$BASE_URL/admin/permissions" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo -e "\n✅ 权限列表获取成功"

# 7. 获取操作日志
echo -e "\n[7] 测试操作日志接口..."
curl -s -X GET "$BASE_URL/admin/logs" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo -e "\n✅ 操作日志获取成功"

# 8. 获取个人信息
echo -e "\n[8] 测试个人信息接口..."
curl -s -X GET "$BASE_URL/admin/profile" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo -e "\n✅ 个人信息获取成功"

# 9. 刷新 Token
echo -e "\n[9] 测试刷新 Token 接口..."
curl -s -X POST "$BASE_URL/admin/refresh" \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json"
echo -e "\n✅ Token 刷新成功"

echo -e "\n========== 所有接口测试通过 ✅ =========="
```

执行测试：
```bash
chmod +x test_api.sh
./test_api.sh
```

#### 3.4 单独测试各接口（手动）

```bash
# 先获取 Token 并保存到变量
TOKEN=$(curl -s -X POST http://localhost:8081/admin/login \
  -H "Content-Type: application/json" \
  -d '{"username":"admin","password":"password"}' | grep -o '"token":"[^"]*"' | cut -d'"' -f4)

# 用户列表
curl -s http://localhost:8081/admin/users \
  -H "Authorization: Bearer $TOKEN"

# 创建用户
curl -s -X POST http://localhost:8081/admin/users \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"username":"testuser","password":"123456","nickname":"测试用户","email":"test@example.com","phone":"13800138000","dept_id":1,"role_id":1}'

# 角色列表
curl -s http://localhost:8081/admin/roles \
  -H "Authorization: Bearer $TOKEN"

# 部门列表
curl -s http://localhost:8081/admin/departments \
  -H "Authorization: Bearer $TOKEN"

# 菜单树
curl -s http://localhost:8081/admin/menus \
  -H "Authorization: Bearer $TOKEN"

# 权限列表
curl -s http://localhost:8081/admin/permissions \
  -H "Authorization: Bearer $TOKEN"

# 操作日志
curl -s http://localhost:8081/admin/logs \
  -H "Authorization: Bearer $TOKEN"

# 个人信息
curl -s http://localhost:8081/admin/profile \
  -H "Authorization: Bearer $TOKEN"

# 修改密码
curl -s -X PUT http://localhost:8081/admin/profile/password \
  -H "Authorization: Bearer $TOKEN" \
  -H "Content-Type: application/json" \
  -d '{"old_password":"password","new_password":"newpassword123"}'
```

### 四、数据库连接测试

```bash
# 进入 MySQL 容器验证数据库
docker-compose exec mysql mysql -uroot -proot -e "USE thinkphp_admin; SHOW TABLES;"

# 验证初始数据
docker-compose exec mysql mysql -uroot -proot -e "SELECT id,username,nickname FROM thinkphp_admin.tp_admin_user;"
```

### 五、Redis 连接测试

```bash
# 进入 Redis 容器验证连接
docker-compose exec redis redis-cli ping
# 预期返回: PONG
```

### 六、完整质检流程

```bash
# 1. 清理旧环境（如有）
docker-compose down -v

# 2. 一键启动（构建镜像 + 初始化数据库 + 安装依赖）
docker-compose up --build -d

# 3. 等待服务就绪后验证容器状态
docker-compose ps

# 4. 运行单元测试
docker-compose exec backend php vendor/bin/phpunit

# 5. 运行 API 测试脚本
./test_api.sh

# 6. 验证数据库
docker-compose exec mysql mysql -uroot -proot -e "USE thinkphp_admin; SHOW TABLES;"

# 7. 验证 Redis
docker-compose exec redis redis-cli ping

echo "✅ 质检完成，所有服务正常运行"
```

### 七、常见问题排查

```bash
# 查看后端日志
docker-compose logs backend

# 查看 MySQL 日志
docker-compose logs mysql

# 进入后端容器调试
docker-compose exec backend sh

# 检查 PHP 扩展
docker-compose exec backend php -m

# 检查 Composer 依赖
docker-compose exec backend composer show
```

## 许可证

Apache-2.0 License

## 作者

ThinkPHP Team
