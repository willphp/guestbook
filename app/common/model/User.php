<?php
declare(strict_types=1);
namespace app\common\model;
use aphp\core\Model;
class User extends Model
{
	protected string $table = 'user';
	protected string $pk = 'id';
    protected array $validate = [
        ['username', '/^\w{4,12}$/|unique', '用户名4-12位|用户名已存在', AT_MUST, IN_INSERT],
        ['nickname', 'required|unique', '昵称必须|昵称已存在', AT_MUST, IN_BOTH],
        ['password', 'required', '请输入密码', AT_MUST, IN_INSERT],
        ['password', '/^\w{1,12}$/', '密码1-12位', AT_NOT_NULL, IN_BOTH],
        ['re_password', 'confirm:password', '确认密码不一致', AT_MUST, IN_BOTH],
        ['mobile', 'mobile', '手机号格式错误', AT_NOT_NULL, IN_BOTH],
        ['email', 'email', '邮箱格式错误', AT_NOT_NULL, IN_BOTH],
        ['qq', 'qq', 'QQ号格式错误', AT_NOT_NULL, IN_BOTH],
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
    //自动处理加密密码
    public function setPwd(string $val, array $data): string
    {
        return $this->getEncryptPassword($val, $data['username']);
    }
    //获取加密密码
    public function getEncryptPassword(string $password, string $salt = ''): string
    {
        return md5(md5($password) . $salt);
    }
    //更新之后，更新session中的nickname
    protected function _after_update(array $before, array $after): void
    {
        $user = session('user');
        if ($after['id'] == $user['id'] && $after['nickname'] != $user['nickname']) {
            session('user.nickname', $after['nickname']);
        }
    }
    //删除之前，设置删除条件id>1和status=0
    protected function _before_delete(array $data): void
    {
        $this->db = $this->db->where('id>1 AND status=0');
    }
    //登录逻辑
    public function login(array $req, bool $isAdmin = false): bool
    {
        $rule = [
            ['username', '/^\w{4,12}$/', '用户名4-12位'],
            ['password', '/^\w{1,12}$/', '密码1-12位'],
            ['captcha', 'captcha', '验证码错误'],
        ];
        $errors = validate($rule, $req)->getError();
        if (!empty($errors)) {
            $this->errors[] = current($errors);
            return false;
        }
        $user = $this->field('id,username,password,nickname,level,qq,status')->where('username', $req['username'])->find();
        if (!$user) {
            $this->errors[] = '用户不存在';
            return false;
        }
        if ($user['status'] == 0) {
            $this->errors[] = '用户已停用';
            return false;
        }
        if ($isAdmin && $user['level'] < 3) {
            $this->errors[] = '只有后台管理才能登录';
            return false;
        }
        if ($user['password'] != $this->getEncryptPassword($req['password'], $user['username'])) {
            $this->errors[] = '密码错误';
            return false;
        }
        unset($user['password'], $user['status']);
        session('user', $user->toArray()); //存储session
        return true;
    }
    //修改密码逻辑
    public function changePwd(array $req): bool
    {
        $rule = [
            ['old_pwd', '/^\w{1,12}$/', '旧密码1-12位'],
            ['new_pwd', '/^\w{1,12}$/', '新密码1-12位'],
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
}