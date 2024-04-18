<?php
declare(strict_types=1);
namespace app\common\model;
use aphp\core\Model;
class Single extends Model
{
	protected string $table = 'single';
	protected string $pk = 'id';
    protected array $validate = [
        ['sign', 'required', '标识格式错误', AT_MUST, IN_BOTH],
        ['name', 'chs', '名称必须中文', AT_MUST, IN_BOTH],
        ['title', 'required', '标题必须', AT_MUST, IN_BOTH],
        ['content', 'required', '内容必须', AT_MUST, IN_BOTH],
    ];
    protected array $auto = [
        ['status', '1', 'string', AT_MUST, IN_INSERT]
    ];

    protected function _before_delete(array $data): void
    {
        $this->db = $this->db->where('status=0');
    }
}