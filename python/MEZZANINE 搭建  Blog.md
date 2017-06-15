Mezzanine 搭建 BLOG 系统
---

### 1. 创建 python 虚拟环境

- 查看虚拟环境列表：
	`conda info --envs`

- 创建虚拟环境 mezzenv：
	`conda create -n mezzenv python=3`

- 进入虚拟环境 mezzenv：
	`source activate mezzenv`


### 2. 安装 Mezzanine

- pip install mezzanine


### 3. 新建 mezzblog 项目

- mezzanine-project mezzblog

- cd mezzblog

- python manage.py createdb

- python manage.py runserver 0.0.0.0:8066


### 4. 后台管理 Mezzanine 配置

- 访问 http://127.0.0.1:8066/admin/
	
	> 默认账号：admin
	
	> 默认密码：default
	
- 进入 `Content > Pages` 配置导航、 页脚信息

- 进入 `Content > Blog posts` 添加分类、发布文章

- 进入 `Site > Settings` 配置网站 Site Title、Tagline


### 5. 通过配置文件修改模板风格

```
mezzblog
	└── mezzblog
		   ├── __init__.py
		   ├── settings.py
		   ├── local_settings.py
		   ├── urls.py
		   ├── wsgi.py
```

修改 `mezzblog/mezzblog/settings.py` 文件：

- 配置 Homepage 为 Blog：

	注释代码：`#url("^$", direct_to_template, {"template": "index.html"}, name="home"),`<br>
	取消注释：`
		url("^$", "mezzanine.blog.views.blog_post_list", name="home"),`
		
- 去掉导航栏 Search 输入框的可选项：

	添加配置项：`SEARCH_MODEL_CHOICES = []`
	
- 去掉左侧边连和页脚：

	添加配置项：`PAGE_MENU_TEMPLATES = ( (1, "Top navigation bar", "pages/menus/dropdown.html"), )`
	
	<i style="color:red">Tips:</i> 要完全去掉左侧边连和页脚，还要修改模板文件`base.html`。稍后第 8 小节介绍 `base.html` 在哪，以及如何修改


### 6. 配置 mezzblog 主题样式

- 在新建中新建一个应用，命名为`theme`:

	```python
	python manage.py startapp theme
	```
	
- 在 `theme` 目录下只保留 `__init__.py` 文件，其他删除：

	```
	mezzblog		   
		└── theme
			   ├── __init__.py
	```

- 自定义 `theme/static/css/custom.css` 样式文件，覆盖默认样式：

	```
	mezzblog 
		└── theme
			   ├── __init__.py
			   └── static
			   		 └── css
			   		 	├── custom.css
			   		 
			   
	```
	
- 将 `theme` 应用添加到 `mezzblog/mezzblog/setting.py`：

	```python
	INSTALLED_APPS = (
		"theme",
	    "django.contrib.admin",
	    "django.contrib.auth",
	    # ...
	)
	```


### 7. 修改 mezzblog 模板布局

将 Mezzanine 的原始模板汇集到 `theme` 应用下，覆盖默认模板：

- 执行 `python manage.py collecttemplates` 会在项目根目录生成模板文件目录 `templates`

- 将 `templates` 移动到 `theme` 应用下：

	```
	mezzblog 
		└── theme
			   ├── __init__.py
			   └── static
			   		 └── css
			   		 	├── custom.css
			   		 	
			   └── templates
			   		├── base.html
			   		├── ...
	```
	
- 去掉左侧边连和页脚（先在 `settings.py` 文件添加相应配置，见第 5 小节）：

	1. 删除 `base.html` 文件中的 3 处代码：
	
		```
		<div class="panel panel-default tree">{% page_menu "pages/menus/tree.html" %}</div>
		```
		
		```
	   {% page_menu "pages/menus/footer.html" %}
		```
		
		```html
	    <ul class="breadcrumb">
		    {% spaceless %}
		    {% block breadcrumb_menu %}{% page_menu "pages/menus/breadcrumb.html" %}{% endblock %}
		    {% endspaceless %}
	    </ul>
		```

	2. 调整布局元素宽度，将左边栏占据宽度减小，中间正文部分宽增加：
	
		```html
		 <div class="col-md-0 left">
            {% block left_panel %}
            {% endblock %}
        </div>
        <div class="col-md-9 middle">
            {% block main %}{% endblock %}
        </div>
		```

		<i style="color:red">Tips:</i> 通过修改 `Bootstrap` 栅格布局样式 `col-md-*` 实现
	

### 8. 汇集 Mezzanine 静态文件

执行 `python manage.py collectstatic` 命令，将 Mezzanine 原始静态文件和自定义的 `custom.css` 全部拷贝到 `theme` 应用下。这样，可以在项目中重写静态资源文件，方便部署静态服务。


### 9. 配置 DEBUG = False 

在本地开发时，`settings.py` 中默认 `DEBUG = True`，Django 不会限制访问来源，并且会自动寻找静态文件。

当设置 `DEBUG = False` 时，可能会产生两个问题，可以尝试按以下方式解决：

1. 报 *Bad Request(400)* 错误
	
	在 `settings.py` 配置：
	
	 ```python
	 ALLOWED_HOSTS = ['*']
	 ```

2. 找不到项目的静态资源文件

	在 `urls.py` 加入：
	
	 ```python
	 from django.views.static import serve as static_serve
	 
	 if settings.DEBUG is False:
	    urlpatterns += [
	        url(r'^static/(?P<path>.*)$', static_serve, {'document_root': settings.STATIC_ROOT}),
	    ]
	 ```

### 10. 结语

   至此，通过 Mezzanine 搭建 Blog 的项目结构基本完成，后续样式和布局的修改在 `theme` 应用下修改即可。

   用 Mezzanine 搭建 Blog 对于新手最大的阻碍在弄不清去哪修改模板和样式，模板和样式文件在创建项目时并没有自动生成。<br>

   需要执行以下两条关键命令，在当前项目下生成模板和静态文件：<br>
   <i style="color:red">`python manage.py collecttemplates`</i><br>
   <i style="color:red">`python manage.py collectstatic`</i><br>
   






