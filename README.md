# btpan_offlin
需要安装Aria2 作为后端

Aria2 安装:

Debian 8系统 apt-get 安装
 
``` 
apt-get update && apt-get install -y aria2 
```

创建目录: 

```
mkdir /root/.aria2 
```

修改配置文件: 下载配置文件:http://webdir.cc/aria2.conf 

修改:  
保存到刚刚的目录上 

```
wget http://webdir.cc/aria2.conf /root/.aria2/ 
```

下载http://webdir.cc/dht.dat dht.dat 到/root/.aria2/ 
```
wget http://webdir.cc/dht.dat /root/.aria2/ 
```
执行命令: 
```
echo '' > /root/aria2.session 
```
执行命令，让aria2启动: 
若没安装screen 请先
```
apt-get install -y screen 
```

在安装好screen后执行
```
screen -dmS aria2 aria2c --enable-rpc --rpc-listen-all=true --rpc-allow-origin-all -c 
```


基于ThinkPHP Bootstrap Jquery BTpan Webdir

/uploads /download /Index /Runtime 777

配置文件 /Index/Conf/config.php

数据库 Torrent.sql

记得crontab 添加一条
```
*/1 * * * * curl http://your_website/index.php/Ondo/insert
```
用于实时刷新下载内容。


