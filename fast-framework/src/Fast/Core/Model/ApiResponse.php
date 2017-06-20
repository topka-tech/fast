<?php

namespace Fast\Core\Model;


class ApiResponse implements \JsonSerializable
{

    private $req_id;
    private $status_code;
    private $received_time;
    private $server_received_time;
    private $server_response_time;
    private $body;
    private $body_md5;
    private $length;

    private $path;
    private $server;
    private $port;

    public static function build(){
        return new static();
    }

    /**
     * ApiResponse constructor.
     * @param $path
     * @param $server
     * @param $port
     * @param $req_id
     */
    public function __construct($req_id = null, $path = null, $server = null, $port=9501)
    {
        $this->path = $path;
        $this->server = $server;
        $this->port = $port;
        $this->req_id = $req_id;
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
     * @return int
     */
    public function getPort()
    {
        return $this->port;
    }

    /**
     * @param int $port
     */
    public function setPort($port)
    {
        $this->port = $port;
        return $this;

    }


    /**
     * @return mixed
     */
    public function getRequestId()
    {
        return $this->req_id;
    }

    /**
     * @param mixed $req_id
     */
    public function setReqId($req_id)
    {
        $this->req_id = $req_id;
        return $this;

    }

    /**
     * @return mixed
     */
    public function getStatusCode()
    {
        return $this->status_code;
    }

    /**
     * @param mixed $status_code
     */
    public function setStatusCode($status_code)
    {
        $this->status_code = $status_code;
    }

    /**
     * @return mixed
     */
    public function getReceivedTime()
    {
        return $this->received_time;
    }

    /**
     * @param mixed $received_time
     */
    public function setReceivedTime($received_time)
    {
        $this->received_time = $received_time;
    }

    /**
     * @return mixed
     */
    public function getServerReceivedTime()
    {
        return $this->server_received_time;
    }

    /**
     * @param mixed $server_received_time
     */
    public function setServerReceivedTime($server_received_time)
    {
        $this->server_received_time = $server_received_time;
    }

    /**
     * @return mixed
     */
    public function getServerResponseTime()
    {
        return $this->server_response_time;
    }

    /**
     * @param mixed $server_response_time
     */
    public function setServerResponseTime($server_response_time)
    {
        $this->server_response_time = $server_response_time;
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
        $this->body_md5 = md5(json_encode($body));
    }

    /**
     * @return mixed
     */
    public function getBodyMd5()
    {
        return $this->body_md5;
    }

    /**
     * @param mixed $body_md5
     */
    public function setBodyMd5($body_md5)
    {
        $this->body_md5 = $body_md5;
    }

    /**
     * @return mixed
     */
    public function getLength()
    {
        return $this->length;
    }

    /**
     * @param mixed $length
     */
    public function setLength($length)
    {
        $this->length = $length;
    }

    function jsonSerialize()
    {
        $vars = get_object_vars($this);
        return $vars;
    }

    public function set($data) {
        foreach ($data AS $key => $value) $this->{$key} = $value;
    }
    public static function jsonBuild($json){
        $ret = new ApiResponse();
        $ret->set($json);
        return $ret;

    }

}