<?php


namespace TaobaoUnionSdk\Api;


use TaobaoUnionSdk\Tools\GateWay;

class Activity extends GateWay
{
    /**
     * taobao.tbk.activity.info.get( 淘宝客-推广者-官方活动转链 )
     * @link https://open.taobao.com/api.htm?docId=48340&docType=2
     * @param array $params
     * @return array|bool|mixed
     */
    public function get(array $params)
    {
        if (!isset($params['adzone_id'])) {
            $adzoneIds = explode('_', $this->globalConfig['pid']);
            $params['adzone_id'] = $adzoneIds[3];
        }
        $result = $this->send('taobao.tbk.activity.info.get', $params);
        return $result ? \current($result) : $result;
    }
}
