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

#### Configuring your own Ubuntu Setup

Reference: https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-20-04


1. Update and upgrade the system first.

```
$ sudo apt update && sudo apt -y upgrade
```

2. Install Apache, MySQL and PHP

```
$ sudo apt install apache2 libapache2-php-mod mysql-server php-mysql
```

3. Secure MySQL Database

```
$ sudo mysql_secure_installation
```

and follow the onscreen steps. For more info, check the above link.

4. Create a Database 

```
$ mysql -u root -p
Password:
```

Enter the password you have given for root during `mysql_secure_installation` and you can see the following promot.

```
mysql>
```

From here, we need to create a database called `apis`.

```
mysql> CREATE DATABASE apis;
```

We also need to create a mysql username and password and give the database previleges for the database we created.

```
mysql> CREATE USER 'apiuser'@'localhost' IDENTIFIED BY 'password';
Query OK, 0 rows affected (0.02 sec)

mysql> GRANT ALL PRIVILEGES ON * . * TO 'apiuser'@'localhost';
Query OK, 0 rows affected (0.00 sec)

mysql> FLUSH PRIVILEGES;
Query OK, 0 rows affected (0.01 sec)

mysql> exit
Bye

```

Now update the `env.json` file with the user and database info created.

