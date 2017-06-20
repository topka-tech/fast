<?php

namespace App\EndPoints;
use Fast\Core\EndPoint\EndPoint;

class UserEndPoint extends EndPoint
{

    protected $path = "user";

    public function fire($data)
    {

        $ret = array();
        $ret['name'] = 'tom';
        $ret['age'] = 24;
        $ret["echo"] = $data;
        return $ret;

    }
    
    public function doQueryAdmin(){
        $ret = array();
        $ret['name'] = 'admin';
        $ret['age'] = 24;
        return $ret;
    }
    
    public function onCreate($context)
    {
        // TODO: Implement onCreate() method.
    }

    public function onDestory($context)
    {
        // TODO: Implement onDestory() method.
    }


}