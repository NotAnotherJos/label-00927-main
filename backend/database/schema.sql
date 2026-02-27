-- ThinkPHP8多应用系统数据库结构

-- 部门表
CREATE TABLE IF NOT EXISTS `tp_department` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '部门ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父部门ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '部门名称',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '部门编码',
  `leader` varchar(50) NOT NULL DEFAULT '' COMMENT '负责人',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '联系电话',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0-禁用，1-启用',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='部门表';

-- 角色表
CREATE TABLE IF NOT EXISTS `tp_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '角色ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '角色名称',
  `code` varchar(50) NOT NULL DEFAULT '' COMMENT '角色编码',
  `data_scope` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据权限：1-全部，2-本部门，3-本部门及子部门，4-本人，5-自定义',
  `remark` varchar(255) NOT NULL DEFAULT '' COMMENT '备注',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0-禁用，1-启用',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色表';

-- 权限表
CREATE TABLE IF NOT EXISTS `tp_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '权限ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父权限ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '权限名称',
  `code` varchar(100) NOT NULL DEFAULT '' COMMENT '权限编码',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型：1-菜单，2-按钮',
  `path` varchar(200) NOT NULL DEFAULT '' COMMENT '路由路径',
  `component` varchar(200) NOT NULL DEFAULT '' COMMENT '组件路径',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0-禁用，1-启用',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `type` (`type`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='权限表';

-- 菜单表
CREATE TABLE IF NOT EXISTS `tp_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '菜单ID',
  `pid` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '父菜单ID',
  `name` varchar(50) NOT NULL DEFAULT '' COMMENT '菜单名称',
  `path` varchar(200) NOT NULL DEFAULT '' COMMENT '路由路径',
  `component` varchar(200) NOT NULL DEFAULT '' COMMENT '组件路径',
  `icon` varchar(50) NOT NULL DEFAULT '' COMMENT '图标',
  `type` tinyint(1) NOT NULL DEFAULT '1' COMMENT '类型：1-目录，2-菜单，3-按钮',
  `permission` varchar(100) NOT NULL DEFAULT '' COMMENT '权限标识',
  `sort` int(11) NOT NULL DEFAULT '0' COMMENT '排序',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0-禁用，1-启用',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  KEY `pid` (`pid`),
  KEY `type` (`type`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='菜单表';

-- 后台用户表
CREATE TABLE IF NOT EXISTS `tp_admin_user` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '用户ID',
  `username` varchar(50) NOT NULL DEFAULT '' COMMENT '用户名',
  `password` varchar(255) NOT NULL DEFAULT '' COMMENT '密码',
  `nickname` varchar(50) NOT NULL DEFAULT '' COMMENT '昵称',
  `avatar` varchar(255) NOT NULL DEFAULT '' COMMENT '头像',
  `email` varchar(100) NOT NULL DEFAULT '' COMMENT '邮箱',
  `phone` varchar(20) NOT NULL DEFAULT '' COMMENT '手机号',
  `dept_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '部门ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `data_scope` tinyint(1) NOT NULL DEFAULT '1' COMMENT '数据权限：1-全部，2-本部门，3-本部门及子部门，4-本人，5-自定义',
  `status` tinyint(1) NOT NULL DEFAULT '1' COMMENT '状态：0-禁用，1-启用',
  `last_login_time` datetime DEFAULT NULL COMMENT '最后登录时间',
  `last_login_ip` varchar(50) NOT NULL DEFAULT '' COMMENT '最后登录IP',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `update_time` datetime DEFAULT NULL COMMENT '更新时间',
  `delete_time` int(11) NOT NULL DEFAULT '0' COMMENT '删除时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `username` (`username`),
  KEY `dept_id` (`dept_id`),
  KEY `role_id` (`role_id`),
  KEY `status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='后台用户表';

-- 角色权限关联表
CREATE TABLE IF NOT EXISTS `tp_role_permission` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `permission_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '权限ID',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_permission` (`role_id`,`permission_id`),
  KEY `role_id` (`role_id`),
  KEY `permission_id` (`permission_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色权限关联表';

-- 用户角色关联表
CREATE TABLE IF NOT EXISTS `tp_admin_user_role` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `user_role` (`user_id`,`role_id`),
  KEY `user_id` (`user_id`),
  KEY `role_id` (`role_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='用户角色关联表';

-- 角色部门关联表（数据权限自定义）
CREATE TABLE IF NOT EXISTS `tp_role_dept` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `dept_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '部门ID',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_dept` (`role_id`,`dept_id`),
  KEY `role_id` (`role_id`),
  KEY `dept_id` (`dept_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色部门关联表';

-- 角色菜单关联表
CREATE TABLE IF NOT EXISTS `tp_role_menu` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT 'ID',
  `role_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '角色ID',
  `menu_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '菜单ID',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  UNIQUE KEY `role_menu` (`role_id`,`menu_id`),
  KEY `role_id` (`role_id`),
  KEY `menu_id` (`menu_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='角色菜单关联表';

-- 操作日志历史表（用于归档）
CREATE TABLE IF NOT EXISTS `tp_operation_log_history` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '操作类型',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '操作内容',
  `params` text COMMENT '请求参数',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `user_agent` varchar(500) NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  `archive_time` datetime DEFAULT NULL COMMENT '归档时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='操作日志历史表';

-- 操作日志表
CREATE TABLE IF NOT EXISTS `tp_operation_log` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT COMMENT '日志ID',
  `user_id` int(11) unsigned NOT NULL DEFAULT '0' COMMENT '用户ID',
  `type` varchar(20) NOT NULL DEFAULT '' COMMENT '操作类型',
  `content` varchar(500) NOT NULL DEFAULT '' COMMENT '操作内容',
  `params` text COMMENT '请求参数',
  `ip` varchar(50) NOT NULL DEFAULT '' COMMENT 'IP地址',
  `user_agent` varchar(500) NOT NULL DEFAULT '' COMMENT 'User-Agent',
  `create_time` datetime DEFAULT NULL COMMENT '创建时间',
  PRIMARY KEY (`id`),
  KEY `user_id` (`user_id`),
  KEY `type` (`type`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='操作日志表';

-- 初始化数据
INSERT INTO `tp_department` (`id`, `pid`, `name`, `code`, `status`, `create_time`) VALUES
(1, 0, '总公司', 'ROOT', 1, NOW());

INSERT INTO `tp_role` (`id`, `name`, `code`, `data_scope`, `status`, `create_time`) VALUES
(1, '超级管理员', 'admin', 1, 1, NOW());

INSERT INTO `tp_admin_user` (`id`, `username`, `password`, `nickname`, `dept_id`, `role_id`, `data_scope`, `status`, `create_time`) VALUES
(1, 'admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', '超级管理员', 1, 1, 1, 1, NOW());
-- 默认密码：password

-- 初始化菜单数据
INSERT INTO `tp_menu` (`id`, `pid`, `name`, `path`, `component`, `icon`, `type`, `permission`, `sort`, `status`, `create_time`) VALUES
(1, 0, '系统管理', '/system', 'Layout', 'setting', 1, '', 1, 1, NOW()),
(2, 1, '用户管理', '/system/user', 'system/user/index', 'user', 2, 'system:user:list', 1, 1, NOW()),
(3, 1, '角色管理', '/system/role', 'system/role/index', 'peoples', 2, 'system:role:list', 2, 1, NOW()),
(4, 1, '菜单管理', '/system/menu', 'system/menu/index', 'tree-table', 2, 'system:menu:list', 3, 1, NOW()),
(5, 1, '部门管理', '/system/dept', 'system/dept/index', 'tree', 2, 'system:dept:list', 4, 1, NOW()),
(6, 1, '操作日志', '/system/log', 'system/log/index', 'documentation', 2, 'system:log:list', 5, 1, NOW()),
(7, 2, '用户新增', '', '', '', 3, 'system:user:add', 1, 1, NOW()),
(8, 2, '用户编辑', '', '', '', 3, 'system:user:edit', 2, 1, NOW()),
(9, 2, '用户删除', '', '', '', 3, 'system:user:delete', 3, 1, NOW()),
(10, 3, '角色新增', '', '', '', 3, 'system:role:add', 1, 1, NOW()),
(11, 3, '角色编辑', '', '', '', 3, 'system:role:edit', 2, 1, NOW()),
(12, 3, '角色删除', '', '', '', 3, 'system:role:delete', 3, 1, NOW()),
(13, 4, '菜单新增', '', '', '', 3, 'system:menu:add', 1, 1, NOW()),
(14, 4, '菜单编辑', '', '', '', 3, 'system:menu:edit', 2, 1, NOW()),
(15, 4, '菜单删除', '', '', '', 3, 'system:menu:delete', 3, 1, NOW()),
(16, 5, '部门新增', '', '', '', 3, 'system:dept:add', 1, 1, NOW()),
(17, 5, '部门编辑', '', '', '', 3, 'system:dept:edit', 2, 1, NOW()),
(18, 5, '部门删除', '', '', '', 3, 'system:dept:delete', 3, 1, NOW());

-- 初始化权限数据
INSERT INTO `tp_permission` (`id`, `pid`, `name`, `code`, `type`, `path`, `component`, `icon`, `sort`, `status`, `create_time`) VALUES
(1, 0, '系统管理', 'system', 1, '/system', 'Layout', 'setting', 1, 1, NOW()),
(2, 1, '用户管理', 'system:user', 1, '/system/user', 'system/user/index', 'user', 1, 1, NOW()),
(3, 2, '用户列表', 'system:user:list', 2, '', '', '', 1, 1, NOW()),
(4, 2, '用户新增', 'system:user:add', 2, '', '', '', 2, 1, NOW()),
(5, 2, '用户编辑', 'system:user:edit', 2, '', '', '', 3, 1, NOW()),
(6, 2, '用户删除', 'system:user:delete', 2, '', '', '', 4, 1, NOW()),
(7, 1, '角色管理', 'system:role', 1, '/system/role', 'system/role/index', 'peoples', 2, 1, NOW()),
(8, 7, '角色列表', 'system:role:list', 2, '', '', '', 1, 1, NOW()),
(9, 7, '角色新增', 'system:role:add', 2, '', '', '', 2, 1, NOW()),
(10, 7, '角色编辑', 'system:role:edit', 2, '', '', '', 3, 1, NOW()),
(11, 7, '角色删除', 'system:role:delete', 2, '', '', '', 4, 1, NOW()),
(12, 1, '菜单管理', 'system:menu', 1, '/system/menu', 'system/menu/index', 'tree-table', 3, 1, NOW()),
(13, 12, '菜单列表', 'system:menu:list', 2, '', '', '', 1, 1, NOW()),
(14, 12, '菜单新增', 'system:menu:add', 2, '', '', '', 2, 1, NOW()),
(15, 12, '菜单编辑', 'system:menu:edit', 2, '', '', '', 3, 1, NOW()),
(16, 12, '菜单删除', 'system:menu:delete', 2, '', '', '', 4, 1, NOW()),
(17, 1, '部门管理', 'system:dept', 1, '/system/dept', 'system/dept/index', 'tree', 4, 1, NOW()),
(18, 17, '部门列表', 'system:dept:list', 2, '', '', '', 1, 1, NOW()),
(19, 17, '部门新增', 'system:dept:add', 2, '', '', '', 2, 1, NOW()),
(20, 17, '部门编辑', 'system:dept:edit', 2, '', '', '', 3, 1, NOW()),
(21, 17, '部门删除', 'system:dept:delete', 2, '', '', '', 4, 1, NOW()),
(22, 1, '操作日志', 'system:log', 1, '/system/log', 'system/log/index', 'documentation', 5, 1, NOW()),
(23, 22, '日志列表', 'system:log:list', 2, '', '', '', 1, 1, NOW());
