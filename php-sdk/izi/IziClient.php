<?php

require_once 'IziHttpClient.php';
require_once 'IziUtil.php';

/**
 * Izi Client
 */
class IziClient {

    /**
     * accessKey
     * @var string
     */
    protected $accessKey = '';
    
    /**
     * secretKey
     * @var string
     */
    protected $secretKey = '';

    /**
     * @param string $accessKey
     * @param string $secretKey
     */
    public function __construct($accessKey, $secretKey){
        $this->accessKey = trim($accessKey);
        $this->secretKey = trim($secretKey);
        $this->client = new IziHttpClient();
        $this->version = '1_0_0';
        $this->proxies = array();
    }

    /**
     * 查看版本
     * @return string
     * 
     */
    public function getVersion(){
        return $this->version;
    }    

    /**
     * 连接超时
     * @param int $ms 毫秒
     */
    public function setConnectionTimeoutInMillis($ms){
        $this->client->setConnectionTimeoutInMillis($ms);
    }

    /**
     * 响应超时
     * @param int $ms 毫秒
     */
    public function setSocketTimeoutInMillis($ms){
        $this->client->setSocketTimeoutInMillis($ms);
    }

    /**
     * 代理
     * @param array $proxy
     * @return string
     * 
     */
    public function setProxies($proxies){
        $this->client->setConf($proxies);
    } 

    /**
     * Api 请求
     * @param  string $url
     * @param  mixed $data
     * @return mixed
     */
    public function request($url, $data, $headers=array()){
        $params = array();

        $headers = $this->getAuthHeaders('POST', $url, $params, $headers);
        $response = $this->client->post($url, $data, $params, $headers);
        return $response['content'];
    }

    /**
     * 格式化结果
     * @param $content string
     * @return mixed
     */
    protected function proccessResult($content){
        return json_decode($content, true);
    }

    /**
     * @param  string $method HTTP method
     * @param  string $url
     * @param  array $param 参数
     * @return array
     */
    private function getAuthHeaders($method, $url, $params=array(), $headers=array()){

        $obj = parse_url($url);
        if(!empty($obj['query'])){        
            foreach(explode('&', $obj['query']) as $kv){
                if(!empty($kv)){
                    list($k, $v) = explode('=', $kv, 2);
                    $params[$k] = $v;
                }
            }
        }

        //UTC 时间戳
        $timestamp = gmdate('Y-m-d\TH:i:s\Z');
        //$headers['Host'] = isset($obj['port']) ? sprintf('%s:%s', $obj['host'], $obj['port']) : $obj['host'];
        $headers['credit-date'] = $timestamp;

        //签名
        $headers['authorization'] = IziSampleSigner::sign(array(
            'ak' => $this->accessKey,
            'sk' => $this->secretKey,
        ), $method, $obj['path'], $headers, $params, array(
            'timestamp' => $timestamp,
            'headersToSign' => array_keys($headers),
        ));

        return $headers;
    }

}
