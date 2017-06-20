<?php
namespace Fast\Core\Server;


use Fast\Core\Model\ApiRequest;
use Fast\Core\Model\ApiResponse;

abstract class AbstractServer
{
    protected $serv;
    protected $handlers = array();

    public function __construct($port = 9501, $props = false) {
        
        $this->serv = $serv = new \swoole_server("0.0.0.0", $port, SWOOLE_BASE, SWOOLE_SOCK_TCP);
        if($props == false){
            $props = array(
                'worker_num' => 8,
                'daemonize' => false,
            );
            
        }
        $this->serv->on('Start', array($this, 'onStart'));
        $this->serv->on('Connect', array($this, 'onConnect'));
        $this->serv->on('Receive', array($this, 'onReceive'));
        $this->serv->on('Close', array($this, 'onClose'));
        $this->serv->set($props);
        debug($this->serv->setting['worker_num']);


    }

    public function addHandler(ServerHandler $hand){
        if($hand == null){
           throw new \Exception("Handler must not be null");
        }

        if(!($hand instanceof ServerHandler)){
            throw new \Exception("Handler must be instance of Handler");
        }

        array_push($this->handlers,$hand);

    }

    public function onStart(&$serv)
    {
        foreach ($this->handlers as $handler){
            $handler->onServerStart($serv);
        }
    }

    public function onConnect(&$serv, &$fd, &$from_id)
    {
        foreach ($this->handlers as $handler){
            $handler->onConnectStart($serv, $fd, $from_id);
        }

    }



    public function onReceive(&$serv, &$fd, &$from_id, &$data)
    {
        $reqs = array();
        $resps = array();

        if(startsWith($data,"{" )){
            $data = json_decode($data, true);
            $data = [$data];
        }else{
            $data = json_decode($data, true);
        }

        if($data === null || empty($data)) {
            throw new \Exception("not json format");
        }

        foreach ($data as $item) {
            $apiRequest = ApiRequest::jsonBuild($item);

            $apiResponse = new ApiResponse($apiRequest->getRequestId(),
                $apiRequest->getPath(),
                $apiRequest->getServer(),
                $port=$apiRequest->getPort());
            $apiResponse->setServerReceivedTime(time());
            $resps[$item["req_id"]] = $apiResponse;
            $reqs[$item["req_id"]] = $apiRequest;
        }

        foreach ($this->handlers as $handler){
            $ret = $handler->onDataReceive($serv, $fd, $from_id, $reqs, $resps);
            if($ret == false){
                break;
            }
        }
        debug(json_encode($resps));

        $serv->send($fd, json_encode($resps));
    }

    public function onClose(&$serv, &$fd, &$from_id)
    {
        foreach ($this->handlers as $handler){
            $handler->onConnectClose($serv, $fd, $from_id);
        }
    }


    public function start(){
        $this->serv->start();
    }

    public function stop(){
        $this->serv->stop();
    }
}