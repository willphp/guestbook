<?php
declare(strict_types=1);
namespace app\admin\controller;
class Gbook extends Base
{
    protected string $middleware = 'auth';
    protected string $table = 'guestbook';
    protected string $order = 'status DESC,is_top DESC,id DESC';
    protected array $stateList = ['status' => ['设为待审', '审核通过']];
    public function replay(int $id)
    {
        if ($this->isPost()) {
            $r = $this->model->replay($id);
            $this->_jump(['回复成功', '回复失败'], $r, 'index');
        }
        $vo = db('guestbook')->where('id', $id)->find();
        if (!$vo) {
            $this->error('留言不存在');
        }
        return view()->with('vo', $vo);
    }
}