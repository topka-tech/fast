<?php

namespace Fast\Core\Model;


class ApiRequest  implements \JsonSerializable
{
    private $req_time;
    private $req_id;

    private $path;
    private $server;
    private $port = 9501;
    private $body;


    public static function build(){
        return new static();
    }

    public function __construct()
    {
        $this->req_id = uuid();
    }

    /**
     * @return mixed
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * @param mixed $path
     */
    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    /**
     * @return mixed
     */
    public function getServer()
    {
        return $this->server;

    }

    /**
     * @param mixed $server
     */
    public function setServer($server)
    {
        $this->server = $server;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param mixed $port
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getBody()
    {
        return $this->body;
    }

    /**
     * @param mixed $body
     */
    public function setBody($body)
    {
        $this->body = $body;
        return $this;

    }




     /**
     * @return mixed
     */
    public function getRequestTime()
    {
        return $this->req_time;
    }


    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->req_id;
    }


    /**
     * Specify data which should be serialized to JSON
     * @link http://php.net/manual/en/jsonserializable.jsonserialize.php
     * @return mixed data which can be serialized by <b>json_encode</b>,
     * which is a value of any type other than a resource.
     * @since 5.4.0
     */
    function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }

    public function set($data) {
        foreach ($data AS $key => $value) $this->{$key} = $value;
    }

    public static function jsonBuild($json){
        $ret = new ApiRequest();
        $ret->set($json);
        return $ret;

    }

    /**
     * @param mixed $req_time
     */
    public function setReqTime($req_time)
    {
        $this->req_time = $req_time;
    }
    
    
    public function getRootPath(){
        $rootPath = $this->path;
        if(strpos($this->path, "/")>0){
            $path_arr = explode("/",$this->path);
            $rootPath = $path_arr[0];
        }

        return $rootPath;
    }
    
    
    public function getSubPath(){
        $subPath = false;
        if(strpos($this->path, "/")>0){
            $path_arr = explode("/",$this->path);
            $subPath = $path_arr[1];
        }

        return $subPath;
    }

}