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

namespace DtApp\ThinkLibrary\service\pinduoduo;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\Service;
use think\exception\HttpException;

/**
 * 进宝
 * Class JinBaoService
 * @package DtApp\ThinkLibrary\service\PinDuoDuo
 */
class JinBaoService extends Service
{
    /**
     * 接口地址
     * @var
     */
    private $url = 'http://gw-api.pinduoduo.com/api/router';

    /**
     * API接口名称
     * @var string
     */
    private $type = '';

    /**
     * 开放平台分配的
     * @var string
     */
    private $client_id, $client_secret = '';

    /**
     * 响应格式，即返回数据的格式，JSON或者XML（二选一），默认JSON，注意是大写
     * @var string
     */
    private $data_type = 'JSON';

    /**
     * API协议版本号，默认为V1，可不填
     * @var string
     */
    private $version = 'v1';

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 响应内容
     * @var
     */
    private $output;

    /*
     * 配置开放平台分配的clientId
     */
    public function clientId(string $clientId): self
    {
        $this->client_id = $clientId;
        return $this;
    }

    /**
     * 配置开放平台分配的clientSecret
     * @param string $clientSecret
     * @return $this
     */
    public function clientSecret(string $clientSecret): self
    {
        $this->client_secret = $clientSecret;
        return $this;
    }

    /**
     * 响应格式，即返回数据的格式，JSON或者XML（二选一），默认JSON，注意是大写
     * @param string $dataType
     * @return $this
     */
    public function dataType(string $dataType): self
    {
        $this->data_type = $dataType;
        return $this;
    }

    /**
     * 请求参数
     * @param array $param
     * @return $this
     */
    public function param(array $param): self
    {
        $this->param = $param;
        return $this;
    }

    /**
     * 网络请求
     * @return $this
     * @throws DtaException
     */
    private function http(): self
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $strParam = $this->createStrParam();
        $strParam .= 'sign=' . $sign;
        //访问服务
        $url = "{$this->url}?" . $strParam;
        var_dump($url);
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        $this->output = $result;
        return $this;
    }

    /**
     * 获取配置信息
     * @return $this
     */
    private function getConfig(): self
    {
        $this->client_id = config('dtapp.pinduoduo.jinbao.client_id');
        $this->client_secret = config('dtapp.pinduoduo.jinbao.client_secret');
        return $this;
    }

    /**
     * 获取商品信息 - 多多进宝商品查询
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.search
     * @return $this
     */
    public function goodsSearch(): self
    {
        $this->type = 'pdd.ddk.goods.search';
        return $this;
    }

    /**
     * 新增推广位 - 创建多多进宝推广位
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.pid.generate
     * @return $this
     */
    public function goodsPidGenerate(): self
    {
        $this->type = 'pdd.ddk.goods.pid.generate';
        return $this;
    }

    /**
     * 管理推广位 - 查询已经生成的推广位信息
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.pid.query
     * @return $this
     */
    public function goodsPidQuery(): self
    {
        $this->type = 'pdd.ddk.goods.pid.query';
        return $this;
    }

    /**
     * CPS订单数据 - 查询订单详情
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.order.detail.get
     * @return $this
     */
    public function orderDetailGet(): self
    {
        $this->type = 'pdd.ddk.order.detail.get';
        return $this;
    }

    /**
     * CPS订单数据 - 最后更新时间段增量同步推广订单信息
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.order.list.increment.get
     * @return $this
     */
    public function orderListIncrementGet(): self
    {
        $this->type = 'pdd.ddk.order.list.increment.get';
        return $this;
    }

    /**
     * CPS订单数据 - 用时间段查询推广订单接口
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.order.list.range.get
     * @return $this
     */
    public function orderListRangeGet(): self
    {
        $this->type = 'pdd.ddk.order.list.range.get';
        return $this;
    }

    /**
     * CPA效果数据 - 查询CPA数据
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.finance.cpa.query
     * @return $this
     */
    public function financeCpaQuery(): self
    {
        $this->type = 'pdd.ddk.finance.cpa.query';
        return $this;
    }

    /**
     * 单品推广- 多多进宝推广链接生成
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.promotion.url.generate
     * @return $this
     */
    public function goodsPromotionUrlGenerate(): self
    {
        $this->type = 'pdd.ddk.goods.promotion.url.generate';
        return $this;
    }

    /**
     * 单品推广- 多多客生成单品推广小程序二维码url
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.weapp.qrcode.url.gen
     * @return $this
     */
    public function weAppQrcodeUrlGen(): self
    {
        $this->type = 'pdd.ddk.weapp.qrcode.url.gen';
        return $this;
    }

    /**
     * 单品推广- 多多进宝转链接口
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.zs.unit.url.gen
     * @return $this
     */
    public function goodsZsUitUrlGen(): self
    {
        $this->type = 'pdd.ddk.goods.zs.unit.url.gen';
        return $this;
    }

    /**
     * 活动转链 - 生成多多进宝频道推广
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.resource.url.gen
     * @return $this
     */
    public function resourceUrlGen(): self
    {
        $this->type = 'pdd.ddk.resource.url.gen';
        return $this;
    }

    /**
     * 活动转链 - 多多进宝主题推广链接生成
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.theme.prom.url.generate
     * @return $this
     */
    public function themePromUrlGenerate(): self
    {
        $this->type = 'pdd.ddk.theme.prom.url.generate';
        return $this;
    }

    /**
     * 店铺推广 - 多多客生成店铺推广链接
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.mall.url.gen
     * @return $this
     */
    public function mallUrlGen(): self
    {
        $this->type = 'pdd.ddk.mall.url.gen';
        return $this;
    }

    /**
     * 营销工具 - 生成营销工具推广链接
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.rp.prom.url.generate
     * @return $this
     */
    public function rpPromUrlGenerate(): self
    {
        $this->type = 'pdd.ddk.rp.prom.url.generate';
        return $this;
    }

    /**
     * 获取商品信息 - 多多进宝商品详情查询
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.detail
     * @return $this
     */
    public function goodsDetail(): self
    {
        $this->type = 'pdd.ddk.goods.detail';
        return $this;
    }

    /**
     * 获取商品信息 - 查询商品的推广计划
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.unit.query
     * @return $this
     */
    public function goodsUnitQuery(): self
    {
        $this->type = 'pdd.ddk.goods.unit.query';
        return $this;
    }

    /**
     * 商品&店铺检索 - 获取商品基本信息接口
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.basic.info.get
     * @return $this
     */
    public function goodsBasicInfoGet(): self
    {
        $this->type = 'pdd.ddk.goods.basic.info.get';
        return $this;
    }

    /**
     * 商品&店铺检索 - 查询优惠券信息
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.coupon.info.query
     * @return $this
     */
    public function couponInfoQuery(): self
    {
        $this->type = 'pdd.ddk.coupon.info.query';
        return $this;
    }

    /**
     * 商品&店铺检索 - 查询店铺商品
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.mall.goods.list.get
     * @return $this
     */
    public function goodsListGet(): self
    {
        $this->type = 'pdd.ddk.mall.goods.list.get';
        return $this;
    }

    /**
     * 多多客获取爆款排行商品接口
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.top.goods.list.query
     * @return $this
     */
    public function topGoodsListQuery(): self
    {
        $this->type = 'pdd.ddk.top.goods.list.query';
        return $this;
    }

    /**
     * 爆品推荐 - 多多进宝商品推荐API
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.recommend.get
     * @return $this
     */
    public function goodsRecommendGet(): self
    {
        $this->type = 'pdd.ddk.goods.recommend.get';
        return $this;
    }

    /**
     * 爆品推荐 - 多多进宝主题列表查询
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.theme.list.get
     * @return $this
     */
    public function themeListGet(): self
    {
        $this->type = 'pdd.ddk.theme.list.get';
        return $this;
    }

    /**
     * 活动选品库 - 多多进宝主题商品查询
     * https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.theme.goods.search
     * @return $this
     */
    public function themeGoodsSearch(): self
    {
        $this->type = 'pdd.ddk.theme.goods.search';
        return $this;
    }

    /**
     * 生成商城-频道推广链接
     * https://open.pinduoduo.com/application/document/api?id=pdd.ddk.cms.prom.url.
     * @return $this
     */
    public function cmsPromUrlGenerate(): self
    {
        $this->type = 'pdd.ddk.cms.prom.url.generate';
        return $this;
    }

    /**
     * 查询直播间详情
     * https://open.pinduoduo.com/application/document/api?id=pdd.ddk.live.detail
     * @return $this
     */
    public function liveDetail(): self
    {
        $this->type = 'pdd.ddk.live.detail';
        return $this;
    }

    /**
     * 查询直播间列表
     * https://open.pinduoduo.com/application/document/api?id=pdd.ddk.live.list
     * @return $this
     */
    public function liveList(): self
    {
        $this->type = 'pdd.ddk.live.list';
        return $this;
    }

    /**
     * 生成直播间推广链接
     * https://open.pinduoduo.com/application/document/api?id=pdd.ddk.live.url.gen
     * @return $this
     */
    public function liveUrlGen(): self
    {
        $this->type = 'pdd.ddk.live.url.gen';
        return $this;
    }

    /**
     * 多多客生成转盘抽免单url
     * https://open.pinduoduo.com/application/document/api?id=pdd.ddk.lottery.url.gen
     * @return $this
     */
    public function lotteryUrlGen(): self
    {
        $this->type = 'pdd.ddk.lottery.url.gen';
        return $this;
    }

    /**
     * 查询是否绑定备案
     * https://open.pinduoduo.com/application/document/api?id=pdd.ddk.member.authority.query
     * @return $this
     */
    public function memberAuthorityQuery(): self
    {
        $this->type = 'pdd.ddk.member.authority.query';
        return $this;
    }

    /**
     * 查询商品标签列表
     * https://open.pinduoduo.com/application/document/api?id=pdd.goods.opt.get
     * @return $this
     */
    public function goodsOptGet(): self
    {
        $this->type = 'pdd.goods.opt.get';
        return $this;
    }

    /**
     * 自定义接口
     * @param string $type
     * @return $this
     */
    public function setMethod($type = ''): self
    {
        $this->type = $type;
        return $this;
    }


    /**
     * 返回数组数据
     * @return array|mixed
     * @throws DtaException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException(404, '请开启curl模块！');
        }
        if (empty($this->client_id)) {
            $this->getConfig();
        }
        if (empty($this->client_id)) {
            throw new DtaException('请检查client_id参数');
        }
        $this->param['type'] = $this->type;
        $this->param['client_id'] = $this->client_id;
        $this->param['timestamp'] = time();
        $this->param['data_type'] = $this->data_type;
        $this->param['version'] = $this->version;
        $this->http();
        if (isset($this->output['error_response'])) {
            // 错误
            if (is_array($this->output)) {
                return $this->output;
            }
            if (is_object($this->output)) {
                return $this->object2array($this->output);
            }
            return json_decode($this->output, true);
        }
        // 正常
        if (is_array($this->output)) {
            return $this->output;
        }
        if (is_object($this->output)) {
            $this->output = $this->object2array($this->output);
            return $this->output;
        }
        $this->output = json_decode($this->output, true);
        return $this->output;
    }

    /**
     * @param $object
     * @return array
     */
    private function object2array(&$object): array
    {
        if (is_object($object)) {
            $arr = (array)($object);
        } else {
            $arr = &$object;
        }
        if (is_array($arr)) {
            foreach ($arr as $varName => $varValue) {
                $arr[$varName] = $this->object2array($varValue);
            }
        }
        return $arr;
    }

    /**
     * 签名
     * @return string
     * @throws DtaException
     */
    private function createSign(): string
    {
        if (empty($this->client_secret)) {
            $this->getConfig();
        }
        if (empty($this->client_secret)) {
            throw new DtaException('请检查client_secret参数}');
        }
        $sign = $this->client_secret;
        ksort($this->param);
        foreach ($this->param as $key => $val) {
            if ($key !== '' && $val !== '') {
                $sign .= $key . $val;
            }
        }
        $sign .= $this->client_secret;
        $sign = strtoupper(md5($sign));
        return $sign;
    }

    /**
     * 组参
     * @return string
     */
    private function createStrParam(): string
    {
        $strParam = '';
        foreach ($this->param as $key => $val) {
            if ($key !== '' && $val !== '' && !is_array($val)) {
                $strParam .= $key . '=' . urlencode($val) . '&';
            }
        }
        return $strParam;
    }

    /**
     * 获取频道ID
     * @return array[]
     */
    public function getChannelTypeList(): array
    {
        return [
            [
                // https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.goods.recommend.get
                'name' => '商品推荐',
                'list' => [
                    [
                        'name' => '1.9包邮',
                        'channel_type' => 0
                    ],
                    [
                        'name' => '今日爆款',
                        'channel_type' => 1
                    ],
                    [
                        'name' => '品牌清仓',
                        'channel_type' => 2
                    ],
                    [
                        'name' => '相似商品推荐',
                        'channel_type' => 3
                    ],
                    [
                        'name' => '猜你喜欢',
                        'channel_type' => 4
                    ],
                    [
                        'name' => '实时热销',
                        'channel_type' => 5
                    ],
                    [
                        'name' => '实时收益',
                        'channel_type' => 6
                    ],
                    [
                        'name' => '今日畅销',
                        'channel_type' => 7
                    ],
                    [
                        'name' => '高佣榜单',
                        'channel_type' => 8
                    ],
                ]
            ],
        ];
    }

    /**
     * 获取频道来源ID
     * @return array[]
     */
    public function getResourceTypeList(): array
    {
        return [
            [
                // https://jinbao.pinduoduo.com/third-party/api-detail?apiName=pdd.ddk.resource.url.gen
                'name' => '频道推广',
                'list' => [
                    [
                        'name' => '限时秒杀',
                        'resource_type' => 4
                    ],
                    [
                        'name' => '充值中心',
                        'resource_type' => 39997
                    ],
                    [
                        'name' => '转链',
                        'resource_type' => 39998
                    ],
                    [
                        'name' => '百亿补贴',
                        'resource_type' => 39996
                    ],
                ]
            ],
        ];
    }
}
