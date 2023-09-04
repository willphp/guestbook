<?php
declare(strict_types=1);
namespace app\index\controller;
use willphp\core\Jump;
class Api
{
	use Jump;
	public function clear()
	{
		cache_flush('[all]');
        clear_runtime('index');
		$this->success('清除缓存成功', 'index/index');
	}
}