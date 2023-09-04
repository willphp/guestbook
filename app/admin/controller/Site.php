<?php
declare(strict_types=1);
namespace app\admin\controller;

use willphp\core\Jump;

class Site
{
    use Jump;
    protected string $middleware = 'auth';
    public function index(array $req)
    {
        if ($this->isPost()) {
            $site = !isset($req['id'])? model('site') : model('site')->find($req['id']);
            $r = $site->save($req);
            $this->_jump(['操作成功', '操作失败'], $r ,'index');
        }
        $list = db('site')->order('id ASC')->select();
        return view()->with('list', $list);
    }
    public function del(int $id) {
        $r = db('site')->where('id', $id)->delete();
        $this->_jump(['删除成功', '删除失败'], $r ,'index');
    }
    public function save_file() {
        if ($this->isAjax()) {
            $site = db('site')->order('id ASC')->column('value', 'name');
            $config = var_export($site, true);
            file_put_contents(ROOT_PATH.'/app/index/config/site.php', "<?php\nreturn ".$config.';');
            $this->success('生成文件成功','index');
        }
        $this->error('非法操作');
    }
}