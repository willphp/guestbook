<?php
declare(strict_types=1);
namespace app\admin\controller;
class Error
{
	use \willphp\core\Jump;
	public function __call($method, $args)
	{
		$msg = $args[0] ?? '出错了！';
		$code = str_starts_with($method, '_') ? substr($method, 1) : 400;
		$this->error($msg, (int)$code);
	}
}