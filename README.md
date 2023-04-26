<h1 align="center">Soccer</h1>


## About Soccer
This application is to see the soccer teams details. User and Guest user can see the teams and its player details, and Only admin will be able to manage the teams details. This application is build based on REST API.

#### There are some key features like
- Create Soccer Teams 
- Edit and Delete Teams 
- View Team List
- Add players to Team
- View Player list with respect to teams
- Edit and delete players


This application have Guest, user and admin role. Admin only able to do the creat, edit and delete action for both Team and players.

## To Run The Application with Docker 
First rename `.env.example` to `.env`  
Start the docker with command -`docker-compose up -d`     
Now we have to set up laravel application, so run the below commands   
1.`docker-compose exec php-apache /bin/bash `   
2.`cd /var/www/html`     
3.`composer update --no-scripts`  
4.`php artisan storage:link`  
5.`php artisan migrate`  
6.`php artisan db:seed`  
7.Now access the application by go to `http://localhost:8088`   
8.Please use the below User Details

    Admin Details : admin@soccer.com Password : admin@123
    User Details : user@soccer.com Password : user@123


## API documentation

API documentation added with swagger.  
 To see the documentation please goto this `http://localhost:8088/api/documentation`  link.






