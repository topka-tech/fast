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

1. 在EndPoints文件夹中创建对应的类，继承 “Fast\Core\EndPoint\EndPoint”
2. $path成员变量为该 endPoint 的访问路径，如：user
3. fire() 方法，为“/“ 根路径的处理器，如：user
4. 以do开头的共有方法，为"/去掉do方法的方法名“的处理器，如：doQueryAdmin() 对应 QueryAdmin
5. endpoint中的接口方法，可以返回数组类型或stdClass类型。接收者将收到包含此返回值信息，以及其他附加信息的ApiResponse对象。

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
$resps = ClientManager::build()->executeRequests($apiRequest);
```
返回值$resps，是一组ApiResponse对象。其中每个ApiResponse对应一个ApiRequest。ApiResponse包含了状态码、返回值等信息。具体ApiResponse所具有的属性，请参考ApiResponse源码。

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


