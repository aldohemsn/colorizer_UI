* * *

点色 - 给语言来点颜色
=============

这是一个基于PHP和自然语言处理技术的简单颜色标注工具。

仓库中的文件
-----------------------

* `.replit`: Replit的配置文件。
* `Dockerfile`: 设置环境的Docker配置。
* `README.md`: 项目的README文件。
* `apache2-utils_2.4.57-2_amd64.deb`: Apache工具包。
* `custom.conf`: 自定义配置文件。
* `en.json`, `es.json`, `fr.json`: 针对特定语言的JSON文件。
* `fonts/liujianmaocao-regular-webfont.woff`: 字体文件。
* `fonts/liujianmaocao-regular-webfont.woff2`: 字体文件。
* `functions.php`: 功能性PHP文件。
* `index.php`: 应用程序的主要PHP文件。
* `jquery.min.js`: jQuery库文件。
* `replit.nix`: Replit Nix环境的配置。
* `start.sh`: 启动应用程序的Shell脚本。
* `styles.css`: 样式表文件。
* `usageInfo.json`: 使用信息的JSON文件。
* `usageInfo.xml`: 使用信息的XML文件。

环境变量
---------------------

要运行应用程序，您需要设置以下环境变量：

* `CORENLP_URL`: CoreNLP服务的URL。
* `CORENLP_USER`: CoreNLP服务的用户。
* `CORENLP_PASSWORD`: CoreNLP服务的密码。
* `AUTH_USER`: Apache认证的用户名。
* `AUTH_PASS`: Apache认证的密码。
* `UMAMI_SCRIPT`: UMAMI跟踪代码。

Detailed Overview
---------------------

The "colorizer_UI" application is a web-based tool designed to colorize text based on part-of-speech tagging. It provides an interactive interface for users to input text, select a language, and view the colorized output.

1. User Interface (index.php):

Entry Point: The main interface allows users to input text and choose a language.

Metadata: The webpage's title and meta information suggest its primary function as a text colorization tool based on part-of-speech tagging.

Usage Instructions: Users can access a set of instructions on how to use the application, parsed from an XML file.

Output Display: Once the text is processed, the colorized output is shown, with options to reset the colorization or return to the input view.

2. Backend Processing (functions.php):

Configuration: The application fetches settings for the CoreNLP service, a natural language processing tool, from environment variables.

Supported Languages: The app supports multiple languages, including English, Spanish, French, and German. Language-specific configurations are loaded from JSON files.

User Input: The user's input is processed, and if it exceeds a certain length, it's truncated for efficiency.

Text Colorization:

The colorize function communicates with the CoreNLP service to tokenize the input text and obtain part-of-speech tags.

Using these tags and a predefined color scheme from the respective language's JSON file, the text is colorized.

3. Interactive Features (colorized-text-clicking.js):

Tooltip Display: Hovering over a word reveals its part-of-speech as a tooltip.

Word Interactions:

Clicking on a word allows users to hide or show related words based on their part-of-speech, either within the same sentence or across the entire document.

Different types of clicks (e.g., regular, Ctrl+Click, Shift+Ctrl+Click) offer varied interaction patterns.

Sentence Interaction: Using Alt+Click on a sentence, users can reset the background color of all words in that sentence to their default colors.

开始使用
---------------

1. 克隆仓库:

    ```bash
    git clone https://github.com/aldohemsn/colorizer_UI.git
    ```

2. 导航到仓库目录:

    ```bash
    cd colorizer_UI
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
    -e UMAMI_SCRIPT='umami_script' \
    colorizer_php_app
    ```

4. 通过浏览器访问应用程序 `http://localhost:8080`。

贡献
------------

# aldo

Inspired by [Parts-of-speech.Info](https://parts-of-speech.info/)
