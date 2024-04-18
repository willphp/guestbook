<?php
declare(strict_types=1);
namespace app\index\widget;
use aphp\core\Widget;
class Single extends Widget
{
    protected string $tag = 'single';
    protected int $expire = 0;
    public function set($id = '', array $options = [])
    {
        return db('single')->where('sign', $id)->order('status DESC,id DESC')->find();
    }
}