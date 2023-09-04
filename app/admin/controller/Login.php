<?php
declare(strict_types=1);
namespace app\admin\controller;
use willphp\core\Jump;

class Login
{
    use Jump;
	public function login(array $req)
	{
        if ($this->isPost()) {
            $user = model('common.user');
            $r = $user->login($req, true);
            $this->_jump(['登录成功', $user->getError()], $r, 'index/index');
        }
		return view();
	}

    public function logout()
    {
        session(null);
        $this->success('退出成功', 'login/login');
    }
    //验证码
    public function captcha()
    {
        return (new \extend\captcha\Captcha())->make();
    }
}