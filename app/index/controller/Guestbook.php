<?php
declare(strict_types=1);

namespace app\index\controller;

use aphp\core\Jump;

class Guestbook
{
    use Jump;

    protected function checkAuth(int $level = 1)
    {
        if (session('user.level') < $level) {
            $this->error('权限不足');
        }
    }

    public function view(int $id)
    {
        $vo = db('guestbook')->where('id', $id)->find();
        if (!$vo) {
            $this->error('留言不存在');
        }
        return view()->with('vo', $vo);
    }

    public function add(array $req)
    {
        if ($this->isPost()) {
            $r = model('common.guestbook')->save($req);
            $audit = site('msg_audit', 0);
            $msg = $audit == 0 ? '留言成功' : '留言添加成功，请耐心等待审核...';
            $this->_jump([$msg, '留言失败'], $r, 'index/index');
        }
        $this->error('非法操作');
    }

    public function poll(int $id)
    {
        if ($this->isAjax()) {
            $ip = ip2long(get_server_ip());
            $poll = db('guestbook_poll');
            $isPoll = $poll->where('guestbook_id', $id)->where('poll_ip', $ip)->find();
            if (!$isPoll) {
                $data = [];
                $data['guestbook_id'] = $id;
                $data['poll_ip'] = $ip;
                $data['create_time'] = time();
                $data['status'] = 1;
                $poll->insert($data);
                db('guestbook')->where('id', $id)->setInc('poll_count');
                $this->success('点赞成功');
            }
            $this->error('您已点赞');
        }
        $this->error('非法操作');
    }

    public function top(int $id)
    {
        $this->checkAuth(3);
        if ($this->isAjax()) {
            $guestbook = db('guestbook');
            $act = '置顶';
            $r = $guestbook->where('id', $id)->where('is_top', 0)->setField('is_top', 1);
            if (!$r) {
                $act = '取消置顶';
                $r = $guestbook->where('id', $id)->where('is_top', 1)->setField('is_top', 0);
            }
            $this->_jump([$act . '成功', $act . '失败'], $r, 'index');
        }
        $this->error('非法操作');
    }

    public function del(int $id)
    {
        $this->checkAuth(3);
        if ($this->isAjax()) {
            $r = db('guestbook')->where('id', $id)->setField('status', 0); //隐藏，后台来删除
            $this->_jump(['设为待审成功', '设为待审失败'], $r, 'index/index');
        }
        $this->error('非法操作');
    }

    public function reply(array $req)
    {
        $this->checkAuth(3);
        if ($this->isPost()) {
            $r = model('common.guestbook')->reply($req);
            $this->_jump(['回复成功', '回复失败'], $r, 'index/index');
        }
        $this->error('非法操作');
    }
}