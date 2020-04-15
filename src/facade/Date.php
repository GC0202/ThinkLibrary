<?php

// +----------------------------------------------------------------------
// | ThinkLibrary 6.0 for ThinkPhP 6.0
// +----------------------------------------------------------------------
// | 版权所有 2017~2020 [ https://www.dtapp.net ]
// +----------------------------------------------------------------------
// | 官方网站: https://gitee.com/liguangchun/ThinkLibrary
// +----------------------------------------------------------------------
// | 开源协议 ( https://mit-license.org )
// +----------------------------------------------------------------------
// | gitee 仓库地址 ：https://gitee.com/liguangchun/ThinkLibrary
// | github 仓库地址 ：https://github.com/GC0202/ThinkLibrary
// | Packagist 地址 ：https://packagist.org/packages/liguangchun/think-library
// +----------------------------------------------------------------------

namespace DtApp\ThinkLibrary\facade;

use think\Facade;

/**
 * 日期门面
 * Class Preg
 * @see \DtApp\ThinkLibrary\Date
 * @package think\facade
 * @mixin \DtApp\ThinkLibrary\Date
 * @package DtApp\ThinkLibrary\facade
 */
class Date extends Facade
{
    protected static function getFacadeClass()
    {
        return 'DtApp\ThinkLibrary\Date';
    }
}