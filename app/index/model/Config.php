<?php
declare(strict_types=1);
namespace app\index\model;
use aphp\core\Model;
class Config extends Model
{
	protected string $table = 'config';
	protected string $pk = 'id';
    protected array $validate = [
        ['name', 'required', '名称必须', AT_MUST, IN_BOTH],
        ['config_key', 'string', '键名必须是英文', AT_MUST, IN_BOTH],
        ['config_value', 'required', '配置值必须', AT_MUST, IN_BOTH],
    ];
    protected array $auto = [
        ['status', '1', 'string', AT_MUST, IN_INSERT]
    ];
}