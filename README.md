# LIB2MOVE Project

###### Requirements :

Docker<br>
Docker-compose<br>

###### How to start :

```
cp .env.dist .env

docker-compose up -d
docker-compose exec web composer install
```

Open a web-browser and type 'localhost'. (Make sure there is nothing listening on port *80 to avoid conflits)

### RULES :

Once the application deployed, you have to make at least one administrator (see below how to).
Before to get any user, admins have to give access to routes of users in the admin dashboard.


### Infos :

Create an admin by php bin/console with :
````
command             params
app:create-admin    (pseudo) (email) (password)
````

[DevMode] Create all fixtures :
````
command
app/console hautelook:fixtures:load 
````

Access to the database at :
(id -> root | password -> root)
```
http://localhost:8080 
```

Turn application from DEV environnement to PROD environnement :
```
#in file .env
[...]
APP_ENV=prod
[...]
```

