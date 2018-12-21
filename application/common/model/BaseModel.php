<?php
/**
 * Created by PhpStorm.
 * User: smallzz
 * Date: 2017/11/13
 * Time: 下午2:12
 */

namespace app\common\model;


use app\common\traits\Api;
use think\Model;

abstract class BaseModel extends Model
{
    use Api;
    protected $table;

    public function __construct($data = [])
    {
        $this->table = config('database.prefix') . $this->table;

        parent::__construct($data);

    }

}