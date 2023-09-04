<?php
declare(strict_types=1);

namespace app\index\controller;

use willphp\core\Jump;

class Profile
{
    use Jump;

    public function info(int $uid)
    {
        $vo = db('user')->where('id', $uid)->find();
        if (!$vo) {
            $this->error('用户不存在');
        }
        return view()->with('vo', $vo);
    }

    public function index()
    {
        $this->_check_login('session.user');
        $uid = session('user.id');
        $user = model('common.user')->find($uid);
        $list = db('guestbook')->where('uid', $uid)->paginate(5);
        return view()->with(['vo' => $user->toArray(), 'list' => $list]);
    }

    public function del_msg(int $id)
    {
        $this->_check_login('session.user');
        $uid = session('user.id');
        $r = db('guestbook')->where('id', $id)->where('uid', $uid)->delete();
        $this->_jump(['删除成功', '删除失败'], $r, 'index');
    }

    public function edit(array $req)
    {
        $this->_check_login('session.user');
        $uid = session('user.id');
        $user = model('common.user')->find($uid);
        if ($this->isPost()) {
            $r = $user->save($req);
            $this->_jump(['修改成功', '修改失败'], $r, 'index');
        }
        return view()->with('vo', $user->toArray());
    }

    public function pwd(array $req)
    {
        $this->_check_login('session.user');
        if ($this->isPost()) {
            $user = model('common.user');
            $r = $user->changePwd($req);
            $this->_jump(['修改成功', $user->getError()], $r, 'index');
        }
        return view();
    }
}