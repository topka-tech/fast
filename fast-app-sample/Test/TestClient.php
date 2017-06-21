<?php


use Fast\Core\Model\ApiRequest;
use Fast\Core\Client\ClientManager;

require_once __DIR__.'/../vendor/autoload.php';


$req1 = ApiRequest::build()->setServer("127.0.0.1")->setPath("user");
$req2 = ApiRequest::build()->setServer("127.0.0.1")->setPath("foobar");
$req3 = ApiRequest::build()->setServer("127.0.0.1")->setPath("user/QueryAdmin");
$req4 = ApiRequest::build()->setServer("127.0.0.1")->setPath("endpointsList");

$reqs = [$req1, $req2, $req3,$req4];

$reps = ClientManager::build()->executeRequests($reqs);

echo var_dump($reps);

exit();