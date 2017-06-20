# fast framework

php构建微服务，一般都会采用构建http服务的方式——“每个微服务都是一个http服务，微服务之间采用http协议相互调用”。这样的方式，性能是个很大的痛点。这主要体现在：

* 微服务间的每次请求是一个完整的http过程，必须经过”创建连接、通信、结束连接“。与浏览器请求服务器的场景不同，由于微服务的提供者和调用者都是特定的服务器，所以在微服务的场景中，创建连接和结束连接，我们认为并不是必须的。
* 请求体过大：http协议所传输的数据，必须包括“请求行、请求头、请求体“三个部分，在微服务的场景中，这其中的有些内容，我们认为也不是必须的。

fast基于swoole，构建了一套灵巧、轻便、性能极高的微服务框架。


## 功能与特性

fast是一个PHP的微服务框架，提供了服务发布、服务间调用的功能。使用fast，你可以迅速构建微多个服务应用，并实现相互调用。

fast职责单一，仅在微服务的应用层面，为服务构建者提供了服务间的通信功能，至于“服务发现、高可用、负载均衡”，我们认为应该由平台的基础设施来支撑实现。例如在汽车达人（topka.cn），我们基于容器技术，利用kubernetes构建了一套完整的微服务支撑平台，服务发现等功能都是由这些基础设施来实现的。

fast追求高性能，我们摒弃了http协议以及nginx php-fpm，使用tcp长连接的方式来保证服务间的通信。服务之间的每次请求，不必每次经过”创建连接、通信、结束连接“的过程，绝大多数情况下，只需执行”通信“的步骤。在连接策略上，通过连接复用等方式，尽可能优化，以提高性能。

fast对业务层非常友好，你可以很方便的开发面向其他服务的接口，也可以很方便的集成第三方库，尤其是不依赖web的第三方库，让你以自己的习惯进行开发。

fast不依赖于任何web框架，但却可以作为客户端集成到绝大多数web框架中，这使你可以很方便的将http请求转向后端fast服务。

## 代码库结构

目录 | 内容
------- | -------
fast-framework | fast 框架代码
fast-app-sample | 利用fast框架开发的业务微服务
fast-demo-laravel | 基于laravel的调用fast业务微服务的例子

由于fast-framework尚未正式发布，故fast-app-sample中的composer.json 以“非正规”的方式引入fast-framework。未来fast-framework将发布到https://packagist.org/，更加便于业务App使用

在真实的场景中，fast-framework会以composer库的形式被另外两个项目依赖。

* fast-demo-laravel 可以认为是整个后端的http路由层，前端http请求通过此层转发到fast-app-sample
* fast-app-sample 则是一个微服务


## 快速开始

打开一个终端窗口，启动服务

```
$ php fast-app-sample/fast.php --port=9501
```

> port为微服务发布时监听的接口，默认为9501

打开另一个终端窗口，启动http serve，或者使用nginx php-fpm的方式部署laravel项目

```
$ php fast-demo-laravel/artisan serve
```

打开浏览器，访问 http://localhost:8000/photo ，如果一切正常，将看到示例微服务的返回值

```json

{"986cea01-4980-e996-e838-9b2929a97bcb":{"req_id":"986cea01-4980-e996-e838-9b2929a97bcb","status_code":200,"received_time":1497926743,"server_received_time":1497926743,"server_response_time":1497926743,"body":{"name":"tom","age":24,"echo":{"k1":"v1","k2":"v2"}},"body_md5":"8f1f35b12a5cc443c1d0ae5113710660","length":null,"path":"user","server":"127.0.0.1","port":9501},"3fcf208d-6d6d-c5d0-4691-08d963a291e7"  
......

```

## 使用指南

fast向使用者屏蔽一切关于服务发布、服务间通信的技术细节，使用者只需调用fast提供的高级接口，实现业务逻辑。

### 关键概念

* EndPoint: 对外开放的微服务接口
* ApiRequest：调用微服务的请求封装类
* ApiResponse：调用微服务的返回值封装类

### 开发业务微服务

开发业务微服务，主要包括 ***建立fast工程*** 和 ***开发业务接口*** 两个部分，下面分别进行阐述。

#### 建立fast工程

建立fast工程，可以参考fast-app-sample。目前fast工程需要手工创建，或以fast-app-sample作为模版工程修改。未来将我们提供命令行工具自动创建工程骨架。


fast-app-sample的文件结构树如下：

```
├── App
│   ├── App.php
│   ├── EndPoints
│   │   └── UserEndPoint.php
│   ├── Logic
│   └── Model
├── Config
│   └── Config.php
├── Test
│   └── TestClient.php
├── composer.json
├── fast.php
└── vendor
```

其中：

目录或文件 | 是否必须 | 功能
------- | ------- |-------
App | 是 | fast工程的主要逻辑代码都在这个文件夹中
App.php |  是  | 声明向外暴露的接口，需要在$endPoints 成员变量中声明
EndPoints | 是 | 所开放接口的实现类所在的位置
Logic | 否 | 业务逻辑
Model | 否 | 数据模型
Config | 是 | 配置文件所在的文件夹
Config.php | 是 | 配置文件
Test | 否 | 测试类
composer.json | 是 | composer 描述文件
fast.php | 是 | 服务启动命令（无需修改）
vendor | 是 | composer vendor

手工创建必要的项目文件后，在composer中添加对fast-framwork的依赖。

注：目前fast-app-sample依赖fast-framework的方式比较丑陋，但这只是暂时的。待fast-framework发布到官方composer库之后，即可避免这种写法。

```
"autoload": {
	"psr-4": {
			"Fast\\": "../fast-framework/src/Fast/",
			"App\\": "App/"
	},
	"files": [
			"../fast-framework/src/Fast/Core/Support/helpers.php"
	]
}

```
#### 配置

微服务的发布者可以通过Config/Config.php对服务端进行一些设置，如：

* 端口：指定发布服务的端口
* swoole server设置：对swoole server进行详细设置，详情请见：https://wiki.swoole.com/wiki/page/274.html
* 白名单：设置客户的访问白名单



#### 开发业务接口

开发业务接口，参考 "fast-app-sample/App/EndPoints/UserEndPoint.php"，主要流程如下：

1. 在EndPoints文件夹中创建对应的类，继承 “Fast\Core\EndPoint\EndPoint\EndPoint”
2. $path成员变量为该 endPoint 的访问路径，如：user
3. fire() 方法，为“/“ 根路径的处理器，如：user
4. 以do开头的共有方法，为"/去掉do方法的方法名“的处理器，如：doQueryAdmin() 对应 QueryAdmin
5. endpoint中的接口方法，可以返回数组类型或stdClass类型。返回值将自动转化为json

### 调用业务微服务

调用fast微服务的总体流程：
1. 构造ApiRequest对象
2. 发起调用


#### 构造ApiRequest对象

可以使用ApiRequest提供的构造器来构造对象：

```
$apiRequest = ApiRequest::build()->setServer("127.0.0.1")
	->setPath("user")
	->setBody(["k1" => "v1","k2" => "v2"]); 
```
#### 发起请求

执行以下方法完成调用。

```
ClientManager::build()->executeRequests($apiRequest);

```

调用支持多请求同时调用，以及支持同步与异步两种方式。


#### 同步阻塞

```php

$req1 = ApiRequest::build()->setServer("127.0.0.1")
	->setPath("user")
	->setBody(["k1" => "v1","k2" => "v2"]);
$req2 = ApiRequest::build()->setServer("127.0.0.1")
	->setPath("user/QueryAdmin");
$req3 = ApiRequest::build()->setServer("127.0.0.1")
	->setPath("foobar");
$req4 = ApiRequest::build()->setServer("127.0.0.1")
	->setPath("endpointsList");
	
$reps = ClientManager::build()->executeRequests([$req1,$req2,$req3,$req4]);

```

同步阻塞，支持多请求(multi-requests)调用，此种调用方式将复用tcp连接，以提高性能。从日志上看，没有连接、断开的过程

```bash
2017-06-20 01:24:45 Connection [1] connected!
2017-06-20 01:24:45 Incoming request 1 user
2017-06-20 01:24:45 Process request  1 user done. response code: 200
2017-06-20 01:24:45 Incoming request 1 user/QueryAdmin
2017-06-20 01:24:45 Process request  1 user/QueryAdmin done. response code: 200
2017-06-20 01:24:45 Incoming request 1 foobar
2017-06-20 01:24:45 Process request  1 foobar done. response code: 404
2017-06-20 01:24:45 Incoming request 1 endpointsList
2017-06-20 01:24:45 Process request  1 endpointsList done. response code: 200
```

#### 异步非阻塞

异步非阻塞客户端求只能用于cli环境。

```php
ClientManager::build()->executeRequests($req2, true,
	function($cli, $data){
	 	var_dump($data);
	 	});
```

异步非阻塞也支持多请求(multi-requests)调用，但此种调用方式时，不可有回调callback。

#### 异常处理

客户端调用微服务时，需要捕获 Fast\Core\FastException 类型的异常。如果连接微服务接口出现问题，将通过此种类型的异常抛出。

## 部署与安全

### Daemon
fast-framework不提供fast服务端在*nix系统层面的daemon化，如有需要，使用者可根据实际情况，选择各种工具实现此功能。如：

* systemctl
* supervisord
* pm2

### 安全
为了性能，fast对客户端的安全性验证目前只提供简单的“ip白名单”功能。所以我们建议使用者不要将fast服务直接部署在具有公网IP的服务器上。更合理的做法是，将fast服务器部署在内网，保证服务间只在内网的通信。在汽车达人（topka.cn），我们使用k8s的若干基础功能，来实现服务之间有关连接安全审计方面的约束。

## 合作
有关fast框架的任何问题，欢迎Issue、PR～～

如果您在生产环境中使用了fast，也请让我们知道，谢谢。

欢迎邮件联系：hong.yang@topka.cn

## Developer

* Hong Yang (hong.yang@topka.cn / kevin.yanghong@gmail.com)

## License
The fast framework is open-sourced software licensed under the MIT license.











