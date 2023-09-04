<?php
declare(strict_types=1);
namespace app\admin\controller;

use willphp\core\Dir;
use willphp\core\Jump;

class Data
{
    use Jump;
    protected string $middleware = 'auth';

    public function clear()
    {
        if ($this->isPost()) {
            $password = input('post.password', '', 'clear_html');
            if (empty($password)) {
                $this->error('请输入管理密码');
            }
            if (!model('common.user')->checkAdmin($password)) {
                $this->error('管理密码错误');
            }
            db('user')->where('id>1')->delete();
            db('guestbook')->where('id>0')->delete();
            db('likevote')->where('id>0')->delete();
            cache(null);
            Dir::del(ROOT_PATH.'/public/uploads');
            $this->success('数据已清空', 'data/clear');
        }
       return view();
    }
}