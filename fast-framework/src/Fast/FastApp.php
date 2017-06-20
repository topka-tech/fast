<?php

namespace Fast;


abstract class FastApp
{
    protected $endPoints;

    protected $server;

    protected $config;

    public static $instance;

    public static function instance($config = null){

        if(FastApp::$instance == null){
            $instance = new static($config);
            return $instance;
        }

        return FastApp::$instance;
    }


    
    protected function __construct($config)
    {
        $this->server = \Fast\Core\Server::instance($config);
        $this->registerEndPoints();

    }

    protected function config($config){
        $this->config = $config;
    }


    public static function getConfig($key){
        if(array_key_exists($key,FastApp::instance()->config)){
            return FastApp::instance()->config['key'];
        }
        return null;
    }

    public function start(){
        if($this->endPoints == null || count($this->endPoints) == 0){
            throw new \Exception("Error: endpoints expects to be non-empty");
        }
        $this->server->start();
    }

    protected function registerEndPoints(){
        foreach ($this->endPoints as $endPoint){
            $this->server->getServer()->registerEndPoint($endPoint);
        }
    }


    public function setPort($port){
        if($port!=null)
        {
            if(!is_numeric($port)){
                throw new \Exception("--port must be numberic");
            }
            $this->config['fast_service_port'] = $port;
        }
    }
    public function shutdown()
    {
        $this->server-> stop();
    }

    public function foo(){
        echo "bar";
    }
}