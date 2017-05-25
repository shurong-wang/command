Window 上 git bash 命令行中文乱码问题
--

###1. 使用 `git log` 出现乱码

> 修改 %GIT_HOME%\etc\gitconfig 文件，加入如下内容：

```
[gui]  
     encoding = utf-8  
[i18n]  
    commitencoding = utf-8  
[svn]  
    pathnameencoding = gbk  
```

> 修改 %GIT_HOME%\etc\profile 文件，加入：

```
export LESSCHARSET=utf-8  
```
 

### 2. 使用 vi/vim 查看中文内容出现乱码

> 修改 %GIT_HOME%\share\vim\vimrc 文件，在文件末尾加入如下内容:

```
set fileencodings=utf-8,ucs-bom,cp936,big5  
set fileencoding=utf-8  
set termencoding=gbk
```

### 3. 命令行输入中文出现乱码

> 修改 %GIT_HOME%\etc\inputrc 文件，加入：

```
set output-meta on
set convert-meta off
```


### 4. 使用 `ls` 命令，出现乱码

> 修改 %GIT_HOME%\etc\git-completion.bash 文件，在文件末尾加入：

```
alias ls='ls --show-control-chars --color=auto' 
``` 

### 5. 使用 `git status` 命令，出现乱码：

```
git config --global core.quotepath false
``` 
