<?php
declare(strict_types=1);

namespace App\Controller;

use Hyperf\Contract\OnCloseInterface;
use Hyperf\Contract\OnMessageInterface;
use Hyperf\Contract\OnOpenInterface;
use Hyperf\WebSocketServer\Context;
use Swoole\Http\Request;
use Swoole\Server;
use Swoole\Websocket\Frame;
use Swoole\WebSocket\Server as WebSocketServer;

class WebSocketController implements OnMessageInterface, OnOpenInterface, OnCloseInterface
{

//    public function onMessage($server, Frame $frame): void
//    {
//        $server->push($frame->fd, 'Hyperf Recv: ' . $frame->data);
//    }
//
//    public function onOpen($server, Request $request): void
//    {
//        $server->push($request->fd, 'Opened');
//    }
    /**
     * WebSocket 服务的 onOpen, onMessage, onClose 回调并不在同一个协程下触发，因此不能直接使用协程上下文存储状态信息。
     * WebSocket Server 组件提供了 连接级 的上下文，API 与协程上下文完全一样。
     * @param \Swoole\Http\Response|WebSocketServer $server
     * @param Frame $frame
     */
    public function onMessage($server, Frame $frame): void
    {
        $server->push($frame->fd, 'Username: ' . Context::get('username'));
    }

    public function onOpen($server, Request $request): void
    {
        Context::set('username', $request->cookie['username']);
    }

    public function onClose($server, int $fd, int $reactorId): void
    {
        var_dump('closed');
    }
}