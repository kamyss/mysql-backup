# 使用 PHP 编写的 MYSQL 备份恢复的小工具  
基于 [Laravel 5.7](https://laravel.com/docs/5.7) 和 [Laravel Zero](https://laravel-zero.com) 编写。

## 运行环境

PHP 7.1.3+

## 使用手册

### 1、复制源代码并进入到项目根目录 
`git clone https://github.com/huanghua581/mysql-backup.git && cd mysql-backup`


### 2、安装依赖
`composer install`

### 3、设置配置文件
`cp .env.example .env`

**配置说明：**  
> DB_HOST：数据库连接地址    
> DB_USERNAME：数据库用户名     
> DB_PASSWORD：数据库密码    
> DB_DATABASES：需要备份的数据库（多数据库英文','分割，例如：abc,def 即备份 abc 和 def 两个数据库）；     
> BACKUP_DAYS：数据库备份保留天数 ，默认为保留 7 天；  
> MYSQL_PATH：MYSQL bin 目录，默认为空，（如果默认不能执行，请设置此项，例如：MYSQL_PATH=/usr/bin/，记得带后面的 /） 。

### 4、开始使用
* 执行 `php mysql-backup` 会列出支持命令；
* 执行 `php mysql-backup create` 备份配置文件中的数据库，可选参数（--database）即 `php mysql-backup  create --database abc` 只备份数据库 abc ；
* `php mysql-backup all` 会列出所有备份文件；
* 执行 `php mysql-backup restore` 恢复数据，必填参数 （--snapshot）即 `php mysql-backup restore --snapshot abc_2018-12-02_04411543725670.sql` 恢复数据库 abc；


当然我们还可以计划任务：https://laravel-zero.com/#/usage?id=scheduler ，默认每天凌晨备份，保留 7 天的数据。

 
如果觉得还是不够方便，自己改源码吧。

## License

MySql Backup is an open-source software licensed under the [MIT license](https://github.com/laravel-zero/laravel-zero/blob/stable/LICENSE.md).
