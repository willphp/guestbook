<?php
declare(strict_types=1);

namespace app\admin\controller;

use willphp\core\Jump;

abstract class Base
{
    use Jump;

    protected string $table;
    protected object $model;
    protected string $order = 'id DESC';
    protected int $pageSize = 10;
    protected string $backUrl = 'index';
    protected array $stateList = ['status' => ['停用', '启用']];

    public function __construct()
    {
        if ($this->table) {
            $this->model = model('common.' . $this->table);
        }
    }

    protected function _where(): array
    {
        return [];
    }

    public function index()
    {
        $where = $this->_where();
        $list = $this->model->where($where)->order($this->order)->paginate($this->pageSize);
        return view()->with('list', $list);
    }

    public function add(array $req)
    {
        if ($this->isPost()) {
            $r = $this->model->save($req);
            $this->_jump(['添加成功', '添加失败'], $r, $this->backUrl);
        }
        return view();
    }

    public function edit(int $id, array $req)
    {
        $model = $this->model->find($id);
        if (!$model) {
            $this->error('记录不存在');
        }
        if ($this->isPost()) {
            $r = $model->save($req);
            $this->_jump(['修改成功', '修改失败'], $r, $this->backUrl);
        }
        return view()->with('vo', $model->toArray());
    }

    public function del($ids)
    {
        if (!$ids) {
            $this->error('请选择id');
        }
        if (!is_array($ids)) {
            $ids = [$ids];
        }
        $r = false;
        foreach ($ids as $id) {
            $tmp = $this->model->find($id);
            $r = $tmp->del();
        }
        $this->_jump(['删除成功', '删除失败，请先停用或设为待审'], $r, $this->backUrl);
    }

    public function state($ids = '')
    {
        if (!$ids) {
            $this->error('请选择id');
        }
        $params = input('get.params', '', 'clear_html');
        if (empty($params)) {
            $this->error('参数必须');
        }
        list($field, $value) = explode('-', $params); //包含-
        if (!isset($this->stateList[$field][$value])) {
            $this->error('状态未设置');
        }
        $title = $this->stateList[$field][$value];
        $map = [];
        $map[] = [$field, '<>', $value];
        if (!is_array($ids)) {
            $map['id'] = $ids;
        } else {
            $map[] = ['id', 'in', $ids];
        }
        $r = db($this->table)->where($map)->setField($field, $value);
        $this->model->updateWidget();
        $this->_jump([$title . '成功', $title . '失败'], $r, $this->backUrl);
    }
}