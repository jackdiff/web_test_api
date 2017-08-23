#__Set up instruction__

## 1. Environment requirements
    * PHP
    * APACHE
    * MYSQL
    * composer
## 2. Clone source code
    * Get source code from git : git clone git@bitbucket.org:TruongPham/truongpham_web_api.git  your_folder_name
    * Place your_folder_name into your webroot folder
## 3. Install dependency
    * cd to your_folder_name
    * Run command : compose update
## 4. Permission setting
    * Make logs folder : mkdir your_folder_name/logs
    * Give write access to logs folder : your_folder_name/logs
## 5. Setup database
    *  Import to your database file sql : web_api_test_2017_08_23.sql  (attached in email or sql file in your_folder_name/database).
    * Config database info : fill entries database user name, password, database name, ... in one of these
        - your_folder_name/dev/database.php
        - your_folder_name/localhost/database.php
        - your_folder_name/production/database.php
    depends on your env setting. The default is "dev"

## 6. Config apache
    * Add these lines into your apache config file
        <VirtualHost *:80>
            ServerName "your_server_name_domain"
                DocumentRoot "/path_to_your_web_root_folder/your_folder_name/public"
            <Directory "/path_to_your_web_root_folder/your_folder_name/public">
            Options -MultiViews +FollowSymLinks
            RewriteEngine On
            RewriteCond %{REQUEST_FILENAME} !-f
            RewriteCond %{REQUEST_URI} !^(/css|/images|/views|/js|/favicon.ico)
            RewriteCond %{HTTP:Authorization} ^(.+)$
            RewriteRule .* - [E=HTTP_AUTHORIZATION:%{HTTP:Authorization}]
            RewriteRule ^ index.php [QSA,L]
                        AllowOverride All
                        Order allow,deny
                        Allow from all
        </Directory>
        </VirtualHost>

## 7. Testing
    * Sign in : 
        Route : /signin
        Method : POST
        Data-type : application/json
        Body Example : {"email":"a@gamail.com", "password":"123"}
    * Sign out:
        Route : /signout
        Method: POST
        Data-type : application/json
        Header: X-Access-Token: Bearer your_token_from_signin_response
    * Update user info :
        Route: /me/update
        Method: POST
        Data-type : application/json
        Header: X-Access-Token: Bearer your_token_from_signin_response
        Body Example : {"name":"Truong Pham Xuan", "phone_number":"0993653324"}
    * Get User info :
        Route: /me
        Method: GET
        Header: X-Access-Token: Bearer your_token_from_signin_response
    * Test user : 
        email : truongpxc@gmail.com
        password : truong123

## 8. Demo
    Check online demo at : http://demo_api.shipplus.com.vn


__Reasons to select Silex Framework__

## 1. It's microframework. It concise API.
## 2. It's built on Symfony kernel which abstracts request and response. This makes it easy to handle flow of request.
## 3. Easy to use third party libraries.
## 4. I've worked with Silex for several projects.