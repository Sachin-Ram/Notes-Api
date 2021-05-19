### API Development Course by LAHTP

To get started, clone this repository to a proper document root. For XAMPP, this is `htdocs`. For private apache setup, its upto you how you configure. 

This code is right now deployed at: https://api1.selfmade.ninja

API Documentation for the development can be found at the [Wiki Section](https://git.selfmade.ninja/sibidharan/api-development-course-apr-2021/-/wikis/home) of this repo. 

Thanks to [Manickam Venkatachalam](https://git.selfmade.ninja/Manic) for making the API documentation happen.

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
</VirtualHost>
```

In the above configuration, `env.json` should sit exactly `/var/www/env.json` here.

#### Configuring your own Ubuntu Setup

Reference: https://www.digitalocean.com/community/tutorials/how-to-install-linux-apache-mysql-php-lamp-stack-on-ubuntu-20-04


1. Update and upgrade the system first.

```
$ sudo apt update && sudo apt -y upgrade
```

2. Install Apache, MySQL and PHP

```
$ sudo apt install apache2 libapache2-mod-php mysql-server php-mysql
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

5. Now fix the file permissions for `/var/www` folder like you own it. The below command will change the owner of the foler /var/www as you, so that no errors will come when you try to edit or create.

```
$ cd /var
$ sudo chown $(whoami):$(whoami) -R www
```

6. Now import the database export locaked at `database/export.sql` into the database you just created and we have all the tables. 

Now update the `env.json` file with the user and database info created. All set, your code should be accessible at http://localhost or whereever you configured it to work. 

### Security

All the data that you get with `$this->_request[]` inside the APIs are secured with `mysqli_real_escape_string` during the API initialization. Look for the function called REST::cleanInputs() inside `api/REST.api.php` and here is where it happens. So this development is considered secured from MySQLi injections. If you access `$_GET` or `$_POST` anywhere else directly without `$this->_request[]`, then you might just need to filter the inputs yourself and make them secure. 

