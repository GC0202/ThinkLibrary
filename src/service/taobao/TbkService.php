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

namespace DtApp\ThinkLibrary\service\taobao;

use DtApp\ThinkLibrary\exception\DtaException;
use DtApp\ThinkLibrary\facade\Times;
use DtApp\ThinkLibrary\Service;
use think\exception\HttpException;

/**
 * 淘宝客
 * Class TbkService
 * @package DtApp\ThinkLibrary\service\TaoBao
 */
class TbkService extends Service
{
    /**
     * 是否为沙箱
     * @var bool
     */
    private $sandbox = false;

    /**
     * TOP分配给应用的
     * @var string
     */
    private $app_key, $app_secret = "";

    /**
     * API接口名称
     * @var string
     */
    private $method = '';

    /**
     * 签名的摘要算法
     * @var string
     */
    private $sign_method = "md5";

    /**
     * 需要发送的的参数
     * @var
     */
    private $param;

    /**
     * 响应格式
     * @var string
     */
    private $format = "json";

    /**
     * API协议版本
     * @var string
     */
    private $v = "2.0";

    /**
     * 响应内容
     * @var
     */
    private $output;

    /**
     * 安全协议
     * @var string
     */
    private $protocol = 'http';

    /**
     * 设置安全协议
     * @param string $protocol
     * @return $this
     */
    public function setProtocol($protocol = 'http'): self
    {
        $this->protocol = $protocol;
        return $this;
    }

    /**
     * 是否为沙箱
     * @return $this
     */
    public function sandbox(): self
    {
        $this->sandbox = true;
        return $this;
    }

    /**
     * 配置应用的AppKey
     * @param string $appKey
     * @return $this
     */
    public function appKey(string $appKey): self
    {
        $this->app_key = $appKey;
        return $this;
    }

    /**
     * 应用AppSecret
     * @param string $appSecret
     * @return $this
     */
    public function appSecret(string $appSecret): self
    {
        $this->app_secret = $appSecret;
        return $this;
    }

    /**
     * API接口名称
     * @param string $signMethod
     * @return $this
     */
    public function signMethod(string $signMethod): self
    {
        $this->sign_method = $signMethod;
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
     * 获取配置信息
     * @return $this
     */
    private function getConfig(): self
    {
        $this->app_key = config('dtapp.taobao.tbk.app_key');
        $this->app_secret = config('dtapp.taobao.tbk.app_secret');
        return $this;
    }

    /**
     * 淘宝客-推广者-所有订单查询
     * @return $this
     */
    public function orderDetailsGet(): self
    {
        $this->method = 'taobao.tbk.order.details.get';
        return $this;
    }

    /**
     * 淘宝客-服务商-所有订单查询
     * @return $this
     */
    public function scOrderDetailsGet(): self
    {
        $this->method = 'taobao.tbk.sc.order.details.get';
        return $this;
    }

    /**
     * 淘宝客-服务商-淘口令解析&转链
     * @return $this
     */
    public function scTpwdConvert(): self
    {
        $this->method = 'taobao.tbk.sc.tpwd.convert';
        return $this;
    }

    /**
     * 淘宝客-服务商-维权退款订单查询
     * @return $this
     */
    public function scRelationRefund(): self
    {
        $this->method = 'taobao.tbk.sc.relation.refund';
        return $this;
    }

    /**
     * 淘宝客-服务商-店铺链接转换
     * @return $this
     */
    public function scShopConvert(): self
    {
        $this->method = 'taobao.tbk.sc.shop.convert';
        return $this;
    }

    /**
     * 淘宝客-推广者-官办找福利页
     * @return $this
     */
    public function jzfConvert(): self
    {
        $this->method = 'taobao.tbk.jzf.convert';
        return $this;
    }

    /**
     * 淘宝客-推广者-维权退款订单查询
     * @return $this
     */
    public function relationRefund(): self
    {
        $this->method = 'taobao.tbk.relation.refund';
        return $this;
    }

    /**
     * 淘宝客-服务商-淘礼金创建
     * @return $this
     */
    public function scVegasTljCreate(): self
    {
        $this->method = 'taobao.tbk.sc.vegas.tlj.create';
        return $this;
    }

    /**
     * 淘宝客商品展示规则获取
     * @return $this
     */
    public function itemRuleGet(): self
    {
        $this->method = 'qimen.taobao.tbk.item.rule.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-处罚订单查询
     * @return $this
     */
    public function dgPunishOrderGet(): self
    {
        $this->method = 'taobao.tbk.dg.punish.order.get';
        return $this;
    }

    /**
     * 淘宝客-公用-淘口令解析出原链接
     * @return $this
     */
    public function tpwdParse(): self
    {
        $this->method = 'taobao.tbk.tpwd.parse';
        return $this;
    }

    /**
     * 淘宝客-推广者-新用户订单明细查询
     * @return $this
     */
    public function dgNewUserOrderGet(): self
    {
        $this->method = 'taobao.tbk.dg.newuser.order.get';
        return $this;
    }

    /**
     * 淘宝客-服务商-新用户订单明细查询
     * @return $this
     */
    public function scNewuserOrderGet(): self
    {
        $this->method = 'taobao.tbk.sc.newuser.order.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-拉新活动对应数据查询
     * @return $this
     */
    public function dgNewUserOrderSum(): self
    {
        $this->method = 'taobao.tbk.dg.newuser.order.sum';
        return $this;
    }

    /**
     * 超级红包发放个数 - 淘宝客-推广者-查询超级红包发放个数
     * https://open.taobao.com/api.htm?spm=a2e0r.13193907.0.0.210524ad2gvyOW&docId=47593&docType=2
     * @return $this
     */
    public function dgVegasSendReport(): self
    {
        $this->method = 'taobao.tbk.dg.vegas.send.report';
        return $this;
    }

    /**
     * 淘宝客-推广者-官方活动转链
     * @return $this
     */
    public function activityInfoGet(): self
    {
        $this->method = 'taobao.tbk.activity.info.get';
        return $this;
    }

    /**
     * 淘宝客-服务商-官方活动转链
     * @return $this
     */
    public function scActivityInfoGet(): self
    {
        $this->method = 'taobao.tbk.sc.activity.info.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-联盟口令生成
     * @return $this
     */
    public function textTpwdCreate(): self
    {
        $this->method = 'taobao.tbk.text.tpwd.create';
        return $this;
    }

    /**
     * 淘宝客-推广者-官方活动转链(2020.9.30下线)
     * @return $this
     */
    public function activityLinkGet(): self
    {
        $this->method = 'taobao.tbk.activitylink.get';
        return $this;
    }

    /**
     * 淘宝客-公用-淘口令生成
     * @return $this
     */
    public function tpWdCreate(): self
    {
        $this->method = 'taobao.tbk.tpwd.create';
        return $this;
    }

    /**
     * 淘宝客-公用-长链转短链
     * @return $this
     */
    public function spreadGet(): self
    {
        $this->method = 'taobao.tbk.spread.get';
        return $this;
    }

    /**
     * 聚划算商品搜索接口
     * https://open.taobao.com/api.htm?docId=28762&docType=2&scopeId=16517
     * @return $this
     */
    public function itemsSearch(): self
    {
        $this->method = 'taobao.ju.items.search';
        return $this;
    }

    /**
     * 淘抢购api(2020.9.30下线)
     * @return $this
     */
    public function juTqgGet(): self
    {
        $this->method = 'taobao.tbk.ju.tqg.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-淘礼金创建
     * @return $this
     */
    public function dgVegasTljCreate(): self
    {
        $this->method = 'taobao.tbk.dg.vegas.tlj.create';
        return $this;
    }

    /**
     * 淘宝客-推广者-轻店铺淘口令解析
     * @return $this
     */
    public function lightshopTbpswdParse(): self
    {
        $this->method = 'taobao.tbk.lightshop.tbpswd.parse';
        return $this;
    }

    /**
     * 淘宝客-推广者-淘礼金发放及使用报表
     * @return $this
     */
    public function dgVegasTljInstanceReport(): self
    {
        $this->method = 'taobao.tbk.dg.vegas.tlj.instance.report';
        return $this;
    }

    /**
     * 淘宝客-服务商-手淘群发单
     * @return $this
     */
    public function scGroupchatMessageSend(): self
    {
        $this->method = 'taobao.tbk.sc.groupchat.message.send';
        return $this;
    }

    /**
     * 淘宝客-服务商-手淘群创建
     * @return $this
     */
    public function scGroupchatCreate(): self
    {
        $this->method = 'taobao.tbk.sc.groupchat.create';
        return $this;
    }

    /**
     * 淘宝客-服务商-手淘群查询
     * @return $this
     */
    public function scGroupchatGet(): self
    {
        $this->method = 'taobao.tbk.sc.groupchat.get';
        return $this;
    }

    /**
     * 淘宝客-公用-手淘注册用户判定
     * @return $this
     */
    public function tbinfoGet(): self
    {
        $this->method = 'taobao.tbk.tbinfo.get';
        return $this;
    }

    /**
     * 淘宝客-公用-pid校验
     * @return $this
     */
    public function tbkinfoGet(): self
    {
        $this->method = 'taobao.tbk.tbkinfo.get';
        return $this;
    }

    /**
     * 淘宝客-公用-私域用户邀请码生成
     * @return $this
     */
    public function scInvIteCodeGet(): self
    {
        $this->method = 'taobao.tbk.sc.invitecode.get';
        return $this;
    }

    /**
     * 淘宝客-公用-私域用户备案信息查询
     * @return $this
     */
    public function scPublisherInfoGet(): self
    {
        $this->method = 'taobao.tbk.sc.publisher.info.get';
        return $this;
    }

    /**
     * 淘宝客-公用-私域用户备案
     * @return $this
     */
    public function scPublisherInfoSave(): self
    {
        $this->method = 'taobao.tbk.sc.publisher.info.save';
        return $this;
    }

    /**
     * 淘宝客-公用-淘宝客商品详情查询(简版)
     * @return $this
     */
    public function itemInfoGet(): self
    {
        $this->method = 'taobao.tbk.item.info.get';
        return $this;
    }

    /**
     * 淘宝客-公用-阿里妈妈推广券详情查询
     * @return $this
     */
    public function couponGet(): self
    {
        $this->method = 'taobao.tbk.coupon.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-物料搜索
     * @return $this
     */
    public function dgMaterialOptional(): self
    {
        $this->method = 'taobao.tbk.dg.material.optional';
        return $this;
    }

    /**
     * 淘宝客-推广者-店铺搜索
     * @return $this
     */
    public function shopGet(): self
    {
        $this->method = 'taobao.tbk.shop.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-物料精选
     * @return $this
     */
    public function dgOpTiUsMaterial(): self
    {
        $this->method = 'taobao.tbk.dg.optimus.material';
        return $this;
    }

    /**
     * 淘宝客-推广者-图文内容输出(2020.9.30下线)
     * @return $this
     */
    public function contentGet(): self
    {
        $this->method = 'taobao.tbk.content.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-图文内容效果数据(2020.9.30下线)
     * @return $this
     */
    public function contentEffectGet(): self
    {
        $this->method = 'taobao.tbk.content.effect.get';
        return $this;
    }


    /**
     * 淘宝客-推广者-商品出词
     * @return $this
     */
    public function itemWordGet(): self
    {
        $this->method = 'taobao.tbk.item.word.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-商品链接转换
     * @return $this
     */
    public function itemConvert(): self
    {
        $this->method = 'taobao.tbk.item.convert';
        return $this;
    }

    /**
     * 淘宝客-公用-链接解析出商品id
     * @return $this
     */
    public function itemClickExtract(): self
    {
        $this->method = 'taobao.tbk.item.click.extract';
        return $this;
    }

    /**
     * 淘宝客-公用-商品关联推荐(2020.9.30下线)
     * @return $this
     */
    public function itemRecommendGet(): self
    {
        $this->method = 'taobao.tbk.item.recommend.get';
        return $this;
    }

    /**
     * 淘宝客-公用-店铺关联推荐
     * @return $this
     */
    public function shopRecommendGet(): self
    {
        $this->method = 'taobao.tbk.shop.recommend.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-选品库宝贝信息(2020.9.30下线)
     * @return $this
     */
    public function uaTmFavoritesItemGet(): self
    {
        $this->method = 'taobao.tbk.uatm.favorites.item.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-选品库宝贝列表(2020.9.30下线)
     * @return $this
     */
    public function uaTmFavoritesGet(): self
    {
        $this->method = 'taobao.tbk.uatm.favorites.get';
        return $this;
    }

    /**
     * 淘宝客-服务商-官方活动转链(2020.9.30下线)
     * @return $this
     */
    public function scActivityLinkToolGet(): self
    {
        $this->method = 'taobao.tbk.sc.activitylink.toolget';
        return $this;
    }

    /**
     * 淘宝客-服务商-处罚订单查询
     * @return $this
     */
    public function scPunishOrderGet(): self
    {
        $this->method = 'taobao.tbk.sc.punish.order.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-创建推广位
     * @return $this
     */
    public function adZoneCreate(): self
    {
        $this->method = 'taobao.tbk.adzone.create';
        return $this;
    }

    /**
     * 淘宝客文本淘口令
     * @return $this
     */
    public function tpwdMixCreate(): self
    {
        $this->method = 'taobao.tbk.tpwd.mix.create';
        return $this;
    }

    /**
     * 淘宝客-推广者-b2c平台用户行为跟踪服务商
     * @return $this
     */
    public function traceBtocAddtrace(): self
    {
        $this->method = 'taobao.tbk.trace.btoc.addtrace';
        return $this;
    }

    /**
     * 淘宝客-推广者-登陆信息跟踪服务商
     * @return $this
     */
    public function traceLogininfoAdd(): self
    {
        $this->method = 'taobao.tbk.trace.logininfo.add';
        return $this;
    }

    /**
     * 淘宝客-推广者-用户行为跟踪服务商
     * @return $this
     */
    public function traceShopitemAddtrace(): self
    {
        $this->method = 'taobao.tbk.trace.shopitem.addtrace';
        return $this;
    }

    /**
     * 淘宝客-推广者-商品三方分成链接转换
     * @return $this
     */
    public function itemShareConvert(): self
    {
        $this->method = 'taobao.tbk.item.share.convert';
        return $this;
    }

    /**
     * 淘宝客-推广者-店铺链接转换
     * @return $this
     */
    public function shopConvert(): self
    {
        $this->method = 'taobao.tbk.shop.convert';
        return $this;
    }

    /**
     * 淘宝客-推广者-店铺三方分成链接转换
     * @return $this
     */
    public function shopShareConvert(): self
    {
        $this->method = 'taobao.tbk.shop.share.convert';
        return $this;
    }

    /**
     * 淘宝客-推广者-返利商家授权查询
     * @return $this
     */
    public function rebateAuthGet(): self
    {
        $this->method = 'taobao.tbk.rebate.auth.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-返利订单查询
     * @return $this
     */
    public function rebateOrderGet(): self
    {
        $this->method = 'taobao.tbk.rebate.order.get';
        return $this;
    }

    /**
     * 淘宝客-推广者-根据宝贝id批量查询优惠券
     * @return $this
     */
    public function itemidCouponGet(): self
    {
        $this->method = 'taobao.tbk.itemid.coupon.get';
        return $this;
    }

    /**
     * 淘宝客-服务商-保护门槛
     * @return $this
     */
    public function dataReport(): self
    {
        $this->method = 'taobao.tbk.data.report';
        return $this;
    }

    /**
     * 淘宝客-推广者-单品券高效转链
     * @return $this
     */
    public function couponConvert(): self
    {
        $this->method = 'taobao.tbk.coupon.convert';
        return $this;
    }

    /**
     * 淘宝客-推广者-淘口令解析&三方分成转链
     * @return $this
     */
    public function tpwdShareConvert(): self
    {
        $this->method = 'taobao.tbk.tpwd.share.convert';
        return $this;
    }

    /**
     * 淘宝客-推广者-淘口令解析&转链
     * @return $this
     */
    public function tpwdConvert(): self
    {
        $this->method = 'taobao.tbk.tpwd.convert';
        return $this;
    }

    /**
     * 淘宝客-服务商-创建推广者位
     * @return $this
     */
    public function scAdzoneCreate(): self
    {
        $this->method = 'taobao.tbk.sc.adzone.create';
        return $this;
    }

    /**
     * 淘宝客-服务商-物料精选
     * @return $this
     */
    public function scOptimusMaterial(): self
    {
        $this->method = 'taobao.tbk.sc.optimus.material';
        return $this;
    }

    /**
     * 淘宝客-服务商-物料搜索
     * @return $this
     */
    public function scMaterialOptional(): self
    {
        $this->method = 'taobao.tbk.sc.material.optional';
        return $this;
    }

    /**
     * 淘宝客-服务商-拉新活动数据查询
     * @return $this
     */
    public function scNewuserOrderSum(): self
    {
        $this->method = 'taobao.tbk.sc.newuser.order.sum';
        return $this;
    }

    /**
     * 自定义接口
     * @param string $method
     * @return $this
     */
    public function setMethod($method = ''): self
    {
        $this->method = $method;
        return $this;
    }

    /**
     * 返回Array
     * @return array|mixed
     * @throws DtaException
     */
    public function toArray()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException(404, '请开启curl模块！');
        }
        $this->format = "json";
        if (empty($this->app_key)) {
            $this->getConfig();
        }
        if (empty($this->app_key)) {
            throw new DtaException('请检查app_key参数');
        }
        if (empty($this->method)) {
            throw new DtaException('请检查method参数');
        }
        $this->param['app_key'] = $this->app_key;
        $this->param['method'] = $this->method;
        $this->param['format'] = $this->format;
        $this->param['v'] = $this->v;
        $this->param['sign_method'] = $this->sign_method;
        $this->param['timestamp'] = Times::getData();
        $this->http();
        if (isset($this->output['error_response'])) {
            // 错误
            if (is_array($this->output)) {
                return $this->output;
            }
            if (is_object($this->output)) {
                $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
            }
            return json_decode($this->output, true);
        }

        // 正常
        if (is_array($this->output)) {
            return $this->output;
        }
        if (is_object($this->output)) {
            $this->output = json_encode($this->output, JSON_UNESCAPED_UNICODE);
        }
        $this->output = json_decode($this->output, true);
        return $this->output;
    }

    /**
     * 返回Xml
     * @return mixed
     * @throws DtaException
     */
    public function toXml()
    {
        //首先检测是否支持curl
        if (!extension_loaded("curl")) {
            throw new HttpException('请开启curl模块！', E_USER_DEPRECATED);
        }
        $this->format = "xml";
        $this->http();
        return $this->output;
    }

    /**
     * 网络请求
     * @throws DtaException
     */
    private function http(): void
    {
        //生成签名
        $sign = $this->createSign();
        //组织参数
        $strParam = $this->createStrParam();
        $strParam .= 'sign=' . $sign;
        //访问服务
        if ($this->protocol === 'http') {
            if (empty($this->sandbox)) {
                $url = 'http://gw.api.taobao.com/router/rest?' . $strParam;
            } else {
                $url = 'http://gw.api.tbsandbox.com/router/rest?' . $strParam;
            }
        }
        if ($this->protocol === 'https') {
            if (empty($this->sandbox)) {
                $url = 'https://eco.taobao.com/router/rest?' . $strParam;
            } else {
                $url = 'https://gw.api.tbsandbox.com/router/rest?' . $strParam;
            }
        }
        $result = file_get_contents($url);
        $result = json_decode($result, true);
        $this->output = $result;
    }

    /**
     * 签名
     * @return string
     * @throws DtaException
     */
    private function createSign(): string
    {
        if (empty($this->app_secret)) {
            $this->getConfig();
        }
        if (empty($this->app_secret)) {
            throw new DtaException('请检查app_secret参数');
        }
        $sign = $this->app_secret;
        ksort($this->param);
        foreach ($this->param as $key => $val) {
            if ($key !== '' && $val !== '') {
                $sign .= $key . $val;
            }
        }
        $sign .= $this->app_secret;
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
            if ($key !== '' && $val !== '') {
                $strParam .= $key . '=' . urlencode($val) . '&';
            }
        }
        return $strParam;
    }

    /**
     * 获取活动物料
     * @return array[]
     */
    public function getActivityMaterialIdList(): array
    {
        return [
            [
                // https://market.m.taobao.com/app/qn/toutiao-new/index-pc.html#/detail/10628646?_k=tcswm1
                'name' => '口碑',
                'list' => [
                    [
                        'name' => '口碑主会场活动（2.3%佣金起）',
                        'material_id' => 1583739244161
                    ],
                    [
                        'name' => '生活服务分会场活动（2.3%佣金起）',
                        'material_id' => 1583739244162
                    ]
                ]
            ],
            [
                // https://market.m.taobao.com/app/qn/toutiao-new/index-pc.html#/detail/10628647?_k=hwggf9
                // https://market.m.taobao.com/app/qn/toutiao-new/index-pc.html#/detail/10630427?_k=sdet4e
                // https://market.m.taobao.com/app/qn/toutiao-new/index-pc.html#/detail/10630361?_k=nq6zgt
                'name' => '饿了么',
                'list' => [
                    [
                        'name' => '聚合页（6%佣金起）',
                        'material_id' => 1571715733668
                    ],
                    [
                        'name' => '新零售（4%佣金起）',
                        'material_id' => 1585018034441
                    ],
                    [
                        'name' => '餐饮',
                        'material_id' => 1579491209717
                    ],
                ]
            ],
            [
                // https://market.m.taobao.com/app/qn/toutiao-new/index-pc.html#/detail/10634663?_k=zqgq01
                'name' => '卡券（饭票）',
                'list' => [
                    [
                        'name' => '饿了么卡券（1元以下商品）',
                        'material_id' => 32469
                    ],
                    [
                        'name' => '饿了么卡券投放全网商品库',
                        'material_id' => 32470
                    ],
                    [
                        'name' => '饿了么卡券（5折以下）',
                        'material_id' => 32603
                    ],
                    [
                        'name' => '饿了么头部全国KA商品库',
                        'material_id' => 32663
                    ],
                    [
                        'name' => '饿了么卡券招商爆品库',
                        'material_id' => 32738
                    ],
                ]
            ],
        ];
    }

    /**
     * 获取官方物料API汇总
     * https://market.m.taobao.com/app/qn/toutiao-new/index-pc.html#/detail/10628875?_k=gpov9a
     * @return array
     */
    public function getMaterialIdList(): array
    {
        return [
            [
                'name' => '相似推荐',
                'list' => [
                    [
                        'name' => '相似推荐',
                        'material_id' => 13256
                    ]
                ]
            ],
            [
                'name' => '官方推荐',
                'list' => [
                    [
                        'name' => '聚划算满减满折',
                        'material_id' => 32366
                    ],
                    [
                        'name' => '猫超满减满折',
                        'material_id' => 27160
                    ]
                ]
            ],
            [
                'name' => '猜你喜欢',
                'list' => [
                    [
                        'name' => '含全部商品',
                        'material_id' => 6708
                    ],
                    [
                        'name' => '营销商品库商品（此为具备“私域用户管理-会员运营管理功能”的媒体专用）',
                        'material_id' => 28017
                    ]
                ]
            ],
            [
                'name' => '好券直播',
                'list' => [
                    [
                        'name' => '综合',
                        'material_id' => 3756
                    ],
                    [
                        'name' => '女装',
                        'material_id' => 3767
                    ],
                    [
                        'name' => '家居家装',
                        'material_id' => 3758
                    ],
                    [
                        'name' => '数码家电',
                        'material_id' => 3759
                    ],
                    [
                        'name' => '鞋包配饰',
                        'material_id' => 3762
                    ],
                    [
                        'name' => '美妆个护',
                        'material_id' => 3763
                    ],
                    [
                        'name' => '男装',
                        'material_id' => 3764
                    ],
                    [
                        'name' => '内衣',
                        'material_id' => 3765
                    ],
                    [
                        'name' => '母婴',
                        'material_id' => 3760
                    ],
                    [
                        'name' => '食品',
                        'material_id' => 3761
                    ],
                    [
                        'name' => '运动户外',
                        'material_id' => 3766
                    ]
                ]
            ],
            [
                'name' => '实时热销榜',
                'list' => [
                    [
                        'name' => '综合',
                        'material_id' => 28026
                    ],
                    [
                        'name' => '大服饰',
                        'material_id' => 28029
                    ],
                    [
                        'name' => '大快消',
                        'material_id' => 28027
                    ],
                    [
                        'name' => '电器美家',
                        'material_id' => 28028
                    ]
                ]
            ],
            [
                'name' => '本地化生活',
                'list' => [
                    [
                        'name' => '今日爆款（综合类目）',
                        'material_id' => 30443
                    ],
                    [
                        'name' => '淘票票（电影代金券）',
                        'material_id' => 19812
                    ],
                    [
                        'name' => '大麦网（演出/演唱会/剧目/会展）',
                        'material_id' => 25378
                    ],
                    [
                        'name' => '优酷会员（视频年卡）',
                        'material_id' => 28636
                    ],
                    [
                        'name' => '有声内容（喜马拉雅年卡，儿童节目等）',
                        'material_id' => 29105
                    ],
                    [
                        'name' => '阿里健康（hpv疫苗预约）',
                        'material_id' => 25885
                    ],
                    [
                        'name' => '阿里健康（体检）',
                        'material_id' => 25886
                    ],
                    [
                        'name' => '阿里健康（口腔）',
                        'material_id' => 25888
                    ],
                    [
                        'name' => '阿里健康（基因检测）',
                        'material_id' => 25890
                    ],
                    [
                        'name' => '飞猪（签证）',
                        'material_id' => 26077
                    ],
                    [
                        'name' => '飞猪（酒店）',
                        'material_id' => 27913
                    ],
                    [
                        'name' => '飞猪（自助餐）',
                        'material_id' => 27914
                    ],
                    [
                        'name' => '飞猪（门票）',
                        'material_id' => 19811
                    ],
                    [
                        'name' => '口碑（肯德基/必胜客/麦当劳）',
                        'material_id' => 19810
                    ],
                    [
                        'name' => '口碑（生活服务）',
                        'material_id' => 28888
                    ],
                    [
                        'name' => '天猫无忧购（家政服务）',
                        'material_id' => 19814
                    ],
                    [
                        'name' => '汽车定金（汽车定金）',
                        'material_id' => 28397
                    ],
                ]
            ],
            [
                'name' => '大额券',
                'list' => [
                    [
                        'name' => '综合',
                        'material_id' => 27446
                    ],
                    [
                        'name' => '女装',
                        'material_id' => 27448
                    ],
                    [
                        'name' => '食品',
                        'material_id' => 27451
                    ],
                    [
                        'name' => '美妆个护',
                        'material_id' => 27453
                    ],
                    [
                        'name' => '家居家装',
                        'material_id' => 27798
                    ],
                    [
                        'name' => '母婴',
                        'material_id' => 27454
                    ]
                ]
            ],
            [
                'name' => '高佣榜',
                'list' => [
                    [
                        'name' => '综合',
                        'material_id' => 13366
                    ],
                    [
                        'name' => '女装',
                        'material_id' => 13367
                    ],
                    [
                        'name' => '家居家装',
                        'material_id' => 13368
                    ],
                    [
                        'name' => '数码家电',
                        'material_id' => 13369
                    ],
                    [
                        'name' => '鞋包配饰',
                        'material_id' => 13370
                    ],
                    [
                        'name' => '美妆个护',
                        'material_id' => 13371
                    ],
                    [
                        'name' => '男装',
                        'material_id' => 13372
                    ],
                    [
                        'name' => '内衣',
                        'material_id' => 13373
                    ],
                    [
                        'name' => '母婴',
                        'material_id' => 13374
                    ],
                    [
                        'name' => '食品',
                        'material_id' => 13375
                    ],
                    [
                        'name' => '运动户外',
                        'material_id' => 13376
                    ]
                ]
            ],
            [
                'name' => '品牌券',
                'list' => [
                    [
                        'name' => '综合',
                        'material_id' => 3786
                    ],
                    [
                        'name' => '女装',
                        'material_id' => 3788
                    ],
                    [
                        'name' => '家居家装',
                        'material_id' => 3792
                    ],
                    [
                        'name' => '数码家电',
                        'material_id' => 3793
                    ],
                    [
                        'name' => '鞋包配饰',
                        'material_id' => 3796
                    ],
                    [
                        'name' => '美妆个护',
                        'material_id' => 3794
                    ],
                    [
                        'name' => '男装',
                        'material_id' => 3790
                    ],
                    [
                        'name' => '内衣',
                        'material_id' => 3787
                    ],
                    [
                        'name' => '母婴',
                        'material_id' => 3789
                    ],
                    [
                        'name' => '食品',
                        'material_id' => 3791
                    ],
                    [
                        'name' => '运动户外',
                        'material_id' => 3795
                    ],
                ]
            ],
            [
                'name' => '猫超优质爆款',
                'list' => [
                    [
                        'name' => '猫超1元购凑单',
                        'material_id' => 27162
                    ],
                    [
                        'name' => '猫超第二件0元',
                        'material_id' => 27161
                    ],
                    [
                        'name' => '猫超单件满减包邮',
                        'material_id' => 27160
                    ],
                ]
            ],
            [
                'name' => '聚划算单品爆款',
                'list' => [
                    [
                        'name' => '开团热卖中',
                        'material_id' => 31371
                    ],
                    [
                        'name' => '预热',
                        'material_id' => 31370
                    ],
                ]
            ],
            [
                'name' => '天天特卖',
                'list' => [
                    [
                        'name' => '开团热卖中',
                        'material_id' => 31362
                    ],
                ]
            ],
            [
                'name' => '母婴主题',
                'list' => [
                    [
                        'name' => '备孕',
                        'material_id' => 4040
                    ],
                    [
                        'name' => '0至6个月',
                        'material_id' => 4041
                    ],
                    [
                        'name' => '4至6岁',
                        'material_id' => 4044
                    ],
                    [
                        'name' => '7至12个月',
                        'material_id' => 4042
                    ],
                    [
                        'name' => '1至3岁',
                        'material_id' => 4043
                    ],
                    [
                        'name' => '7至12岁',
                        'material_id' => 4045
                    ],
                ]
            ],
            [
                'name' => '有好货',
                'list' => [
                    [
                        'name' => '有好货',
                        'material_id' => 4092
                    ],
                ]
            ],
            [
                'name' => '潮流范',
                'list' => [
                    [
                        'name' => '潮流范',
                        'material_id' => 4093
                    ],
                ]
            ],
            [
                'name' => '特惠',
                'list' => [
                    [
                        'name' => '特惠',
                        'material_id' => 4094
                    ],
                ]
            ],
        ];
    }
}
