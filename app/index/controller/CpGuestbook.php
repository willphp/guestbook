<?php
declare(strict_types=1);
namespace app\index\controller;
class CpGuestbook extends Cp
{
    protected string $model = 'common.guestbook';
    protected string $order = 'status ASC,is_top DESC,id DESC';
    protected array $stateList = ['status' => ['设为待审', '审核通过']];

    public function reply(int $id, array $req)
    {
        if ($this->isPost()) {
            $r = model('common.guestbook')->reply($req);
            $this->_jump(['回复成功', '回复失败'], $r, 'index');
        }
        $vo = db('guestbook')->where('id', $id)->find();
        if (!$vo) {
            $this->error('留言不存在');
        }
        return view()->with('vo', $vo);
    }
}