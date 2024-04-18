<?php
declare(strict_types=1);
namespace app\index\controller;
use aphp\core\Jump;

class Index
{
    use Jump;
    public function index()
    {
        return view();
    }

    public function single(string $sign = 'about')
    {
        $vo = db('single')->where('sign', $sign)->find();
        if (!$vo) {
            $this->error('页面不存在');
        }
        return view()->with('vo', $vo);
    }
}