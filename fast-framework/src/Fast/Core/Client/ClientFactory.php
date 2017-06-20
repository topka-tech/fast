<?php

namespace Fast\Core\Client;


class ClientFactory
{
    //TODO 根据ip/port 生成连接池

    public static function build($ip, $port = 9501)
    {
        //TODO connectionManager管理所有链接，未来可以有连接池等策略。每次需要连接，到connectionManager拿一个出来

        $conn = new Connection($ip,$port);
        $client = new ApiClient($conn);
        return $client;

    }

}