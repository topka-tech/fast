<?php

namespace Fast\Core\Server;


class ApiServer extends AbstractServer  implements ServerHandler
{
    private $end_points;

    public function __construct($port = 9501, $props = null) {

        if($props==null){
            $props = array(
                'worker_num' => 8,
            );

        }
        parent::__construct($port,$props);

        $this->addHandler(new ApiValidationServerHandler());
        $this->addHandler($this);
    }

    public function registerEndPoint($endpoint){

        $r = new \ReflectionClass($endpoint);

        if ($r->isSubclassOf('Fast\\Core\\EndPoint\\EndPoint') &&
            !$r->isAbstract() &&
            !$r->getConstructor()->getNumberOfRequiredParameters()) {
            $clazz = $r->newInstance();
            $key = $clazz->getPath();
            $this->end_points[$key] = $clazz;
            return ;
        }

        throw new \Exception("Error registerEndPoint");
    }




    public function onServerStart(&$serv)
    {
        debug("Server onStart on: ". $serv->port);
    }

    public function onConnectStart(&$serv, &$fd, &$from_id)
    {
        debug("Connection [$fd] connected!");
    }

    public function onDataReceive(&$serv, &$fd, &$from_id, &$data, &$resps)
    {

        $ret = true;
        foreach ($data as $req_id => $req){
            $resp = $resps[$req_id];
            $code = 200;
            debug("Incoming request $fd ".$req->getPath());

            if($req->getPath() == "endpointsList"){
                // TODO ugly !!
                $end_points_list_detail = array();
                foreach ($this->end_points as $key => $item){
                    $detail = $item->allPath();
                    array_push($end_points_list_detail,$detail);
                }
                $resp->setBody($end_points_list_detail);
            }else if(array_key_exists($req->getRootPath(), $this->end_points )){
                $endPoint = $this->end_points[$req->getRootPath()];
                if($endPoint!=null){
                    if($req->getSubPath()){
                        $method = "do".$req->getSubPath();
                        if(method_exists($endPoint,$method)){
                            $resp->setBody(call_user_func(array($endPoint, $method), $req->getBody()));
                        }else{
                            $code = 404;
                        }
                    }else{
                        $resp->setBody($endPoint->fire($req->getBody()));
                    }
                }
            }else{
               $code = 404;
            }
            $resps[$req_id] = $resp;
            $resp->setServerResponseTime(time());
            $resp->setStatusCode($code);
            debug("Process request  $fd ".$req->getPath(). " done. response code: $code");

        }

        return $ret;

    }

    public function onConnectClose(&$serv, &$fd, &$from_id)
    {
        debug("Connection [$fd] closed");
    }



}