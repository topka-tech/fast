<?php

namespace Fast\Core\Server;


class ServerFactory
{
    public static function build($port = 9501,$swoole_props = null)
    {
       // Only one server impl temporarily, more in the future
        $apiServer = new ApiServer($port,$swoole_props);
        return $apiServer;
    }

}