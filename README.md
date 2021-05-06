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