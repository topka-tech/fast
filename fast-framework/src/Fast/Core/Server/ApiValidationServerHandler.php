<?php

namespace Fast\Core\Server;


class ApiValidationServerHandler implements ServerHandler
{

    public function onServerStart(&$serv)
    {
        // TODO: Implement onServerStart() method.
    }

    public function onConnectStart(&$serv, &$fd, &$from_id)
    {
        // TODO: Implement onConnectStart() method.
    }

    public function onDataReceive(&$serv, &$fd, &$from_id, &$reqs, &$resps)
    {
        $fret = true;
        
        return $fret;
    }

    public function onConnectClose(&$serv, &$fd, &$from_id)
    {
        // TODO: Implement onConnectClose() method.
    }
}