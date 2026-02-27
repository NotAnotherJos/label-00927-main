<?php
declare(strict_types=1);

namespace app\common\service;

use think\db\Query;

/**
 * 数据权限服务
 */
class DataScopeService
{
    /**
     * 应用数据权限
     *
     * @param Query $query 查询对象
     * @param int $dataScope 数据权限类型
     * @param int|null $userId 用户ID
     * @param int|null $deptId 部门ID
     * @return Query
     */
    public static function applyDataScope(Query $query, int $dataScope, ?int $userId = null, ?int $deptId = null): Query
    {
        $userId = $userId ?? self::getCurrentUserId();
        $deptId = $deptId ?? self::getUserDeptId($userId);
        
        switch ($dataScope) {
            case 1: // 全部数据
                // 不添加任何条件
                break;
            case 2: // 本部门数据
                $query->where('dept_id', $deptId);
                break;
            case 3: // 本部门及子部门数据
                $deptIds = self::getDeptAndChildrenIds($deptId);
                $query->whereIn('dept_id', $deptIds);
                break;
            case 4: // 本人数据
                $query->where('user_id', $userId);
                break;
            case 5: // 自定义数据
                // 需要从数据权限关联表获取
                $deptIds = self::getCustomDeptIds($userId);
                if (!empty($deptIds)) {
                    $query->whereIn('dept_id', $deptIds);
                }
                break;
        }
        
        return $query;
    }

    /**
     * 获取当前用户ID
     *
     * @return int|null
     */
    private static function getCurrentUserId(): ?int
    {
        $token = \think\facade\Request::header('Authorization', '');
        if (empty($token)) {
            return null;
        }
        
        $token = str_replace('Bearer ', '', $token);
        $payload = \extend\JwtUtil::verifyToken($token);
        
        return $payload['user_id'] ?? null;
    }

    /**
     * 获取用户部门ID
     *
     * @param int $userId
     * @return int|null
     */
    private static function getUserDeptId(int $userId): ?int
    {
        $user = \app\admin\model\AdminUser::find($userId);
        return $user->dept_id ?? null;
    }

    /**
     * 获取部门及子部门ID列表
     *
     * @param int $deptId
     * @return array
     */
    private static function getDeptAndChildrenIds(int $deptId): array
    {
        $ids = [$deptId];
        $children = \app\admin\model\Department::where('pid', $deptId)->column('id');
        foreach ($children as $childId) {
            $ids = array_merge($ids, self::getDeptAndChildrenIds($childId));
        }
        return $ids;
    }

    /**
     * 获取自定义数据权限部门ID列表
     *
     * @param int $userId
     * @return array
     */
    private static function getCustomDeptIds(int $userId): array
    {
        // 从数据权限关联表获取
        return \think\facade\Db::table('tp_role_dept')
            ->where('role_id', function ($query) use ($userId) {
                $query->table('tp_admin_user_role')
                    ->where('user_id', $userId)
                    ->field('role_id');
            })
            ->column('dept_id');
    }
}
