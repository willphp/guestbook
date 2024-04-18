<?php
declare(strict_types=1);
namespace app\index\controller;
class CpSingle extends Cp
{
    protected string $model = 'common.single';
    protected string $order = 'status DESC,id DESC';
    protected string $listFieldExcept = 'content';
}