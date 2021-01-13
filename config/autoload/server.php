<?php

declare(strict_types=1);

/**
 * This file is part of Hyperf.
 *
 * @link     https://www.hyperf.io
 * @document https://hyperf.wiki
 * @contact  group@hyperf.io
 * @license  https://github.com/hyperf/hyperf/blob/master/LICENSE
 */

/**
 * composer拉取，安装不了 WebSocketServer，去github下载源码，放置verdor/hyperf/下，并在 composer/autoload_static.php, 增加如下两段composer配置，即可使用
 * 'Hyperf\\HttpServer\\' => 18,
 *
 * 'Hyperf\\WebSocketServer\\' =>
 * array (
 * 0 => __DIR__ . '/..' . '/hyperf/websocket-server/src',
 * ),
 */

use Hyperf\Server\Server;
use Hyperf\Server\SwooleEvent;

return [
    'mode' => SWOOLE_PROCESS,
    'servers' => [
        //HTTP服务器
//        [
//            'name' => 'http',
//            'type' => Server::SERVER_HTTP,
//            'host' => '0.0.0.0',
//            'port' => 9501,
//            'sock_type' => SWOOLE_SOCK_TCP,
//            'callbacks' => [
//                SwooleEvent::ON_REQUEST => [Hyperf\HttpServer\Server::class, 'onRequest'],
//            ],
//            'settings' => [
//                //因为 Swoole\WebSocket\Server 继承自 Swoole\Http\Server，可以使用 HTTP 触发所有 WebSocket 的推送，
//                //如需关闭，可以修改 config/autoload/server.php 文件给 http 服务中增加 open_websocket_protocol 配置项。
//                'open_websocket_protocol' => false,
//            ]
//        ],
        //WebSocket服务器
//        [
//            'name' => 'ws',
//            'type' => Server::SERVER_WEBSOCKET,
//            'host' => '0.0.0.0',
//            'port' => 9502,
//            'sock_type' => SWOOLE_SOCK_TCP,
//            'callbacks' => [
//                SwooleEvent::ON_HAND_SHAKE => [Hyperf\WebSocketServer\Server::class, 'onHandShake'],
//                SwooleEvent::ON_MESSAGE => [Hyperf\WebSocketServer\Server::class, 'onMessage'],
//                SwooleEvent::ON_CLOSE => [Hyperf\WebSocketServer\Server::class, 'onClose'],
//            ],
//        ],
    //TCP服务器
//        [
//            'name' => 'tcp',
//            'type' => Server::SERVER_BASE,
//            'host' => '0.0.0.0',
//            'port' => 9504,
//            'sock_type' => SWOOLE_SOCK_TCP,
//            'callbacks' => [
//                SwooleEvent::ON_RECEIVE => [App\Controller\TcpServer::class, 'onReceive'],
//            ],
//            'settings' => [
//                // 按需配置
//            ],
//        ],
    ],
    'settings' => [
        'enable_coroutine' => true,
        'worker_num' => swoole_cpu_num(),
        'pid_file' => BASE_PATH . '/runtime/hyperf.pid',
        'open_tcp_nodelay' => true,
        'max_coroutine' => 100000,
        'open_http2_protocol' => true,
        'max_request' => 100000,
        'socket_buffer_size' => 2 * 1024 * 1024,
        'buffer_output_size' => 2 * 1024 * 1024,
        'daemonize' => 1,//守护进程运行
    ],
    'callbacks' => [
        SwooleEvent::ON_WORKER_START => [Hyperf\Framework\Bootstrap\WorkerStartCallback::class, 'onWorkerStart'],
        SwooleEvent::ON_PIPE_MESSAGE => [Hyperf\Framework\Bootstrap\PipeMessageCallback::class, 'onPipeMessage'],
        SwooleEvent::ON_WORKER_EXIT => [Hyperf\Framework\Bootstrap\WorkerExitCallback::class, 'onWorkerExit'],
    ],
];
