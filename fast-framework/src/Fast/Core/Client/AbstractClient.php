<?php

namespace Fast\Core\Client;

use Fast\Core\Model\ApiResponse;
use Mockery\CountValidator\Exception;

abstract class AbstractClient
{
    public $connection;
    public function __construct($conn)
    {
        if($conn == null){
            throw new \Exception("Conn can not be null!");
        }
        
        if(!($conn instanceof Connection)){
            throw new \Exception("Conn type error!");
        }

        $this->connection = $conn;
    }
    
    
    public function send($data, $async = false, $callback = null){

        $ret = $this->connection->send($data, $async, $callback);
        
        $resps = array();
        if($async==false){
            $ret = json_decode($ret,true);
            if($ret == null){
                throw new \Exception("error return format");
            }
            
            foreach ($ret as $req_id => $resp){
                // TODO 检查APIRequest的比要字段
                
                $apiResponse = ApiResponse::jsonBuild($resp);
                $apiResponse->setReceivedTime(time());
                $resps[$req_id] = ApiResponse::jsonBuild($apiResponse);
            }
            
        }
        return $resps;
    }


    
}