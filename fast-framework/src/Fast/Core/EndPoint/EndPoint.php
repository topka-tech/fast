<?php
/**
 * 服务抽象类
 */

namespace Fast\Core\EndPoint;


abstract class EndPoint
{
    protected $path = false;

    public function __construct()
    {
        if($this->path == false){
            //TODO TopicListEndPoint to topic_list
            $this->path = get_class($this);
        }

    }

    public function getPath()
    {
        return $this->path;
    }
    public abstract function fire($data);
    public abstract function onCreate($context);
    public abstract function onDestory($context);

    public function allPath(){
        $methods = [];
        $reflection = new \ReflectionClass($this);

        foreach ($reflection->getMethods(\ReflectionMethod::IS_PUBLIC) as $method){
            if (startsWith($method->name,"do")){
                $methods[] = "/".substr($method->name,2);
            }else if($method->name == "fire"){
                $methods[] = "/";
            }
        }
        return $methods;


    }

}