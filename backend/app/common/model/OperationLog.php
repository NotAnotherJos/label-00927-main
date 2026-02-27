<?php
declare(strict_types=1);

namespace app\common\model;

use think\Model;

/**
 * 操作日志模型
 * 不使用软删除，日志数据通过归档方式处理
 */
class OperationLog extends Model
{
    protected $name = 'operation_log';
    
    // 自动时间戳
    protected $autoWriteTimestamp = 'datetime';
    
    // 只有创建时间
    protected $createTime = 'create_time';
    protected $updateTime = false;
    
    protected $type = [
        'id' => 'integer',
        'user_id' => 'integer',
    ];

    /**
     * 关联用户
     */
    public function user()
    {
        return $this->belongsTo(\app\admin\model\AdminUser::class, 'user_id', 'id');
    }
}
