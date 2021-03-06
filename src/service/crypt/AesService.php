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

namespace DtApp\ThinkLibrary\service\crypt;

use DtApp\ThinkLibrary\Service;

class AesService extends Service
{
    /**
     * @var
     */
    private $key, $iv;

    /**
     * @param $str
     * @return $this
     */
    public function key($str): self
    {
        $this->key = $str;
        return $this;
    }

    /**
     * @param $str
     * @return $this
     */
    public function iv($str)
    {
        $this->iv = $str;
        return $this;
    }

    /**
     * 加密
     * @param $data
     * @return string
     */
    public function encrypt($data)
    {
        if (!empty(is_array($data))) {
            $data = json_encode($data, JSON_UNESCAPED_UNICODE);
        }
        return urlencode(base64_encode(openssl_encrypt($data, 'AES-128-CBC', $this->key, 1, $this->iv)));
    }

    /**
     * 解密
     * @param $data
     * @return false|string
     */
    public function decrypt($data)
    {
        return openssl_decrypt(base64_decode(urldecode($data)), "AES-128-CBC", $this->key, true, $this->iv);
    }
}
