# ullr

我试着做了个XSS Platform，只有一点基本功能，不包含Payload，自己写吧。

## 安装

1、您需要apache2（nginx应该也行）、mysql、php7。

>apt install -y apache2 php libapache2-mod-php php-gd php-mysql mysql-server mysql-client

2、如果想使用https的话：

>a2enmod ssl

3、登录mysql，创建数据库：

>CREATE DATABASE `xss-platform` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_bin;

4、数据库在sql文件中:

>source xss-platform.sql

5、有些参数需要简单配置一下：

    +在config/db-creds.inc配置数据库参数
    
    +在config/config.inc配置你的站点的Url
    
6、开启url rewrite

>a2enmod rewrite

7、修改apache2的配置文件，添加如下内容：

>&lt;Directory &quot;/var/www/html&quot;&gt;<br/>
>&nbsp;&nbsp;&nbsp;&nbsp;AllowOverride All<br/>
>&lt;/Directory&gt;

## 使用

打开浏览器访问一下看看~

预置了一个用户root/toor，可以使用这个用户生成邀请码，然后再注册新用户。

~~PS：数据库居然是明文存密码，下一个版本再改吧。~~

如果您愿意帮我添加payload，或者修bug，给我pull request或者提issue。

## 注意：未获授权的安全测试将承担法律责任，请只在获得授权的情况下使用本工具。
