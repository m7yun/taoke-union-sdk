<?php


namespace TaobaoUnionSdk\Tools;


use TaobaoUnionSdk\TbkFatory;
use TaobaoUnionSdk\Tools\Http;

class GateWay
{

    /**
     * @link https://open.taobao.com/api.htm?docId=24515&docType=2
     * @var string 淘宝联盟官网URL
     */
    protected $unionUrl = 'https://eco.taobao.com/router/rest';

    /**
     * 配置
     * @var array
     */
    protected $globalConfig = [
        'appkey' => '',
        'secretKey' => '',
        'format' => 'json',
        'session' => '',//授权接口（sc类的接口）需要带上
        'signMethod' => 'md5',
        'apiVersion' => '2.0',
        'sandbox' => false,
    ];

    /**
     * @var TbkFatory
     */
    protected $tbkFatory;

    public function __construct($config, TbkFatory $fatory)
    {
        $this->globalConfig = array_merge($this->globalConfig, $config);
        $this->tbkFatory = $fatory;
    }

    protected function send($method, array $params)
    {
        //组装系统参数
        $sysParams["app_key"] = $this->globalConfig['appkey'];
        $sysParams["v"] = $this->globalConfig['apiVersion'];
        $sysParams["format"] = $this->globalConfig['format'];
        $sysParams["sign_method"] = $this->globalConfig['signMethod'];
        $sysParams["method"] = $method;
        $sysParams["session"] = $this->globalConfig['session'];
        $sysParams["timestamp"] = \date("Y-m-d H:i:s");
        $sysParams["sign"] = $this->generateSign(array_merge($params, $sysParams), $this->globalConfig['secretKey']);
        $requestUrl = $this->unionUrl . '?' . http_build_query($sysParams);
		$resp = Http::sendRequest($requestUrl, $params);
		if($resp['ret']) {
			$info = json_decode($resp['msg'], true);
			if ($this->globalConfig['sandbox']) {
			    var_dump($info);
			}
			if (isset($info['error_response'])) {
			    $code = isset($info['error_response']['sub_code']) ? $info['error_response']['sub_code'] : $info['error_response']['code'];
			    $msg = isset($info['error_response']['sub_msg']) ? $info['error_response']['sub_msg'] : $info['error_response']['msg'];
			    $this->tbkFatory->setError($code . ' ' . $msg);
			    return false;
			}
			return \current($info);
		} else {
			$this->tbkFatory->setError($resp['msg']);
			return false;
		}
    }

    private function generateSign(array $attributes, $secretKey)
    {
        ksort($attributes);
        $stringToBeSigned = $secretKey;
        foreach ($attributes as $k => $v) {
            if (!is_array($v) && "@" != substr($v, 0, 1)) {
                $stringToBeSigned .= "$k$v";
            }
        }
        unset($k, $v);
        $stringToBeSigned .= $secretKey;
        return strtoupper(md5($stringToBeSigned));
    }
}