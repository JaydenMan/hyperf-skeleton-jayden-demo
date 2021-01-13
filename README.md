```php
<?php
declare(strict_types=1);

namespace App\Controller;

//注解@后面的类都需要use进来(Inject,Controller,AutoController)
//引入 use GetMapping、PostMapping、RequestMapping、PutMapping、PatchMapping、DeleteMapping
use App\Exception\InvalidParamException;
use App\Service\UserService;
use Hyperf\Contract\ConfigInterface;
use Hyperf\Di\Annotation\Inject;
use Hyperf\HttpServer\Annotation\Controller;
use Hyperf\HttpServer\Annotation\DeleteMapping;
use Hyperf\HttpServer\Annotation\GetMapping;
use Hyperf\HttpServer\Annotation\PatchMapping;
use Hyperf\HttpServer\Annotation\PostMapping;
use Hyperf\HttpServer\Annotation\RequestMapping;
use Hyperf\HttpServer\Contract\RequestInterface;
use Hyperf\HttpServer\Contract\ResponseInterface;
use Psr\Container\ContainerInterface;

/**
 * @Controller()
 */
class TestController
{


    /**
     * 通过 `@Inject` 注解注入由 `@var` 注解声明的属性类型对象
     * 当 ContainerInterface 不存在于 DI 容器内或不可创建时，则注入 null
     * Inject 直接注入，不用写构造方法
     * 使用 @Inject 注解时需 use Hyperf\Di\Annotation\Inject; 命名空间；
     *
     * @Inject(required=false)
     * @var ContainerInterface
     */
    private $container;

    // 通过在构造函数的参数上声明参数类型完成自动注入
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * 测试get
     * @GetMapping(path="lists")
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return mixed
     */
    public function lists(RequestInterface $request, ResponseInterface $response)
    {
        $id = $request->input('id');
        return '你发送的参数id=' . $id;
    }

    /**
     * 测试post
     * @PostMapping(path="update")
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function update(RequestInterface $request, ResponseInterface $response)
    {
        $data = $request->post('data');
        return $response->json($data);
    }


    /**
     * 测试重定向
     * @GetMapping(path="get")
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function get(RequestInterface $request, ResponseInterface $response)
    {
        $id = $request->query('id');
        return $response->redirect('/test/lists?id=' . $id);
    }

    /**
     * 测试下载
     * @GetMapping(path="download")
     * @param RequestInterface $request
     * @param ResponseInterface $response
     * @return \Psr\Http\Message\ResponseInterface
     */
    public function download(RequestInterface $request, ResponseInterface $response)
    {
        $file = '/root/Python-3.7.0.tar.xz';
        $name = 'python-src-3.7.tar.xz';
        return $response->download($file, $name);
    }

    //Hyperf 会自动为此方法生成一个 /test/t1 的路由，允许通过 GET 或 POST 方式请求

    /**
     * @RequestMapping(path="t1", methods="get,post")
     */
    public function t1()
    {
        echo 'aaaa' . PHP_EOL;  //输出到服务端控制台
        return 't1';    //输出到访问的客户端
    }

    /**
     * @RequestMapping(path="testContainer", methods="get,post")
     * @return string
     */
    public function testContainer()
    {
        return 'container';
    }

    /**
     * @GetMapping(path="exception")
     */
    public function exception()
    {
        throw new InvalidParamException(1234, '参数错误');
    }

    /**
     * 容器注入
     * @GetMapping(path="show")
     * @return UserService
     */
    public function show()
    {
        $config = $this->container->get(ConfigInterface::class);
        // 我们假设对应的配置的 key 为 cache.enable
        $enableCache = $config->get('cache.enable', false);
        // make(string $name, array $parameters = []) 方法等同于 new ，使用 make() 方法是为了允许 AOP 的介入，而直接 new 会导致 AOP 无法正常介入流程
        return make(UserService::class, compact('enableCache'));
    }


}
```