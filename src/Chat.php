<?php

namespace lotfi;



use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;

class Chat implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }
    public function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        require dirname(__DIR__) . '/db.php';
        $msgArray = json_decode($msg, true);
        if (!strlen($msgArray['message']) == 0) {
            $stmt = $mysqli->prepare('INSERT INTO chat (username,msg) VALUES (?,?);');
            $stmt->bind_param('ss', $msgArray['username'], $msgArray['message']);
            $stmt->execute();
            $stmt->close();
            $mysqli->close();
            foreach ($this->clients as $client) {
                if ($from !== $client) {
                    $client->send($msg);
                }
            }
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
    }
}
