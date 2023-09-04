<?php
declare(strict_types=1);
namespace app\admin\model;
use willphp\core\Model;

class Site extends Model
{
    protected string $table = 'site';
    protected string $pk = 'id';
    protected array $validate = [
        ['title', 'required', '名称必须', AT_MUST, IN_BOTH],
        ['name', 'string', '键名必须是英文', AT_MUST, IN_BOTH],
        ['value', 'required', '配置值必须', AT_MUST, IN_BOTH],
    ];
}