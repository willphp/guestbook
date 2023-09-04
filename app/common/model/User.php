<?php
declare(strict_types=1);

namespace app\common\model;

use willphp\core\Model;

class User extends Model
{
    protected string $table = 'user';
    protected string $pk = 'id';
    protected array $validate = [
        ['username', 'user|unique', '用户名格式错误|用户名已存在', AT_MUST, IN_INSERT],
        ['nickname', 'required', '昵称必须', AT_MUST, IN_BOTH],
        ['qq', 'qq', 'QQ格式错误', AT_NOT_NULL, IN_BOTH],
        ['email', 'email', '邮箱格式错误', AT_NOT_NULL, IN_BOTH],
        ['mobile', 'mobile', '手机号格式错误', AT_NOT_NULL, IN_BOTH],
        ['password', 'pwd', '密码6-12位', AT_MUST, IN_INSERT],
        ['repass', 'confirm:password', '确认密码不一致', AT_MUST, IN_BOTH],
    ];
    protected array $auto = [
        ['password', 'setPwd', 'method', AT_NOT_NULL, IN_BOTH],
        ['level', '1', 'string', AT_MUST, IN_INSERT],
        ['status', '1', 'string', AT_MUST, IN_INSERT],
    ];
    protected array $filter = [
        ['username', AT_SET, IN_UPDATE],
        ['password', AT_NULL, IN_UPDATE],
    ];

    public function setPwd($val, $data): string
    {
        return $this->getEncryptPassword($val, $data['username']);
    }

    protected function _before_update(array &$data): void
    {
        if (session('user.id') != 1) {
            $this->db = $this->db->where('id', '<>', 1);
        }
    }

    protected function _before_delete(array $data): void
    {
        $this->db = $this->db->where('status', 0)->where('id', '<>', 1);
    }

    public function login(array $req, bool $isLoginAdmin = false): bool
    {
        $rule = [
            ['username', 'required|user', '用户名必须|用户名格式错误'],
            ['password', 'pwd', '密码6-12位'],
            ['captcha', 'captcha', '验证码错误'],
        ];
        $errors = validate($rule, $req)->getError();
        if (!empty($errors)) {
            $this->errors[] = current($errors);
            return false;
        }
        $user = $this->field('id,username,password,level,nickname,qq,status')->where('username', $req['username'])->find();
        if (!$user) {
            $this->errors[] = '用户不存在';
            return false;
        }
        if ($user['status'] == 0 && $user['id'] != 1) {
            $this->errors[] = '用户已停用';
            return false;
        }
        if ($user['password'] != $this->getEncryptPassword($req['password'], $user['username'])) {
            $this->errors[] = '密码错误';
            return false;
        }
        if ($isLoginAdmin && $user['level'] < 3) {
            $this->errors[] = '只有后台管理才能登录';
            return false;
        }
        unset($user['password'], $user['status']);
        session('user', $user->toArray());
        return true;
    }

    public function changePwd(array $req): bool
    {
        $rule = [
            ['old_pwd', 'pwd', '旧密码6-12位'],
            ['new_pwd', 'pwd', '新密码6-12位'],
            ['re_pwd', 'confirm:new_pwd', '确认密码不一致'],
        ];
        $errors = validate($rule, $req)->getError();
        if (!empty($errors)) {
            $this->errors[] = current($errors);
            return false;
        }
        $uid = session('user.id');
        $user = $this->field('username,password')->where('id', $uid)->find();
        if ($user['password'] != $this->getEncryptPassword($req['old_pwd'], $user['username'])) {
            $this->errors[] = '旧密码错误';
            return false;
        }
        $newPwd = $this->getEncryptPassword($req['new_pwd'], $user['username']);
        $r = $this->where('id', $uid)->setField('password', $newPwd);
        if (!$r) {
            $this->errors[] = '修改失败';
            return false;
        }
        return true;
    }

    public function checkAdmin(string $password): bool
    {
        $user = $this->field('username,password')->where('id', 1)->find();
        return $user['password'] == $this->getEncryptPassword($password, $user['username']);
    }

    public function getEncryptPassword(string $password, string $salt = ''): string
    {
        return md5(md5($password) . $salt);
    }
}