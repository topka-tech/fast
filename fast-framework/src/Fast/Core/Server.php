<?php


namespace Fast\Core;


use Fast\Core\Server\ServerHandler;
use Fast\Core\Server\ServerFactory;

final class Server
{
    public static $instance;
    private $server;

    public static function instance($config = null){
        
        if(Server::$instance == null){
            // TODO loading configuration
            $port = 9501;
            $swoole_props = array(
                'worker_num' => 6,
                'backlog' => 128,
            );
            
            if($config!=null){
                $port = $config['fast_service_port'];
                $swoole_props = $config['swoole_props'];
            }

            
            // building singleton serve
            $instance = new Server();
            $instance->server = ServerFactory::build($port,$swoole_props);

            return $instance;
        }

        return Server::$instance;
    }

   
    public function stop(){
        if($this->server != null){
            $this->server -> stop();
        }
    }


    public function start(){
        if($this->server !=null){
            $this->server -> start();
        }
    }



    //TODO expose server only for debug !!!!!
    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;
    }
    


}