<?php


use Fast\Core\Model\ApiRequest;
use Fast\Core\Client\ClientManager;

require_once __DIR__.'/../vendor/autoload.php';


$req1 = ApiRequest::build()->setServer("127.0.0.1")->setPath("user");
$req2 = new ApiRequest("user/QueryAdmin","127.0.0.1");
$req3 = new ApiRequest("foobar","127.0.0.1");
$req3 = new ApiRequest("endpointsList","127.0.0.1");

$reqs = [$req1, $req2, $req3];


$reps = ClientManager::build()->executeRequests($reqs);

echo json_encode($reps);

exit();