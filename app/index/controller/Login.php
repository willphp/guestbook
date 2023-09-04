<?php
declare(strict_types=1);

namespace app\index\controller;

use willphp\core\Jump;

class Login
{
    use Jump;

    //登录
    public function login(array $req)
    {
        if ($this->isPost()) {
            $model = model('common.user');
            $r = $model->login($req);
            $this->_jump(['登录成功', $model->getError()], $r, 'index/index');
        }
        return view();
    }

    //退出
    public function logout()
    {
        session(null);
        $this->success('退出成功', 'login/login');
    }

    //注册
    public function reg(array $req)
    {
        if ($this->isPost()) {
            $r = model('common.user')->save($req);
            $this->_jump(['注册成功', '注册失败'], $r, 'login?from=' . $req['username']);
        }
        return view();
    }

    //验证码
    public function captcha()
    {
        return (new \extend\captcha\Captcha())->make();
    }
}