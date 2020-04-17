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

namespace DtApp\ThinkLibrary;

/**
 * 随机管理类
 * Class Random
 * @mixin Random
 * @package DtApp\ThinkLibrary
 */
class Random
{
    /**
     * 生成随机
     * @param int $length 长度
     * @param int $type 类型，1 纯数字，2 纯小写字母，3 纯大写字母，4 数字和小写字母，5 数字和大写字母，6 大小写字母，7 数字和大小写字母
     * @return false|string
     */
    public function generate(int $length = 6, int $type = 1)
    {
        // 取字符集数组
        $number = range(0, 9);
        $lowerLetter = range('a', 'z');
        $upperLetter = range('A', 'Z');
        // 根据type合并字符集
        if ($type == 1) {
            $charset = $number;
        } elseif ($type == 2) {
            $charset = $lowerLetter;
        } elseif ($type == 3) {
            $charset = $upperLetter;
        } elseif ($type == 4) {
            $charset = array_merge($number, $lowerLetter);
        } elseif ($type == 5) {
            $charset = array_merge($number, $upperLetter);
        } elseif ($type == 6) {
            $charset = array_merge($lowerLetter, $upperLetter);
        } elseif ($type == 7) {
            $charset = array_merge($number, $lowerLetter, $upperLetter);
        } else {
            $charset = $number;
        }
        $str = '';
        // 生成字符串
        for ($i = 0; $i < $length; $i++) {
            $str .= $charset[mt_rand(0, count($charset) - 1)];
            // 验证规则
            if ($type == 4 && strlen($str) >= 2) {
                if (!preg_match('/\d+/', $str) || !preg_match('/[a-z]+/', $str)) {
                    $str = substr($str, 0, -1);
                    $i = $i - 1;
                }
            }
            if ($type == 5 && strlen($str) >= 2) {
                if (!preg_match('/\d+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                    $str = substr($str, 0, -1);
                    $i = $i - 1;
                }
            }
            if ($type == 6 && strlen($str) >= 2) {
                if (!preg_match('/[a-z]+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                    $str = substr($str, 0, -1);
                    $i = $i - 1;
                }
            }
            if ($type == 7 && strlen($str) >= 3) {
                if (!preg_match('/\d+/', $str) || !preg_match('/[a-z]+/', $str) || !preg_match('/[A-Z]+/', $str)) {
                    $str = substr($str, 0, -2);
                    $i = $i - 2;
                }
            }
        }
        return $str;
    }
}
