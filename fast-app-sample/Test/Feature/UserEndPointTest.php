<?php
namespace Tests\Feature;

use PHPUnit\Framework\TestCase;
use Fast\Core\Model\ApiRequest;
use Fast\Core\Client\ClientManager;

/**
 * User: hongyang
 * Date: 2017/6/20
 * Time: 上午9:48
 */
class UserEndPointTest extends TestCase
{
    public function testUserRootApi()
    {
        $req1 = ApiRequest::build()->setServer("127.0.0.1")->setPath("user");
        $resps = ClientManager::build()->executeRequests($req1);
        $this->assertEquals(count($resps),1);
        $resp1 = $resps[$req1->getRequestId()];
        $this->assertEquals($resp1->getRequestId(),$req1->getRequestId());
        $this->assertEquals($resp1->getBody()['name'],"tom");

        return 'first';
    }

    
}