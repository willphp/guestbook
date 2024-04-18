<?php
declare(strict_types=1);
namespace app\common\model;
use aphp\core\Model;
class Guestbook extends Model
{
	protected string $table = 'guestbook';
	protected string $pk = 'id';
    protected array $validate = [
        ['nickname', 'required', '昵称不能为空', AT_SET, IN_INSERT],
        ['qq', 'qq', '请输入正确的QQ号', AT_NOT_NULL, IN_INSERT],
        ['title', 'required|unique', '标题不能为空|标题已存在', AT_MUST, IN_BOTH],
        ['msg', 'required', '留言内容不能为空', AT_MUST, IN_BOTH],
        ['captcha', 'captcha', '请输入正确的验证码', AT_MUST, IN_INSERT]
    ];

    protected function _before_insert(array &$data): void
    {
        $user = session('user');
        if ($user) {
            $data['uid'] = $user['id'];
            $data['nickname'] = $user['nickname'];
            $data['qq'] = $user['qq'] ?? 0;
        }
        $data['ip'] = ip2long(get_server_ip());
        $data['status'] = site('msg_audit', 0) ? 0 : 1;
    }

    public function reply(array $req)
    {
        $data = [];
        $data['reply_msg'] = $req['reply_msg'];
        if (empty($req['reply_msg'])) {
            $data['reply_time'] = 0;
            $data['reply_uid'] = 0;
        } else {
            $data['reply_time'] = time();
            $data['reply_uid'] = session('user.id');
            $data['reply_nickname'] = session('user.nickname');
        }
        return db('guestbook')->where('id', $req['id'])->update($data);
    }

    protected function _before_delete(array $data): void
    {
        $this->db = $this->db->where('status=0'); //停用后才能删除
    }

    protected function _after_delete(array $data): void
    {
        db('guestbook_poll')->where('guestbook_id', $data['id'])->delete(); //删除点赞
    }
}