Django 前端教程
---

### 1. Django 命令

Django 安装成功，系统便拥有了 <i>**`django-admin.py`**</i> 命令。<br>
执行 <i>**`django-admin.py startproject kavout`**</i> 会自动生成项目 kavout。<br>
项目初始目录结构如下：

```
kavout
	└── kavout
	    ├── __init__.py
	    ├── settings.py
	    ├── urls.py
	    └── wsgi.py
	├── manage.py
```

其中，需要特别注意 <i>**manage.py**</i> 文件，它是 Django 项目管理的命令行工具。<br/>
进入项目根目录，通过 <i>**manage.py**</i> 可以进行启动服务、新建应用、同步数据、创建用户等。<br/>

**前端同学可能用到 Django 命令：**<br/>

| 操作           			 | 命令        	             | 参数说明               |
| :---------------------:|:--------------------------|:----------------------|
| 查看 Django 版本    | <i>**`django-admin.py --version`**</i> |例如：我的 Django 版本为 1.8.12|
| 同步数据（version < 1.7.1）    | <i>**`python manage.py syncdb`**</i> | 文档称自 1.7 版起已弃用，但我们的主站 kavout.com 的 Django 版本为 1.8.12，同步数据时仍需要使用该命令才能成功。[知道原因的请再此说明]|
| 同步数据（version >= 1.7.1）   | <i>**`python manage.py makemigrations`**</i><br><br><i>**`python manage.py migrate`**</i>|启动项目时，可能会看到红色提醒 <small style='color:red'>You have unapplied migrations; your app may not work properly until they are applied.</small><br>按照提示，执行 <i>**`python manage.py migrate`**</i> 即可。<br>这时，会在项目根目录生成 *db.sqlite3* 数据文件，保存 Django 自带一些应用数据 |
| 启动 Django 服务   | <i>**`python manage.py runserver`**</i> | 默认在 127.0.0.1:8000 启动，如果提示 <small style='color:red'>Error: That port is already in use.</small> 说明端口被占用。<br>可以选择在其他端口（如 8080）启动：<br><i>**`python manage.py runserver 8080`**</i>|
| 新建一个应用（如：blog） | <i>**`python manage.py startapp blog`**</i> | 执行成功，会在项目根目录生成 `blog` 应用目录。`blog` 目录下的文件在下一小节介绍|
| 汇集静态文件到指定目录  | <i>**`python manage.py collectstatic`**</i> | 把 app 下 `static` 中的静态文件全部拷贝到 `STATIC_ROOT` 路径指定的文件夹下。变量`STATIC_ROOT`在`settings.py`中设置 |

<span style="color:red">**Tips：**</span><br/>
使用 *python manage.py* 系列命令时，通常会将具体参数写入项目配置目录下的单独配置文件，如 *`kavout/settings_dev.py`*。<br>
然后，通过连接 *`--settings=kavout.settings_dev`* 执行。以我们 kavout.com 开发环境为例：

- 同步数据：<i>**`python manage.py syncdb --settings=kavout.settings_dev`**</i>
- 启动服务：<i>**`python manage.py runserver --settings=kavout.settings_dev`**</i><br>


### 2. Django 项目结构

```
kavout
	└── kavout
		   ├── __init__.py
		   ├── settings.py
		   ├── settings.dev.py
		   ├── urls.py
		   ├── wsgi.py
		   
	└── blog
		   └── migrations
		    	  ├── __init__.py
		    	  
		   └── templates
		   	     └── blog
		   		     ├── index.html
		   		     
		   ├── __init__.py
		   ├── admin.py
		   ├── models.py
		   ├── tests.py
		   ├── urls.py
		   ├── views.py
		   
   └── templates
		   ├── 404.html
		   ├── 500.html
   		
   └── static
	      └── css
	      └── js
	      └── img
      
   ├── db.sqlite3
   ├── manage.py
   ├── requirements.txt
   ├── README.md
```

各目录文件的具体说明：

```
kavout（项目容器，执行 django-admin.py startproject kavout 自动生成）

	└── kavout（项目管理目录，与项目容器同时生成且同名。新手易误认为一个应用，为什么不叫 config !-_-）
		   ├── __init__.py（默认空文件，声明所在目录 kavout 为 Python 模块）
		   ├── settings.py（项目配置文件，可以配置：路径、应用、模板、缓存、数据库、时区、语言等）
		   ├── settings.dev.py（为开发环境添加单独的配置文件，方便本地调试）
		   ├── urls.py（应用路由配置，在这里配置 URL 正则，将 URL 请求分发到 Views）
		   ├── wsgi.py（Python Web Server Gateway Interface，服务器部署相关的配置）

	└── blog（应用目录，执行 python manage.py startapp blog 自动生成）
		   └── migrations（数据迁移模块，在 Django 1.7 之前版本，新建应用不会生成该目录）
		    	  ├── __init__.py（声明所在目录 migrations 为 Python 模块）
		    	   
		   └── templates（放置 blog 应用的模板文件）
		   	     └── blog（额外加一级目录，否则 Django 傻傻分不清同名模板应该去哪个应用去渲染 !-_-）
		   		     ├── index.html
		   		     
		   ├── __init__.py（声明所在目录 blog 为 Python 模块）
		   ├── admin.py（将 blog 数据模型注册到 Django 后台数据管理）
		   ├── models.py（定义 blog 的数据模型，构建 blog 应用相关数据表结构）
		   ├── tests.py（blog 的自动化测试文件）
		   ├── urls.py（blog 的路由配置文件，将 URL 请求分发到 views）
		   ├── views.py（定义函数：处理业务逻辑，响应请求，返回 HTML 等）
		   
   └── templates（放置公共模板文件）
		   ├── 404.html
		   ├── 500.html
   		
   └── static（放置静态资源文件）
	      └── css
	      └── js
	      └── img
	      
   ├── db.sqlite3（数据文件，执行 python manage.py migrate 自动生成）
   ├── manage.py（Django 项目的命令行管理工具）
   ├── requirements.txt（记录 python 依赖，相当于 package.json。新手看到 txt 很容易当做普通文本 !-_-）
   ├── README.md（可以记录项目简介、环境搭建、特别注意、版本介绍内容等）
```

<span style="color:red">**Tips：**</span><br/>

其中，前端开发人员需要注意的目录文件包括：

- 日常开发中，主要关心 `templates` 目录和`static` 目录下的文件即可

- 搭建环境时，需要执行 `pip install -r requirements.txt` 安装项目后端依赖

- 运行环境时，需要执行 `python manage.py runserver --settings=kavout.settings_dev`

- 开发环境报 ***Bad Request(400)*** 错误时，在 `kavout/urls.py` 设置 `ALLOWED_HOSTS=['*']` 允许任意访问来源

- 新建应用时，需要在 `kavout/settings.py` 等 *settings* 文件加入应用名；同时，在 `kavout/urls.py` 添加 URL 匹配

- 添加 HTML 页面时，除了编写模板文件，还需要修改应用下的 `urls.py`、`views.py` 文件，以及项目配置目录 `kavout` 下的 `urls.py`。后面小节会具体介绍


### 3. 添加新页面

想要为`blog 应用`添加一个页面，并在 Django 服务中通过 URL 访问，需要完成以下步骤：

1. 添加 `blog 应用`到 `kavout/settings.py`：

	```python
		INSTALLED_APPS = (
		    'django.contrib.admin',
		    'django.contrib.auth',
		    'django.contrib.contenttypes',
		    'django.contrib.sessions',
		    'django.contrib.messages',
		    'django.contrib.staticfiles',
		    
		    'blog', # 加入 blog 应用
		)
	
	```

2. 添加模板文件 `blog/templates/blog/index.html`：

	```html
	<!DOCTYPE html>
		<html>
		    <head>
		        <meta charset="utf-8">
		        <title>{{ title }}</title>
		    </head>
		    <body>
		        <h3>Kavout 博客</h3>
		        <div class="content">
		            <ul>
		                {% for blog in blogs %}
		                    <li>{{ blog.name }}</li>
		                {% endfor %}
		            </ul>
		        </div>
		    </body>
		</html>

	```
	
3. 添加响应函数到 `blog/views.py`：
	
	```python
	# -*- coding: utf-8 -*-
	from django.shortcuts import render
	
	# Create your views here.
	def index(request):
	context = {}
	context['title'] = '首页'
	context['blogs'] = [
	{
	    'id': '1',
	    'name': '博客一',
	    'content': '第一篇博客',
	}, {
	    'id': '2',
	    'name': '博客二',
	    'content': '第二篇博客',
	},
	]
	
	return render(request, 'blog/index.html', context)
	```
	
4. 添加应用内路由 `blog/urls.py`：

	```python
	# -*- coding: utf-8 -*-
	from django.conf.urls import include, url
	
	# import blog.views as blog_views
	from . import views
	
	urlpatterns = [
	    # url(r'^index/', blog_views.index),
	    url(r'^index/', views.index), # 注意：r'^index/' 不要忘掉斜杠 /
	]
	```
	添加项目配置路由 `kavout/urls.py`：
	
	```python
	# -*- coding: utf-8 -*-
	"""Kavout URL Configuration
	from django.conf.urls import include, url
	from django.contrib import admin
	
	urlpatterns = [
	    url(r'^admin/', include(admin.site.urls)),
	    url(r'^blog/', include('blog.urls')), # 注意：'blog.urls' 要加单引号
	]
	```
	
<span style="color:red">**Tips：**</span><br/>
	
- 如果出现 *SyntaxError: Non-ASCII character* 错误，说明 python 文件中（包括注释）出现了汉字。
这时，需要在代码最开始位置加入 `# -*- coding: utf-8 -*-`
	
	
- 如果服务正常启动，页面仍然报 `ERR_EMPTY_RESPONSE` 错误，则可能是因为开启了翻墙工具，请尝试退出翻墙工具后重试

- `kavout/settings.py` 中 `DEBUG = True` 时，如果找不到静态资源，尝试在 `kavout/urls.py` 配置：
	
	```python
	STATICFILES_DIRS = (
	    os.path.join(BASE_DIR, "static"),
	)
	```
另外，如果使用 Django 相对路径方式加载静态资源，如 `<img src="{% static image %}loading.gif" />`，还需要在模板文件头部添加 `{% load static %}`

- `kavout/settings.py` 中 `DEBUG = False` 时，如果找不到静态资源，尝试在 `kavout/urls.py` 配置：

	```python
	from django.views.static import serve as static_serve
	
	if settings.DEBUG is False:
	    urlpatterns += [
	        url(r'^static/(?P<path>.*)$', static_serve, {'document_root': settings.STATIC_ROOT}),
	    ]
	```

- 如果向后端发送的是 Ajax 请求，则需要在 `views.py` 函数做相应处理：
	
	```python
	# -*- coding: utf-8 -*-
	from django.shortcuts import render
	from django.http import JsonResponse
		
	def hotNews(request):
	
	    newsDict = {
	        '1': {
	            'id': '1',
	            'name': '中国队 1 : 0 韩国',
	            'content': '中国队取胜，仍保留出线可能',
	        },
	        '2': {
	            'id': '2',
	            'name': '人民的名义热播',
	            'content': '人民的名义热播，达康书记狂圈粉',
	        },
	    }
    
		if request.is_ajax():
			return JsonResponse(newsDict)
		
		return render(request, 'news/hotNews.html', newsDict)
	```

- 前端发送 Ajax 请求事，为了防止 CSRF 攻击，最好做以下设置：
	
	```javascript
	$.ajaxSetup({
		// 使用了模板变量，不能写在 js 文件中，要直接写在模板文件中
	    data: {csrfmiddlewaretoken: '{{ csrf_token }}' },
	});
	```
		
### 4. DTL 模板标签

DTL(Django template language) 是 Django 默认的模板语言，DTL 常用模板标签示例：


```html
{{ sum|default:'0.00' }}
```

```html
{% load static %}
```

```html
{% url 'articel' 12 %}
```

```html
{% if value in [10, 100, 1000] %}
   ... display 1
{% elif value < 10 %}
   ... display 2
{% else %}
   ... display 3
{% endif %}
```

```html
{% for item in list reversed %}
    <li>
    	{{forloop.counter1}}
    	{% if not forloop.first %}|{% endif %}
    	{{ item.name }}
    </li> 
{% endfor %}
```

```html
{% for key, value in json.items %}
    {{ key }}: {{ value }}
{% endfor %}
```

```html
{{ request.user }}
```

```html
{% if request.user.is_authenticated %}
    您好，{{ request.user.username }}！
{% else %}
    <a>请登陆</a>
{% endif %}
```

```html
{{ request.path }}?{{ request.GET.urlencode }}
```

```html
{% csrf_token %}
```

```html
{# 模板注释 #}
```

```html
{{ list|join:", " }}
```

```html
{% include "nav.html" %}
```

```html
{% block css %}{% endblock %}
```

```html
{% extends "base.html" %} 
```

### 5. 隔离项目运行环境

开发中经常会在一台机器上安装多个的项目，各项目使用的 python 版本或依赖（如 Django）版本不同时，后安装的就会覆盖之前安装，造成之前项目无法运行。<br>
可以通过 virualenv、Anaconda 等工具，在同一机器搭建多个虚拟 python 运行环境，将各项目隔离来避免以上问题。<br>
下面以 Anaconda 为例，列出搭建一个 python 的虚拟运行环境用到的命令：

- 查看 python 环境列表：
	`conda info --envs`

- 创建一个 python3 虚拟环境：
	`conda create -n kavenv python=3`

- 激活（进入） py3 虚拟环境：
	`source activate kavenv`

- 查看 python 版本：
	`python --version`

- 查看 py3 已安装的 packages：
	`conda list -n kavenv`

- 在 py3 安装 packages：
	`conda install -n kavenv Django=1.8.2`

- 注销（离开）当前环境，恢复之前环境状态：
	`source deactivate kaenv `

- 移除 py3 虚拟环境：
	`conda remove -n kavenv --all`



