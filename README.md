### API Development Course by LAHTP

To get started, clone this repository to a proper document root. For XAMPP, this is `htdocs`. For private apache setup, its upto you how you configiure. 

This code is right now accessible at: https://api1.selfmade.ninja

Right outside the document root, create a file called `env.json` and keep the contents of the file similar to the following. 

```
{
	"database": "apis",
	"username": "root",
	"password": "password",
	"server": "localhost",
	"email_api_key": "Your_Sendgrid_Key"
}
```

This will be called by the API functions to get the database connection. 

This project is under development.

#### Virtual Host Apache Configuration:

```
<VirtualHost *:80>
    ServerAdmin hello@sibidharan.me       
    DocumentRoot "/var/www/api-development-course-apr-2021"
    ServerName api1.selfmade.ninja 

        ErrorLog ${APACHE_LOG_DIR}/error.log
        CustomLog ${APACHE_LOG_DIR}/access.log combined

    <Directory "/var/www/api-development-course-apr-2021">
            Options Indexes FollowSymLinks ExecCGI Includes
            AllowOverride All
            Require all granted
    </Directory>

# Added automatically by LetsEncrypt
RewriteEngine on
RewriteCond %{SERVER_NAME} =api1.selfmade.ninja
RewriteRule ^ https://%{SERVER_NAME}%{REQUEST_URI} [END,NE,R=307]
</VirtualHost>

```

