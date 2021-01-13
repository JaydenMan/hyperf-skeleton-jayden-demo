<?php

declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\OnReceiveInterface;

class TcpServer implements OnReceiveInterface
{
    public function onReceive($server, int $fd, int $fromId, string $data): void
    {
        echo 'TCP request data:', $data;
        $server->send($fd, 'hyperf has reveived data:' . $data);
    }
}