<?php
declare(strict_types=1);
namespace app\admin\controller;

use willphp\core\Jump;

class Profile
{
    use Jump;
    protected string $middleware = 'auth';

    public function edit(array $req)
    {
        $uid = session('user.id');
        $user = model('common.user')->find($uid);
        if ($this->isPost()) {
            $r = $user->save($req);
            $this->_jump(['修改成功', '修改失败'], $r, 'index/index');
        }
        return view()->with('vo', $user->toArray());
    }

    public function pwd(array $req)
    {
        if ($this->isPost()) {
            $user = model('common.user');
            $r = $user->changePwd($req);
            $this->_jump(['修改成功', $user->getError()], $r, 'index/index');
        }
        return view();
    }
}