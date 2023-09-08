* * *

巧言·点色 - 学外语可以来点颜色
=============

A simple PHP-based colorizer tool.

Files in the Repository
-----------------------

* `.replit`: Configuration file for Replit.
* `Dockerfile`: Docker configuration for setting up the environment.
* `custom.conf`: Custom configuration file.
* `en.json`, `es.json`, `fr.json`: Language-specific JSON files.
* `index.php`: Main PHP file for the application.
* `replit.nix`: Configuration for Replit Nix environment.
* `start.sh`: Shell script to start the application.

Environment Variables
---------------------

To run the application, you'll need to set the following environment variables:

* `CORENLP_URL`: URL for the CoreNLP service.
* `CORENLP_USER`: User for the CoreNLP service.
* `CORENLP_PASSWORD`: Password for the CoreNLP service.
* `AUTH_USER`: Username for Apache authentication.
* `AUTH_PASS`: Password for Apache authentication.

Getting Started
---------------

1. Clone the repository:
    
    ```bash
    git clone https://github.com/aldohemsn/colorizer_PHP.git
    ```
    
2. Navigate to the repository directory:
    
    ```bash
    cd colorizer_PHP
    ```
    
3. If using Docker, build and run the Docker container:
    
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
    
4. Access the application via your browser at `http://localhost:8080`.
    

Contributing
------------
# colorizer_UI
