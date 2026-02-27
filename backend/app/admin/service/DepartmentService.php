<?php
declare(strict_types=1);

namespace app\admin\service;

use app\admin\model\Department;
use app\common\exception\BusinessException;
use app\common\service\LogService;
use think\facade\Db;

/**
 * 部门服务
 */
class DepartmentService
{
    /**
     * 获取部门列表
     *
     * @param array $params
     * @return array
     */
    public static function getList(array $params = []): array
    {
        $query = Department::order('sort asc, id asc');
        
        if (!empty($params['name'])) {
            $query->where('name', 'like', '%' . $params['name'] . '%');
        }
        if (isset($params['status'])) {
            $query->where('status', $params['status']);
        }
        
        return $query->select()->toArray();
    }

    /**
     * 获取部门树形结构
     *
     * @return array
     */
    public static function getTree(): array
    {
        $list = Department::where('status', 1)
            ->order('sort asc, id asc')
            ->select()
            ->toArray();
        
        return self::buildTree($list);
    }

    /**
     * 构建树形结构
     *
     * @param array $list
     * @param int $pid
     * @return array
     */
    private static function buildTree(array $list, int $pid = 0): array
    {
        $tree = [];
        foreach ($list as $item) {
            if ($item['pid'] == $pid) {
                $children = self::buildTree($list, $item['id']);
                if (!empty($children)) {
                    $item['children'] = $children;
                }
                $tree[] = $item;
            }
        }
        return $tree;
    }

    /**
     * 创建部门
     *
     * @param array $params
     * @return Department
     */
    public static function create(array $params): Department
    {
        Db::startTrans();
        try {
            $dept = new Department();
            $dept->pid = $params['pid'] ?? 0;
            $dept->name = $params['name'];
            $dept->code = $params['code'] ?? '';
            $dept->leader = $params['leader'] ?? '';
            $dept->phone = $params['phone'] ?? '';
            $dept->email = $params['email'] ?? '';
            $dept->sort = $params['sort'] ?? 0;
            $dept->status = $params['status'] ?? 1;
            $dept->save();
            
            LogService::record('create', "新增部门：{$dept->name}", $params);
            
            Db::commit();
            return $dept;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('创建部门失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 更新部门
     *
     * @param int $id
     * @param array $params
     * @return Department
     */
    public static function update(int $id, array $params): Department
    {
        $dept = Department::find($id);
        if (!$dept) {
            throw new BusinessException('部门不存在', 404);
        }
        
        // 不能将部门设置为自己的子部门
        if (isset($params['pid']) && $params['pid'] == $id) {
            throw new BusinessException('不能将部门设置为自己的子部门', 400);
        }
        
        Db::startTrans();
        try {
            if (isset($params['pid'])) {
                $dept->pid = $params['pid'];
            }
            if (isset($params['name'])) {
                $dept->name = $params['name'];
            }
            if (isset($params['code'])) {
                $dept->code = $params['code'];
            }
            if (isset($params['leader'])) {
                $dept->leader = $params['leader'];
            }
            if (isset($params['phone'])) {
                $dept->phone = $params['phone'];
            }
            if (isset($params['email'])) {
                $dept->email = $params['email'];
            }
            if (isset($params['sort'])) {
                $dept->sort = $params['sort'];
            }
            if (isset($params['status'])) {
                $dept->status = $params['status'];
            }
            $dept->save();
            
            LogService::record('update', "修改部门：{$dept->name}", $params);
            
            Db::commit();
            return $dept;
        } catch (\Exception $e) {
            Db::rollback();
            throw new BusinessException('更新部门失败：' . $e->getMessage(), 500);
        }
    }

    /**
     * 删除部门
     *
     * @param int $id
     * @return bool
     */
    public static function delete(int $id): bool
    {
        $dept = Department::find($id);
        if (!$dept) {
            throw new BusinessException('部门不存在', 404);
        }
        
        // 检查是否有子部门
        $childCount = Department::where('pid', $id)->count();
        if ($childCount > 0) {
            throw new BusinessException('存在子部门，无法删除', 400);
        }
        
        // 检查是否有用户
        $userCount = \app\admin\model\AdminUser::where('dept_id', $id)->count();
        if ($userCount > 0) {
            throw new BusinessException('部门下存在用户，无法删除', 400);
        }
        
        LogService::record('delete', "删除部门：{$dept->name}", ['id' => $id]);
        
        return $dept->delete();
    }

    /**
     * 获取部门详情
     *
     * @param int $id
     * @return Department
     */
    public static function getDetail(int $id): Department
    {
        $dept = Department::find($id);
        if (!$dept) {
            throw new BusinessException('部门不存在', 404);
        }
        return $dept;
    }
}
