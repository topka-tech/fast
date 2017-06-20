<?php

namespace Fast\Core\Client;


use Fast\Core\Model\ApiRequest;
use Mockery\CountValidator\Exception;

class ClientManager
{
    private $req_map = array();
    private $token;
    
    public function __construct()
    {
        
    }

    public static function build()
    {
        $ret = new ClientManager();
        return $ret;
    }
    
    // only a example for build pattern
    public function setToken($token)
    {
        $this->token = $token;
        return $this;
    }
    
    public function executeRequests($requests, $async = false, $callback = null)
    {
        $ret = array();
        if(!is_array($requests) && !($requests instanceof ApiRequest)){
            throw new \Exception("requests type error");
        }

        if(is_array($requests) && $async == true && $callback!=null){
            throw new \Exception("async mode with callback is not supported in multiple requests");
        }
        
        if(!is_array($requests)){
            $requests = array($requests);
        }

        foreach ($requests as $request){
            $request->setReqTime(time());
            $key = $request->getServer(). ":" . $request->getPort();
            if(!key_exists($key, $this->req_map)){
                $this->req_map[$key] = array($request);
            }else{
                $this->req_map[$key] = array_merge($requests,$this->req_map[$key]);
            }
        }


        foreach ($this->req_map as $sp => $reqs){
            // batch execute request;
            $sparr = explode(":",$sp);
            $server = $sparr[0];
            $port = $sparr[1];
            $client = ClientFactory::build($server,$port);
            $sreps = $client->send($reqs, $async, $callback);
            $ret = array_merge($ret,$sreps);
        }

        return $ret;
    }

}