一款[typecho][1]验证码插件，使用google的[reCAPTCHA][2]接口。
======

#### 使用方法：
1. 到「[页面][3]」申请一个API key；
2. 激活该插件，并配置public key和private key；
3. 在适当地方添加如下代码：

```
reCAPTCHA_Plugin::output();
```


[1]: http://typecho.org/about
[2]: https://www.google.com/recaptcha/
[3]: https://www.google.com/recaptcha/admin/create