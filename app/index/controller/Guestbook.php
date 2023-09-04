<?php
declare(strict_types=1);

namespace app\index\controller;

use willphp\core\Jump;

class Guestbook
{
    use Jump;

    public function add(array $req) {
        if ($this->isPost()) {
            $r = model('common.guestbook')->save($req);
            $this->_jump(['留言成功', '留言失败'], $r, 'index/index');
        }
        $this->error('非法操作');
    }

    public function like(int $id)
    {
        if ($this->isAjax()) {
            $ip = get_ip(1);
            $vote = db('likevote');
            $isGood = $vote->where('gid', $id)->where('ip', $ip)->find();
            if (!$isGood) {
                $vote->insert(['gid' => $id, 'ip' => $ip]);
                db('guestbook')->where('id', $id)->setInc('likes');
                $this->success('点赞成功');
            }
            $this->error('您已点赞');
        }
        $this->error('非法操作');
    }

    public function replay(int $id) {
        $this->checkAdmin();
        if ($this->isPost()) {
            $r = model('common.guestbook')->replay($id);
            $this->_jump(['回复成功', '回复失败'], $r, 'index/index');
        }
        $this->error('非法操作');
    }

    public function top(int $id)
    {
        $this->checkAdmin();
        if ($this->isAjax()) {
            $guestbook = db('guestbook');
            $act = '置顶';
            $r = $guestbook->where('id', $id)->where('is_top', 0)->setField('is_top', 1);
            if (!$r) {
                $act = '取消置顶';
                $r = $guestbook->where('id', $id)->where('is_top', 1)->setField('is_top', 0);
            }
            $this->_jump([$act . '成功', $act . '失败'], $r, 'index/index');
        }
        $this->error('非法操作');
    }

    public function del(int $id)
    {
        $this->checkAdmin();
        if ($this->isAjax()) {
            $r = db('guestbook')->where('id', $id)->setField('status', 0); //隐藏，后台来删除
            $this->_jump(['删除成功', '删除失败'], $r, 'index/index');
        }
        $this->error('非法操作');
    }

    protected function checkAdmin()
    {
        if (session('user.level') < 2) {
            $this->error('权限不足');
        }
    }
}