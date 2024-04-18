<?php
declare(strict_types=1);

namespace app\index\controller;
use aphp\core\Jump;
class Profile
{
    use Jump;
    protected string $middleware = 'auth'; //登录验证

    public function index()
    {
        $uid = session('user.id');
        $user = model('common.user')->find($uid);
        $list = db('guestbook')->where('uid', $uid)->paginate(5);
        return view()->with(['vo' => $user->toArray(), 'list' => $list]);
    }

    public function del_msg(int $id)
    {
        $uid = session('user.id');
        $r = db('guestbook')->where('id', $id)->where('uid', $uid)->delete();
        $this->_jump(['删除成功', '删除失败'], $r, 'index');
    }

    //修改资料
    public function edit(array $req)
    {
        $uid = session('user.id');
        $user = model('common.user')->find($uid);
        if ($this->isPost()) {
            $r = $user->save($req);
            $this->_jump(['修改成功', '修改失败'], $r, 'index');
        }
        return view()->with('vo', $user->toArray());
    }
    //修改密码
    public function password(array $req)
    {
        if ($this->isPost()) {
            $user = model('common.user');
            $r = $user->changePwd($req);
            $this->_jump(['修改成功', $user->getError()], $r, 'index');
        }
        return view();
    }
}