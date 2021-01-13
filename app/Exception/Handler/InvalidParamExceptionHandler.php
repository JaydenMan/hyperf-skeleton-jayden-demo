<?php

declare(strict_types=1);

namespace App\Exception\Handler;

use App\Exception\InvalidParamException;
use Hyperf\Contract\StdoutLoggerInterface;
use Hyperf\ExceptionHandler\ExceptionHandler;
use Hyperf\HttpMessage\Stream\SwooleStream;
use Psr\Http\Message\ResponseInterface;
use Throwable;

/**
 * 定义异常处理器
 * 我们可以在任意位置定义一个 类(Class) 并继承抽象类  Hyperf\ExceptionHandler\ExceptionHandler 并实现其中的抽象方法，
 * 通过配置文件注册异常处理器(config/autoload/exceptions.php)
 * Class InvalidParamExceptionHandler
 * @package App\Exception\Handler
 */
class InvalidParamExceptionHandler extends ExceptionHandler
{

    public function handle(Throwable $throwable, ResponseInterface $response)
    {
        // 判断被捕获到的异常是希望被捕获的异常
        if ($throwable instanceof InvalidParamException) {
            // 格式化输出
            $data = json_encode([
                'code' => $throwable->getCode(),
                'message' => $throwable->getMessage(),
            ], JSON_UNESCAPED_UNICODE);

            // 阻止异常冒泡
            $this->stopPropagation();
            return $response->withStatus(500)->withBody(new SwooleStream($data));
        }

        // 交给下一个异常处理器
        return $response;

        // 或者不做处理直接屏蔽异常
    }

    public function isValid(Throwable $throwable): bool
    {
        return true;
    }
}
