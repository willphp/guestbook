<?php
declare(strict_types=1);

namespace app\common\model;

use willphp\core\Model;

class Single extends Model
{
    protected string $table = 'single';
    protected string $pk = 'id';
    protected array $validate = [
        ['name', 'chs', '名称为中文', AT_MUST, IN_BOTH],
        ['pageid', 'string', '标识格式错误', AT_MUST, IN_BOTH],
        ['title', 'required', '标题必须', AT_MUST, IN_BOTH],
        ['content', 'required', '内容必须', AT_MUST, IN_BOTH],
    ];
    protected array $auto = [
        ['status', '1', 'string', AT_MUST, IN_INSERT]
    ];

    protected function _before_delete(array $data): void
    {
        $this->db = $this->db->where('status', 0); //停用后才能删除
    }
}