<?php

namespace App\Listeners;

use Ratchet\ConnectionInterface;
use Ratchet\RFC6455\Messaging\MessageInterface;
use Ratchet\WebSocket\MessageComponentInterface;

class TestListener implements MessageComponentInterface
{


    function onOpen(ConnectionInterface $conn)
    {
        // TODO: Implement onOpen() method.

    }

    function onClose(ConnectionInterface $conn)
    {

        // TODO: Implement onClose() method.
    }

    function onError(ConnectionInterface $conn, \Exception $e)
    {
        // TODO: Implement onError() method.
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        // TODO: Implement onMessage() method.
    }
}
