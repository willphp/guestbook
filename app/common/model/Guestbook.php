<?php
declare(strict_types=1);

namespace app\common\model;

use willphp\core\Model;

class Guestbook extends Model
{
    protected string $table = 'guestbook';
    protected string $pk = 'id';
    protected array $validate = [
        ['name', 'required', '昵称不能为空', AT_SET, IN_INSERT],
        ['qq', 'qq', '请输入正确的QQ号', AT_NOT_NULL, IN_INSERT],
        ['title', 'required', '标题不能为空', AT_MUST, IN_BOTH],
        ['msg', 'required', '留言内容不能为空', AT_MUST, IN_BOTH],
        ['captcha', 'captcha', '请输入正确的验证码', AT_MUST, IN_INSERT]
    ];
    protected array $auto = [
        ['ip', 'get_int_ip', 'function', AT_MUST, IN_INSERT],
        ['status', '1', 'string', AT_MUST, IN_INSERT]
    ];

    protected function _before_insert(array &$data): void
    {
        $user = session('user');
        if ($user) {
            $data['uid'] = $user['id'];
            $data['name'] = $user['nickname'];
            $data['qq'] = $user['qq'];
        }
    }

    protected function _before_delete(array $data): void
    {
        $this->db = $this->db->where('status', 0); //停用后才能删除
    }

    public function replay(int $id)
    {
        $reMsg = input('re_msg', '', 'clear_html');
        $data = [];
        $data['re_msg'] = $reMsg;
        if (empty($reMsg)) {
            $data['re_time'] = 0;
            $data['re_uid'] = 0;
        } else {
            $data['re_time'] = time();
            $data['re_uid'] = session('user.id');
            $data['re_name'] = session('user.nickname');
        }
        return db('guestbook')->where('id', $id)->update($data);
    }
}