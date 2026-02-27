<?php
declare(strict_types=1);

namespace app\admin\middleware;

use Closure;
use think\Request;
use think\Response;
use app\admin\model\AdminUser;

/**
 * 数据权限中间件
 * 将数据权限信息注入到请求上下文中
 */
class DataScope
{
    /**
     * 处理请求
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userId = $request->userId ?? null;
        
        if ($userId) {
            $user = AdminUser::find($userId);
            if ($user) {
                // 注入数据权限相关信息
                $request->dataScope = $user->data_scope ?? 1;
                $request->deptId = $user->dept_id ?? 0;
                $request->roleId = $user->role_id ?? 0;
                
                // 获取可访问的部门ID列表
                $request->accessDeptIds = $this->getAccessDeptIds($user);
            }
        }
        
        return $next($request);
    }

    /**
     * 获取可访问的部门ID列表
     *
     * @param AdminUser $user
     * @return array
     */
    private function getAccessDeptIds(AdminUser $user): array
    {
        $dataScope = $user->data_scope ?? 1;
        $deptId = $user->dept_id ?? 0;
        
        switch ($dataScope) {
            case 1: // 全部数据
                return [];
            case 2: // 本部门数据
                return [$deptId];
            case 3: // 本部门及子部门数据
                return $this->getDeptAndChildrenIds($deptId);
            case 4: // 本人数据
                return [-1]; // 特殊标记，表示只能看自己的数据
            case 5: // 自定义数据
                return $this->getCustomDeptIds($user->id);
            default:
                return [];
        }
    }

    /**
     * 获取部门及子部门ID列表
     *
     * @param int $deptId
     * @return array
     */
    private function getDeptAndChildrenIds(int $deptId): array
    {
        $ids = [$deptId];
        $children = \app\admin\model\Department::where('pid', $deptId)->column('id');
        foreach ($children as $childId) {
            $ids = array_merge($ids, $this->getDeptAndChildrenIds($childId));
        }
        return $ids;
    }

    /**
     * 获取自定义数据权限部门ID列表
     *
     * @param int $userId
     * @return array
     */
    private function getCustomDeptIds(int $userId): array
    {
        $user = AdminUser::find($userId);
        if (!$user) {
            return [];
        }
        
        return \think\facade\Db::table('tp_role_dept')
            ->where('role_id', $user->role_id)
            ->column('dept_id');
    }
}
