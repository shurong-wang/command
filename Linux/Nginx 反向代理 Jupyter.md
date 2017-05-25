
# 本地 kavoutai Jupyter 环境配置

### 1. 约定服务端口及相关配置
-----------------------------------------------------------------
1. Jupyter 端口: 9008
	1. 启动 Jupyter 时，加入参数: --ip=127.0.0.1 --port=9008
	2. 在 Nginx 配置文件设置: proxy_pass http://127.0.0.1:9008;

2. Nginx 端口: 8009
	1. 在 Nginx 配置文件设置: listen 8009;
	2. 访问本地开发环境：http://127.0.0.1:8009/

3. uWSGI socket 端口: 8005
	1. 在 Nginx 配置文件设置: uwsgi_pass 127.0.0.1:8005;
	2. 在 uWSGI 配置文件设置: socket = 127.0.0.1:8005


### 2. 约定 Jupyter 用户(例如：shurong)
-----------------------------------------------------------------
	1. 启动 Jupyter 时，加入参数: --NotebookApp.base_url=/shurong/
	2. 在 Nginx 配置文件设置: location ^~ /shurong/
	3. shurong 作为本地调试环境的登录账号


### 3. 安装 Nginx，修改 Nginx 配置文件（相关路径配置以自己机器为准）
-----------------------------------------------------------------

```
user www;
worker_processes  1;
worker_rlimit_nofile 100000;

error_log  /Users/wsr/nginx_dir/error.log warn;
pid        /Users/wsr/nginx_dir/nginx.pid;

events {
    worker_connections  2048;
    multi_accept on;
}

http {

    include      /usr/local/etc/nginx/mime.types;
    default_type  application/octet-stream;

    log_format  main  '$remote_addr - $remote_user [$time_local] "$request" '
                      '$status $body_bytes_sent "$http_referer" '
                      '"$http_user_agent" "$http_x_forwarded_for"';

    access_log  /Users/wsr/nginx_dir/access.log  main;

    server_tokens off;
    sendfile      on;
    tcp_nopush    on;
    tcp_nodelay on;

    keepalive_timeout  10;
    client_header_timeout 10;
    client_body_timeout 10;
    reset_timedout_connection on;
    send_timeout 10;

    #gzip模块设置
    gzip on;
    gzip_min_length 1k;
    gzip_buffers 4 16k;
    gzip_http_version 1.1;    gzip_comp_level 2;
    gzip_types application/json application/javascript text/plain text/css application/x-javascript text/xml application/xml application/xml+rss text/javascript image/jpeg image/gif image/png;
    gzip_vary on;
    #limit_zone crawler $binary_remote_addr 10m;
    add_header Access-Control-Allow-Origin *;
    add_header Access-Control-Allow-Headers X-Requested-With;
    add_header Access-Control-Allow-Methods GET,POST,OPTIONS;

    server
    {
        listen 8009;
        location ^~ /shurong/ {
            proxy_pass            http://127.0.0.1:9008;
            proxy_set_header      Host $host;
            proxy_http_version    1.1;
            proxy_set_header      Upgrade "websocket";
            proxy_set_header      Connection "Upgrade";
            proxy_read_timeout    86400;
        }

        location ^~ /static {
            root /Users/wsr/myProject;
            expires 7d;
            add_header Pragma public;
            add_header Cache-Control "public, must-revalidate, proxy-revalidate";
            add_header Access-Control-Allow-Origin *;
            log_not_found off;
            tcp_nodelay off;
        }

        location / {
            include /usr/local/etc/nginx/uwsgi_params;
            uwsgi_connect_timeout 60;
            uwsgi_send_timeout 60;
            uwsgi_read_timeout 60;
            uwsgi_pass 127.0.0.1:8005;
        }

    }
}
```

### 4. 安装 uWSGI，修改 uWSGI 配置文件（相关路径配置以自己机器为准）
-----------------------------------------------------------------
```
[uwsgi]
socket = 127.0.0.1:8005
#http = 0.0.0.0:80
chdir = /Users/wsr/myProject/
pythonpath = ..
env = DJANGO_SETTINGS_MODULE=myProject.settings_dev
module=myProject.wsgi:application
master=True
pidfile=/Users/wsr/myProject/project-master.pid
vacuum=True
max-requests=5000
daemonize=/Users/wsr/myProject/logs/myProject.log
processes = 1
threads = 2
stats = 127.0.0.1:17005
buffer-size=32768
```


### 5. 约定 notebook 目录和 Jupyter 日志文件（目录不存在须新建）
-----------------------------------------------------------------
1. 约定 notebook 目录为 /Users/wsr/note-dir/
	启动 Jupyter 时，加入参数: --notebook-dir=/Users/wsr/note-dir/

2. 约定日志文件位置为 /Users/wsr/jupyter.log
	启动 Jupyter 时，加入参数:  > /Users/wsr/jupyter.log


### 6. 查看 Jupyter 是否启动 && 结束占用端口的进程(非必须)
-----------------------------------------------------------------
- 查看 Jupyter 是否启动（9008端口是否占用）：
>`lsof -i :9008`

- 结束占用端口的进程（如果需要使用该端口）
>`kill -9 [进程号]`


### 7. 后台启动 Jupyter (确保 note-dir 目录存在，端口 9008 未占用)
-----------------------------------------------------------------
```
nohup jupyter notebook --NotebookApp.token= --ip=127.0.0.1 --debug --no-browser --notebook-dir=/Users/wsr/note-dir/ --port=9008 --NotebookApp.base_url=/shurong/ --NotebookApp.allow_origin='*' > /Users/wsr/jupyter.log 2>&1 &
```


### *8. 注释相关代码，并保证不能提交线上
-----------------------------------------------------------------

修改 /Users/wsr/myProject/module/views.py

```python

#from myProject.KWare.alpha.empower import FactorWeightEmpower
#from myProject.KWare.config.factor import FactorConfig, ModelConfig
#from myProject.KWare.alpha.models import FactorModel
#from myProject.KWare.alpha.metrics import FactorModelMetrics
```


### *9. 本地 git 忽略配置开发环境对项目的修改
-----------------------------------------------------------------
`cd /Users/wsr/myProjectmyProject/`

`git status`

```
	modified:   myProject/module/urls.py
	modified:   myProject/module/views.py
	modified:   myProject/tools/nginx_dev.conf
	modified:   myProject/tools/uwsgi_test.ini

```

执行 git 命令：

```
	git update-index --assume-unchanged myProject/module/urls.py
	git update-index --assume-unchanged myProject/module/views.py
	git update-index --assume-unchanged myProject/tools/nginx_dev.conf
	git update-index --assume-unchanged myProject/tools/uwsgi_test.ini

```


### 10. 修改本机 Jupyter 环境的源码（实际路径以本机 Jupyter 安装目录为准）
-----------------------------------------------------------------

##### 1. 修改 /Applications/anaconda/lib/python2.7/site-packages/notebook/static/tree/js/main.min.js line:14701
```javascript
var input = $('<input/>').attr('type','text').attr('size','25').attr('maxlength','30').addClass('form-control')
```

##### 2. 修改：/Applications/anaconda/lib/python2.7/site-packages/notebook/static/notebook/js/actions.js line:707, line:719
```javascript
// warn_bad_name(name);
```

##### 3. 修改 /Applications/anaconda/lib/python2.7/site-packages/notebook/static/notebook/js/main.min.js line:30909, line:30921
```javascript
// warn_bad_name(name);
```

##### 4. 修改 /Applications/anaconda/lib/python2.7/site-packages/notebook/static/notebook/js/main.min.js line:29204
```javascript
// console.warn('actions', id_act, 'does not exist, still binding it in case it will be defined later...');
```

### 11. 将项目下的 Jupyter 主题目录软链到本机 Jupyter 环境的主题目录
-----------------------------------------------------------------
```
ln -s /Users/wsr/myProject/jupyter/themes/custom /Users/wsr/.jupyter/custom
```


### 12. 用 uWSGI 启动项目服务（注意：不要用 python manage.py 启动）
-----------------------------------------------------------------
```
uwsgi --ini /Users/wsr/myProject/tools/uwsgi_test.ini
```

### 13. 关于 `ERR_EMPTY_RESPONSE` 错误
-----------------------------------------------------------------
请确保 Nginx 服务已启动，并已退出翻墙工具


### 14. 登录平台（注意：使用约定的端口和账号）
-----------------------------------------------------------------
>访问：127.0.0.1:8009

>账户：shurong

>密码：***


=================================================================


### 其他：
-----------------------------------------------------------------

#### Nginx 默认路径
`/usr/local/etc/nginx/`

#### 本地项目路径
`/Users/wsr/myProjectmyProject/`

#### 查看 jupyter 进程 (查看 jupyter 是否启动成功)
```ps -ef|grep jupyter```

#### 验证 Nginx 配置文件是否正确
```
sudo nginx -t -c /Users/wsr/myProject/tools/nginx_dev.conf
```

#### 检查 Nginx 进程是否存在
`ps aux|grep nginx`

#### 启动 Nginx
```
sudo nginx -c /Users/wsr/myProject/tools/nginx_dev.conf
```

#### 重载 Nginx
```
sudo nginx -s reload -c /Users/wsr/myProject/tools/nginx_dev.conf
```

#### 查看 Nginx 访问日志
```
tail -f /Users/wsr/nginx_dir/access.log
```

#### 重载 uWSGI
```
uwsgi --reload /Users/wsr/myProject/project-master.pid
```
