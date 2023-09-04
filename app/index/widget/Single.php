<?php
declare(strict_types=1);
namespace app\index\widget;
use willphp\core\Widget;
class Single extends Widget
{
    protected string $tagName = 'single';
    protected int $expire = 0;
    public function set($id = '', array $options = [])
    {
        return db('single')->where('pageid', $id)->order('status DESC,id DESC')->find();
    }
}