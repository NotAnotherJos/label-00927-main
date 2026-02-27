<?php
declare(strict_types=1);

namespace app\common\helper;

/**
 * 树形结构助手类
 */
class TreeHelper
{
    /**
     * 列表转树形结构
     *
     * @param array $list 列表数据
     * @param string $idKey ID字段名
     * @param string $pidKey 父ID字段名
     * @param string $childKey 子节点字段名
     * @param int $rootId 根节点ID
     * @return array
     */
    public static function listToTree(
        array $list,
        string $idKey = 'id',
        string $pidKey = 'pid',
        string $childKey = 'children',
        int $rootId = 0
    ): array {
        $tree = [];
        $map = [];
        
        // 建立ID映射
        foreach ($list as $item) {
            $map[$item[$idKey]] = $item;
        }
        
        // 构建树形结构
        foreach ($list as $item) {
            $pid = $item[$pidKey];
            if ($pid == $rootId) {
                $tree[] = &$map[$item[$idKey]];
            } else {
                if (isset($map[$pid])) {
                    $map[$pid][$childKey][] = &$map[$item[$idKey]];
                }
            }
        }
        
        return $tree;
    }

    /**
     * 树形结构转列表
     *
     * @param array $tree 树形数据
     * @param string $childKey 子节点字段名
     * @return array
     */
    public static function treeToList(array $tree, string $childKey = 'children'): array
    {
        $list = [];
        
        foreach ($tree as $item) {
            $children = $item[$childKey] ?? [];
            unset($item[$childKey]);
            $list[] = $item;
            
            if (!empty($children)) {
                $list = array_merge($list, self::treeToList($children, $childKey));
            }
        }
        
        return $list;
    }

    /**
     * 获取所有子节点ID
     *
     * @param array $list 列表数据
     * @param int $parentId 父节点ID
     * @param string $idKey ID字段名
     * @param string $pidKey 父ID字段名
     * @return array
     */
    public static function getChildIds(
        array $list,
        int $parentId,
        string $idKey = 'id',
        string $pidKey = 'pid'
    ): array {
        $ids = [];
        
        foreach ($list as $item) {
            if ($item[$pidKey] == $parentId) {
                $ids[] = $item[$idKey];
                $ids = array_merge($ids, self::getChildIds($list, $item[$idKey], $idKey, $pidKey));
            }
        }
        
        return $ids;
    }

    /**
     * 获取所有父节点ID
     *
     * @param array $list 列表数据
     * @param int $childId 子节点ID
     * @param string $idKey ID字段名
     * @param string $pidKey 父ID字段名
     * @return array
     */
    public static function getParentIds(
        array $list,
        int $childId,
        string $idKey = 'id',
        string $pidKey = 'pid'
    ): array {
        $ids = [];
        $map = array_column($list, null, $idKey);
        
        $current = $map[$childId] ?? null;
        while ($current && $current[$pidKey] > 0) {
            $ids[] = $current[$pidKey];
            $current = $map[$current[$pidKey]] ?? null;
        }
        
        return $ids;
    }

    /**
     * 获取节点路径
     *
     * @param array $list 列表数据
     * @param int $nodeId 节点ID
     * @param string $idKey ID字段名
     * @param string $pidKey 父ID字段名
     * @param string $nameKey 名称字段名
     * @param string $separator 分隔符
     * @return string
     */
    public static function getNodePath(
        array $list,
        int $nodeId,
        string $idKey = 'id',
        string $pidKey = 'pid',
        string $nameKey = 'name',
        string $separator = ' / '
    ): string {
        $path = [];
        $map = array_column($list, null, $idKey);
        
        $current = $map[$nodeId] ?? null;
        while ($current) {
            array_unshift($path, $current[$nameKey]);
            $current = ($current[$pidKey] > 0) ? ($map[$current[$pidKey]] ?? null) : null;
        }
        
        return implode($separator, $path);
    }
}
