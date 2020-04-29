<img align="right" width="100" src="https://cdn.oss.liguangchun.cn/04/999e9f2f06d396968eacc10ce9bc8a.png" alt="dtApp Logo"/>

<h1 align="left"><a href="https://www.dtapp.net/">ThinkPHP6扩展包</a></h1>

📦 ThinkPHP6扩展包

[![Latest Stable Version](https://poser.pugx.org/liguangchun/think-library/v/stable)](https://packagist.org/packages/liguangchun/think-library) 
[![Latest Unstable Version](https://poser.pugx.org/liguangchun/think-library/v/unstable)](https://packagist.org/packages/liguangchun/think-library) 
[![Total Downloads](https://poser.pugx.org/liguangchun/think-library/downloads)](https://packagist.org/packages/liguangchun/think-library) 
[![License](https://poser.pugx.org/liguangchun/think-library/license)](https://packagist.org/packages/liguangchun/think-library)

## 依赖环境

1. PHP7.0 版本及以上

## 安装

### 开发版
```text
composer require liguangchun/think-library ^6.x-dev -vvv
```

### 稳定版
```text
composer require liguangchun/think-library ^6.0.* -vvv
```

## 更新

```text
composer update liguangchun/think-library -vvv
```

## 删除

```text
composer remove liguangchun/think-library -vvv
```

## 抖音服务使用示例

```text

use DtApp\ThinkLibrary\service\douyin\DouYinException;
use DtApp\ThinkLibrary\service\douyin\WatermarkService;

try {
    // 方法一
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 方法二
    $dy = WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/');
    var_dump($dy->getAll()->toArray());
    // 获取全部信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 获取原全部信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getApi()->toArray());
    // 获取视频信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getVideoInfo()->toArray());
    // 获取音频信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getMusicInfo()->toArray());
    // 获取分享信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getShareInfo()->toArray());
    // 获取作者信息
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAuthorInfo()->toArray());
    // 返回数组数据方法
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toArray());
    // 返回Object数据方法
    var_dump(WatermarkService::instance()->url('https://v.douyin.com/vPGAdM/')->getAll()->toObject());
} catch (DouYinException $e) {
    // 错误提示
    var_dump($e->getMessage());
}

```
