<?php
declare(strict_types=1);

namespace app\index\controller;

use willphp\core\Jump;

class Single
{
    use Jump;

    public function index(string $pageid = 'about')
    {
        $vo = db('single')->where('pageid', $pageid)->find();
        if (!$vo) {
            $this->error('页面不存在');
        }
        return view()->with('vo', $vo);
    }
}