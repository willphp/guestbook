<?php
declare(strict_types=1);

namespace app\index\controller;
use willphp\core\Jump;

class Index
{
    use Jump;
    public function index()
    {
        return view();
    }

    public function view(int $id)
    {
        $vo = db('guestbook')->where('id', $id)->find();
        if (!$vo) {
            $this->error('留言不存在');
        }
        return view()->with('vo', $vo);
    }
}