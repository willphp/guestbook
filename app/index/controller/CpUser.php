<?php
declare(strict_types=1);
namespace app\index\controller;
class CpUser extends Cp
{
    protected string $model = 'common.user';
    protected string $order = 'status DESC,id DESC';
}