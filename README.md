# fast framework

php构建微服务，一般都会采用构建http服务的方式——“每个微服务都是一个http服务，微服务之间采用http协议相互调用”。这样的方式，性能是个很大的痛点。这主要体现在：

* 微服务间的每次请求是一个完整的http过程，必须经过”创建连接、通信、结束连接“。与浏览器请求服务器的场景不同，由于微服务的提供者和调用者都是特定的服务器，所以在微服务的场景中，创建连接和结束连接，我们认为并不是必须的。
* 请求体过大：http协议所传输的数据，必须包括“请求行、请求头、请求体“三个部分，在微服务的场景中，这其中的有些内容，我们认为也不是必须的。

fast基于swoole，构建了一套灵巧、轻便、性能极高的微服务框架。

## 依赖

* php >= 5.5 (tested on 7.1)
* swoole >= 2.0

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

[详细的使用指南请见此处](fast-framework/doc/usage_cn.md)
[性能测试](fast-framework/doc/benchmark.md)

## 合作
有关fast框架的任何问题，欢迎Issue、PR～～

如果您在生产环境中使用了fast，也请让我们知道，谢谢。

欢迎邮件联系：hong.yang@topka.cn

## Developer

* Hong Yang (hong.yang@topka.cn / kevin.yanghong@gmail.com)

## License
The fast framework is open-sourced software licensed under the MIT license.











