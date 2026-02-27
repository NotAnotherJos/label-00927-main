<?php
declare(strict_types=1);

namespace app\admin\service;

use app\admin\model\AdminUser;
use app\common\exception\BusinessException;
use app\common\service\DataScopeService;
use app\common\service\LogService;
use think\facade\Db;

/**
 * 用户服务
 */
class UserService
{
    /**
     * 获取用户列表
     *
     * @param int $page
     * @param int $limit
     * @param array $params
     * @return array
     */
    public static function getList(int $page, int $limit, array $params = []): array
    {
        $query = AdminUser::alias('u')
            ->leftJoin('department d', 'u.dept_id = d.id')
            ->leftJoin('role r', 'u.role_id = r.id')
            ->field('u.*, d.name as dept_name, r.name as role_name');
        
        // 应用数据权限
        $userId = request()->userId ?? null;
        $user = $userId ? AdminUser::find($userId) : null;
        if ($user) {
            DataScopeService::applyDataScope($query, $user->data_scope ?? 1, $userId, $user->dept_id);
        }
        
        // 搜索条件
        if (!empty($params['username'])) {
            $query->where('u.username', 'like', '%' . $params['username'] . '%');
        }
        if (!empty($params['nickname'])) {
            $query->where('u.nickname', 'like', '%' . $params['nickname'] . '%');
        }
        if (isset($params['status'])) {
            $query->where('u.status', $params['status']);
        }
        
        $total = $query->count();
        $list = $query->page($page, $limit)->order('u.id desc')->select();
        
        return [
            'list' => $list,
            'total' => $total,
            'page' => $page,
            'limit' => $limit,
        ];
    }

    /**
     * 创建用户
     *
     * @param array $params
     * @return AdminUser
     */
    public static function create(array $params): AdminUser
    {
        Db::startTrans();
        try {
            $user = new AdminUser();
            $user->username = $params['username'];
            $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
            $user->nickname = $params['nickname'] ?? $params['username'];
            $user->email = $params['email'] ?? '';
            $user->phone = $params['phone'] ?? '';
            $user->dept_id = $params['dept_id'] ?? 0;
            $user->role_id = $params['role_id'] ?? 0;
            $user->status = $params['status'] ?? 1;
            $user->save();
            
            // 记录日志
            LogService::record('create', "新增用户：{$user->username}", $params);
            
            Db::commit();
            return $user;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('创建用户失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 更新用户
     *
     * @param int $id
     * @param array $params
     * @return AdminUser
     */
    public static function update(int $id, array $params): AdminUser
    {
        $user = AdminUser::find($id);
        if (!$user) {
            throw new BusinessException('用户不存在', 404);
        }
        
        Db::startTrans();
        try {
            if (isset($params['nickname'])) {
                $user->nickname = $params['nickname'];
            }
            if (isset($params['email'])) {
                $user->email = $params['email'];
            }
            if (isset($params['phone'])) {
                $user->phone = $params['phone'];
            }
            if (isset($params['dept_id'])) {
                $user->dept_id = $params['dept_id'];
            }
            if (isset($params['role_id'])) {
                $user->role_id = $params['role_id'];
            }
            if (isset($params['password'])) {
                $user->password = password_hash($params['password'], PASSWORD_DEFAULT);
            }
            $user->save();
            
            // 记录日志
            LogService::record('update', "修改用户：{$user->username}", $params);
            
            Db::commit();
            return $user;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('更新用户失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 删除用户
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $user = AdminUser::find($id);
        if (!$user) {
            throw new BusinessException('用户不存在', 404);
        }
        
        // 记录日志
        LogService::record('delete', "删除用户：{$user->username}", ['id' => $id]);
        
        return $user->delete();
    }

    /**
     * 设置用户状态
     *
     * @param int $id
     * @param int $status
     * @return bool
     */
    public static function setStatus(int $id, int $status): bool
    {
        $user = AdminUser::find($id);
        if (!$user) {
            throw new BusinessException('用户不存在', 404);
        }
        
        $user->status = $status;
        $user->save();
        
        // 记录日志
        LogService::record('update', "设置用户状态：{$user->username} -> " . ($status == 1 ? '启用' : '禁用'), ['id' => $id, 'status' => $status]);
        
        return true;
    }
}
