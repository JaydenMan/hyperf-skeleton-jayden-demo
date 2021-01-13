<?php
declare(strict_types=1);

namespace App\Controller;

//注解@后面的类都需要use进来(Inject,Controller,AutoController)
//引入 use GetMapping、PostMapping、RequestMapping、PutMapping、PatchMapping、DeleteMapping
use App\Exception\InvalidParamException;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Hyperf\Pool\SimplePool\PoolFactory;
use Psr\Container\ContainerInterface;

/**
 * @Controller()
 */
class PoolController
{
    /**
     * 注入ContainerInterface
     * @Inject
     * @var ContainerInterface
     */
    protected $container;

    /**
     *
     * @GetMapping(path="lists")
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function lists(RequestInterface $request, ResponseInterface $response)
    {

        $factory = $this->container->get(PoolFactory::class);

        $pool = $factory->get('mysqlPool', function () {
//            return new Client($host, $port, $ssl);
            return 1;
        }, [
            'max_connections' => 50
        ]);
        //获取连接
        $connection = $pool->get();

        $client = $connection->getConnection(); // 即上述 Client.

        // Do something.
        //释放连接
        $connection->release();
        return '你发送的参数id=' . $id;
    }

}