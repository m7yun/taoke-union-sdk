<?php


namespace TaobaoUnionSdk\Api;


use TaobaoUnionSdk\Tools\GateWay;

class Coupon extends GateWay
{
    /**
     * taobao.tbk.coupon.get( 阿里妈妈推广券信息查询 )
     * @link https://open.taobao.com/api.htm?docId=31106&docType=2
     * @param array $params
     * @return mixed
     */
    public function get(array $params)
    {
        $result = $this->send('taobao.tbk.coupon.get', $params);

        return \current($result);
    }

    /**
     * taobao.tbk.coupon.convert( 淘宝客-推广者-单品券高效转链 )
     * @link https://open.taobao.com/api.htm?docId=29289&docType=2&scopeId=12486
     * @param array $params
     * @return mixed
     */
    public function convert(array $params)
    {
        $result = $this->send('taobao.tbk.coupon.convert', $params);

        return \current($result);
    }
}
