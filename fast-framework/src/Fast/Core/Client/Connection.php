<?php
namespace Fast\Core\Client;

class Connection
{
    public $socket_conn;
    private $ip;
    private $port;

    public function __construct($ip, $port = 9501)
    {
        $this->socket_conn = new \swoole_client(SWOOLE_SOCK_TCP | SWOOLE_KEEP);
        $this->ip = $ip;
        $this->port = $port;
        $this->callback_map = array();
    }

    public function connect()
    {
        try{
            $this->socket_conn->connect($this->ip, $this->port);
        } catch (\Exception $e){
            throw new \Fast\Core\FastException("Connect to server".$this->ip.":".$this->port." failed");
        }
    }
    
    public function __destruct()
    {
        if($this->isConnected()){
            $this->close();
        }
    }
    
    public function isConnected(){
        return $this->socket_conn->isConnected();
    }

    public function close()
    {
        $this->socket_conn->close();
    }

    public function send($data, $async = false, $callback=null){
        $ret = false;
        
        if($async==false){
            if(!$this->socket_conn->isConnected()){
                $this->connect();
            }
            try{
                $this->socket_conn->send(json_encode($data));
                $ret = $this->socket_conn->recv();
            } catch (\Exception $e){
                throw new \Fast\Core\FastException("Send data to server".$this->ip.":".$this->port." failed");
            }
            
        }else{
            $this->socket_conn = new \swoole_client(SWOOLE_SOCK_TCP, SWOOLE_SOCK_ASYNC);
            $this->socket_conn->on("connect", function(\swoole_client $cli) use($data) {
                $cli->send(json_encode($data));

            });
            $this->socket_conn->on("receive", function(\swoole_client $cli, $data) use($callback){
                if($callback!=null && is_callable($callback)){
                    $callback($cli,$data);
                }
                $cli->close();
            });
            $this->socket_conn->on("error", function(\swoole_client $cli){
                echo "error\n";
            });
            $this->socket_conn->on("close", function(\swoole_client $cli){
                echo "Connection close\n";
            });
            $this->connect();
        }

        return $ret;
    }

}