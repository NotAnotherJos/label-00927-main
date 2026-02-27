<?php
declare(strict_types=1);

namespace app\admin\controller;

use app\BaseController;
use app\common\model\OperationLog;

/**
 * 操作日志控制器
 * @OA\Tag(name="操作日志", description="操作日志接口")
 */
class Log extends BaseController
{
    /**
     * 日志列表
     */
    public function index()
    {
        $params = $this->request->get();
        $page = $params['page'] ?? 1;
        $limit = $params['limit'] ?? 15;
        
        $query = OperationLog::alias('l')
            ->leftJoin('admin_user u', 'l.user_id = u.id')
            ->field('l.*, u.username, u.nickname');
        
        if (!empty($params['type'])) {
            $query->where('l.type', $params['type']);
        }
        if (!empty($params['user_id'])) {
            $query->where('l.user_id', $params['user_id']);
        }
        if (!empty($params['start_time'])) {
            $query->where('l.create_time', '>=', $params['start_time']);
        }
        if (!empty($params['end_time'])) {
            $query->where('l.create_time', '<=', $params['end_time']);
        }
        
        $total = $query->count();
        $list = $query->page($page, $limit)->order('l.id desc')->select();
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => [
                'list' => $list,
                'total' => $total,
                'page' => $page,
                'limit' => $limit,
            ],
            'timestamp' => time(),
        ]);
    }

    /**
     * 日志详情
     */
    public function read($id)
    {
        $log = OperationLog::find($id);
        if (!$log) {
            return json([
                'code' => 404,
                'msg' => '日志不存在',
                'data' => [],
                'timestamp' => time(),
            ]);
        }
        
        return json([
            'code' => 200,
            'msg' => 'success',
            'data' => $log,
            'timestamp' => time(),
        ]);
    }
}
