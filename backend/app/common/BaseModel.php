<?php
declare(strict_types=1);

namespace app\common;

use think\Model;

/**
 * 基础模型类
 */
abstract class BaseModel extends Model
{
    // 自动时间戳
    protected $autoWriteTimestamp = true;
    
    // 时间字段格式
    protected $dateFormat = 'Y-m-d H:i:s';
    
    // 软删除
    use \think\model\concern\SoftDelete;
    protected $deleteTime = 'delete_time';
    protected $defaultSoftDelete = 0;
}
