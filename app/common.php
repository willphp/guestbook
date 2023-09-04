<?php
declare(strict_types=1);
/**
 * 获取主题
 */
function getTheme(int $type = 0)
{
    $themeSet = get_config('site.theme_set', 'default=默认');
    $themeSet = explode('|', $themeSet);
    $arr = [];
    foreach ($themeSet as $k => $v) {
        [$key, $val] = pre_split($v, strval($k), '=');
        $arr[$key] = $val;
    }
    if ($type == 0) {
        return $arr;
    }
    return $arr[__THEME__] ?? '未设';
}

/**
 * 获取网站版本
 */
function site_ver(): string
{
    return APP_DEBUG ? date('YmdHis') : \willphp\core\Config::init()->get('site.ver', date('Y-m-d'));
}
/**
 * 获取当前导航激活样式
 */
function nav_active(string $nav, string $class = ' class="active"'): string
{
    $controller = \willphp\core\Route::init()->getController();
    return ($nav == $controller)? $class : '';
}

/**
 * 生成form:select
 */
function build_select(string $name, $options, $selected = ''): string
{
    $options = is_array($options)? $options : explode(',', $options);
    $html = "<select name=\"{$name}\" class=\"form-control\" style=\"width:auto;\">\n";
    foreach ($options as $val => $key) {
        if ($selected == $val) {
            $html .= "\t<option value=\"{$val}\" selected=\"selected\">{$key}</option>\n";
        } else {
            $html .= "\t<option value=\"{$val}\">{$key}</option>\n";
        }
    }
    $html .= "</select>\n";
    return $html;
}
/**
 * 生成form:radios
 */
function build_radios(string $name, $options, $selected = ''): string
{
    $options = is_array($options)? $options : explode(',', $options);
    $html = '';
    foreach ($options as $val => $key) {
        if ($selected == $val) {
            $html .= "<label><input type=\"radio\" name=\"{$name}\" value=\"{$val}\" checked=\"checked\" />{$key}</label>\n";
        } else {
            $html .= "<label><input type=\"radio\" name=\"{$name}\" value=\"{$val}\" />{$key}</label>\n";
        }
    }
    return $html;
}

/**
 * 获取int类型的IP
 */
function get_int_ip()
{
    return get_ip(1);
}

/**
 * 默认值
 */
function none($value, $default = '暂无')
{
    return !empty($value)? $value : $default;
}

/**
 * 格式化输出IP
 */
function ip_format(int $ip): string
{
    return preg_replace('/(\d+)\.(\d+)\.(\d+)\.(\d+)/i',"$1.$2.*.$4", long2ip($ip));
}