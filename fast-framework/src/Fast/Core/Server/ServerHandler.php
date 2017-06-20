<?php


namespace Fast\Core\Server;


interface ServerHandler
{

    public function onServerStart(&$serv);

    public function onConnectStart(&$serv, &$fd, &$from_id);

    public function onDataReceive(&$serv, &$fd, &$from_id, &$reqs, &$resps);

    public function onConnectClose(&$serv, &$fd, &$from_id);

}