<?php
declare(strict_types=1);
namespace app\index\controller;
use aphp\core\Jump;

class CpConfig
{
    use Jump;
    protected string $middleware = 'rbac'; //权限验证

    public function index(array $req)
    {
        if ($this->isPost()) {
            $site = !isset($req['id']) ? model('config') : model('config')->find($req['id']);
            $r = $site->save($req);
            $this->_jump(['操作成功', '操作失败'], $r, 'index');
        }
        $list = db('config')->order('id ASC')->select();
        return view()->with('list', $list);
    }

    public function del(int $id) {
        $r = db('config')->where('id', $id)->delete();
        $this->_jump(['删除成功', '删除失败'], $r ,'index');
    }

    public function save_file() {
        if ($this->isAjax()) {
            $site = db('config')->order('id ASC')->column('config_value', 'config_key');
            $config = var_export($site, true);
            file_put_contents(APHP_TOP.'/app/index/config/site.php', "<?php\nreturn ".$config.';');
            $this->success('生成文件成功','index');
        }
        $this->error('非法操作');
    }

    public function clear()
    {
        if ($this->isAjax()) {
            if (session('user.id') != 1) {
                $this->error('必须是超级管理员才能操作');
            }
            db('user')->where('id>1')->delete();
            db('guestbook')->where('id>0')->delete();
            db('guestbook_poll')->where('id>0')->delete();
            cache_clear();
            $this->success('数据已清空', 'index');
        }
        $this->error('非法操作');
    }
}