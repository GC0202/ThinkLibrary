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

use DtApp\ThinkLibrary\exception\DtAppException;
use ZipArchive;

/**
 * 文件管理类
 * Class Files
 * @mixin Files
 * @package DtApp\ThinkLibrary
 */
class Files
{
    /**
     * 删除文件
     * @param string $name 路径
     * @return bool
     * @throws DtAppException
     */
    public function delete(string $name)
    {
        if (empty($name)) throw new DtAppException('请检查需要删除文件夹的名称');
        if (file_exists($name)) if (unlink($name)) return true;
        return false;
    }

    /**
     * 删除文件夹
     * @param string $name 路径
     * @return bool
     * @throws DtAppException
     */
    public function deletes(string $name)
    {
        if (empty($name)) throw new DtAppException('请检查需要删除文件夹的名称');
        //先删除目录下的文件：
        $dh = opendir($name);
        while ($file = readdir($dh)) {
            if ($file != "." && $file != "..") {
                $fullpath = $name . "/" . $file;
                if (!is_dir($fullpath)) {
                    unlink($fullpath);
                } else {
                    $this->deletes($fullpath);
                }
            }
        }
        closedir($dh);
        //删除当前文件夹：
        if (rmdir($name)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * 把文件夹里面的文件打包成zip文件
     * @param string $name 路径
     * @param string $suffix_name 需要打包的后缀名，默认.png
     * @param string $file_name 文件名，默认全部名
     * @return bool
     * @throws DtAppException
     */
    public function folderZip(string $name, string $suffix_name = '.png', string $file_name = '*')
    {
        if (empty($name)) throw new DtAppException('请检查需要打包的路径名称');
        try {
            // 获取目录下所有某个结尾的文件列表
            $list = glob($name . "{$file_name}.{$suffix_name}");
            $fileList = $list;
            $zip = new ZipArchive();
            // 打开压缩包
            $zip->open($name, ZipArchive::CREATE);
            //向压缩包中添加文件
            foreach ($fileList as $file) $zip->addFile($file, basename($file));
            //关闭压缩包
            $zip->close();
            return true;
        } catch (\Exception $e) {
            return false;
        }
    }
}