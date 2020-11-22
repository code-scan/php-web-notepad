# php web notepad 
## 修改自 minimalist-web-notepad

nginx config
```
location / {
    rewrite ^/([a-zA-Z0-9_-]+)$ /index.php?note=$1;
}
location ^~/_tmp_js_al_files 
{ 
    deny all; 
} 
```



* 增加上传文件支持
* 支持使用curl直接上传

```
➜  /tmp curl js.al -F 'data=@123.txt'
123.txt
https://js.al/95672-txt

```

网页上传会自动追加到文本中