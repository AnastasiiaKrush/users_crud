Users CRUD application 
Create, read, update and delete functionality for user entity.

Getting Started
These instructions will get you a copy of the project up and running on your local machine for development and testing purposes.

Prerequisites
1. PHP >= 7.2.5 
2. MySQL 5.6+
3. SQL Server 2017+
4. Composer

How to run project
1. Clone the repository.
2. Open console and cd into downloaded project folder.
3. In console run "composer install".
4. To create database need to do next steps.
	Connect to mysql with next command in console "mysql -u your_username -p your_password".
	Create database with next command in console "create database your_database_name;"
	Disconnect mysql with "exit;" in console.
5. Turn back into project folder and enter your database credentials in .env file:
	DB_HOST=your_database_host
	DB_PORT=your_database_port
	DB_DATABASE=your_database_name
	DB_USERNAME=your_username
	DB_PASSWORD=your_password
6. Run in console in project folder "php artisan migrate" to create all needed tables.
7. Run in console in project folder "php artisan key:generate".
8. Run in console in project folder "php artisan serve". Then you'll see in console on which url laravel development server started. Copy this url to browser and try to test application.
