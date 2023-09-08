* * *

巧言·点色 - 给外语来点颜色
=============

这是一个基于PHP的简单颜色标注工具。

仓库中的文件
-----------------------

* `.replit`: Replit的配置文件。
* `Dockerfile`: 设置环境的Docker配置。
* `custom.conf`: 自定义配置文件。
* `en.json`, `es.json`, `fr.json`: 针对特定语言的JSON文件。
* `index.php`: 应用程序的主要PHP文件。
* `replit.nix`: Replit Nix环境的配置。
* `start.sh`: 启动应用程序的Shell脚本。

环境变量
---------------------

要运行应用程序，您需要设置以下环境变量：

* `CORENLP_URL`: CoreNLP服务的URL。
* `CORENLP_USER`: CoreNLP服务的用户。
* `CORENLP_PASSWORD`: CoreNLP服务的密码。
* `AUTH_USER`: Apache认证的用户名。
* `AUTH_PASS`: Apache认证的密码。

开始使用
---------------

1. 克隆仓库:

    ```bash
    git clone https://github.com/aldohemsn/colorizer_PHP.git
    ```

2. 导航到仓库目录:

    ```bash
    cd colorizer_PHP
    ```

3. 如果使用Docker，构建并运行Docker容器:

    ```bash
    docker build -t colorizer_php_app .
    docker run -p 8080:80 \
    -e CORENLP_URL='corenlp_url' \
    -e CORENLP_USER='corenlp_user' \
    -e CORENLP_PASSWORD='corenlp_password' \
    -e AUTH_USER='apache_user' \
    -e AUTH_PASS='apache_password' \
    colorizer_php_app
    ```

4. 通过浏览器访问应用程序 `http://localhost:8080`。

贡献
------------

# colorizer_UI
