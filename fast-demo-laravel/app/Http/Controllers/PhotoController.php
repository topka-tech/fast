<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Fast\Core\Model\ApiRequest;
use Fast\Core\Client\ClientManager;



class PhotoController extends Controller
{
    //
    public function index()
    {
        $ret = array();
        $ret['a'] = 1;

        $req1 = ApiRequest::build()->setServer("127.0.0.1")
            ->setPath("user")
            ->setBody(["k1" => "v1","k2" => "v2"]);
        $req2 = ApiRequest::build()->setServer("127.0.0.1")
             ->setPath("user/QueryAdmin");
        $req3 = ApiRequest::build()->setServer("127.0.0.1")
            ->setPath("foobar");
        $req4 = ApiRequest::build()->setServer("127.0.0.1")
            ->setPath("endpointsList");
        $reps = ClientManager::build()->executeRequests([$req1,$req2,$req3,$req4]);



        return $reps;
    }

}
