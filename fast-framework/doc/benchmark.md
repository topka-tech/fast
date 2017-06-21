
## 性能测试

利用ab工具，我们对框架进行了简单的性能测试，结论如下：

* 测试机配置：MacBook Pro 2.7 GHz Intel Core i5 / 8GB / 128G SSD 
* MacOS 10.12.5 (16F73) / PHP 7.1.5 / Swoole 2.0.7
* 压测命令：ab -n1000 -c10 
* 关掉fast-framework的console输出

结果如下：

url | 场景描述 | RPS
------- | ------- | -------
/fast-app-sample/Test/TestClient.php | 调用fast服务 |  538.95
/static.html  | 与fast服务返回值相同的静态页面 |  9072.27
/ | phpinfo()页面 |  507.97
/wordpress/ |Wordpress首页|   5.36

可见，fast调用远程API的速度非常之快，已经超过phpinfo()的速度

```bash
➜  fast-app-sample git:(master) ✗ ab -n1000 -c10 http://127.0.0.1:9091/fast-app-sample/Test/TestClient.php

This is ApacheBench, Version 2.3 <$Revision: 1757674 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.8.1
Server Hostname:        127.0.0.1
Server Port:            9091

Document Path:          /fast-app-sample/Test/TestClient.php
Document Length:        7222 bytes

Concurrency Level:      10
Time taken for tests:   1.855 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      7383000 bytes
HTML transferred:       7222000 bytes
Requests per second:    538.95 [#/sec] (mean)
Time per request:       18.555 [ms] (mean)
Time per request:       1.855 [ms] (mean, across all concurrent requests)
Transfer rate:          3885.82 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       1
Processing:    12   18   6.2     18     145
Waiting:       12   18   6.2     18     145
Total:         12   18   6.2     18     145

Percentage of the requests served within a certain time (ms)
  50%     18
  66%     19
  75%     19
  80%     20
  90%     22
  95%     26
  98%     33
  99%     37
 100%    145 (longest request)

➜  fast-app-sample git:(master) ✗  ab -n1000 -c10 http://127.0.0.1:9091/       
This is ApacheBench, Version 2.3 <$Revision: 1757674 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.8.1
Server Hostname:        127.0.0.1
Server Port:            9091

Document Path:          /
Document Length:        100978 bytes

Concurrency Level:      10
Time taken for tests:   1.969 seconds
Complete requests:      1000
Failed requests:        99
   (Connect: 0, Receive: 0, Length: 99, Exceptions: 0)
Total transferred:      101138893 bytes
HTML transferred:       100977893 bytes
Requests per second:    507.97 [#/sec] (mean)
Time per request:       19.686 [ms] (mean)
Time per request:       1.969 [ms] (mean, across all concurrent requests)
Transfer rate:          50171.47 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.1      0       1
Processing:     6   19  14.9     14      94
Waiting:        5   17  13.1     12      92
Total:          7   20  14.9     14      94

Percentage of the requests served within a certain time (ms)
  50%     14
  66%     16
  75%     18
  80%     20
  90%     43
  95%     50
  98%     71
  99%     85
 100%     94 (longest request)

➜  fast-app-sample git:(master) ✗  ab -n1000 -c10 http://127.0.0.1:9091/static.html 
This is ApacheBench, Version 2.3 <$Revision: 1757674 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.8.1
Server Hostname:        127.0.0.1
Server Port:            9091

Document Path:          /static.html
Document Length:        1349 bytes

Concurrency Level:      10
Time taken for tests:   0.110 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      1582000 bytes
HTML transferred:       1349000 bytes
Requests per second:    9072.27 [#/sec] (mean)
Time per request:       1.102 [ms] (mean)
Time per request:       0.110 [ms] (mean, across all concurrent requests)
Transfer rate:          14015.95 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.2      0       2
Processing:     0    1   0.5      1       4
Waiting:        0    1   0.4      0       4
Total:          0    1   0.5      1       4
ERROR: The median and mean for the waiting time are more than twice the standard
       deviation apart. These results are NOT reliable.

Percentage of the requests served within a certain time (ms)
  50%      1
  66%      1
  75%      1
  80%      1
  90%      2
  95%      2
  98%      3
  99%      3
 100%      4 (longest request)

➜  fast-app-sample git:(master) ✗ ab -n1000 -c10 http://127.0.0.1:9091/wordpress/
This is ApacheBench, Version 2.3 <$Revision: 1757674 $>
Copyright 1996 Adam Twiss, Zeus Technology Ltd, http://www.zeustech.net/
Licensed to The Apache Software Foundation, http://www.apache.org/

Benchmarking 127.0.0.1 (be patient)
Completed 100 requests
Completed 200 requests
Completed 300 requests
Completed 400 requests
Completed 500 requests
Completed 600 requests
Completed 700 requests
Completed 800 requests
Completed 900 requests
Completed 1000 requests
Finished 1000 requests


Server Software:        nginx/1.8.1
Server Hostname:        127.0.0.1
Server Port:            9091

Document Path:          /wordpress/
Document Length:        52046 bytes

Concurrency Level:      10
Time taken for tests:   186.712 seconds
Complete requests:      1000
Failed requests:        0
Total transferred:      52288000 bytes
HTML transferred:       52046000 bytes
Requests per second:    5.36 [#/sec] (mean)
Time per request:       1867.116 [ms] (mean)
Time per request:       186.712 [ms] (mean, across all concurrent requests)
Transfer rate:          273.48 [Kbytes/sec] received

Connection Times (ms)
              min  mean[+/-sd] median   max
Connect:        0    0   0.0      0       1
Processing:   771 1862 525.7   2059    3223
Waiting:      750 1829 516.9   2025    3118
Total:        771 1862 525.7   2059    3223

Percentage of the requests served within a certain time (ms)
  50%   2059
  66%   2169
  75%   2232
  80%   2274
  90%   2381
  95%   2594
  98%   2829
  99%   2948
 100%   3223 (longest request)

```

