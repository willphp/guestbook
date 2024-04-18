<?php
declare(strict_types=1);

namespace app\index\controller;

use aphp\core\Jump;

class User
{
    use Jump;

    //用户列表
    public function index()
    {
        return view();
    }

    //用户资料
    public function info(int $id)
    {
        $vo = db('user')->where('id', $id)->find();
        if (!$vo) {
            $this->error('用户不存在');
        }
        return view()->with('vo', $vo);
    }
}