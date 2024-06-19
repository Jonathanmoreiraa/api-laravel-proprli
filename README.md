# Proprli API

## Description
Proprli API is a task management system built with Laravel 10, tailored for the real estate sector. It allows users to create tasks, list tasks by building, create comments, edit the text and date of the comments, and more.

## Prerequisites
Before you begin, ensure you have met the following requirements:

1. Install Composer from https://getcomposer.org/download/
2. Install Apache to use PHP from https://sourceforge.net/projects/xampp/files/
3. Uncomment the following lines in the php.ini file by removing the "`;`":
```
extension=pdo_pgsql
extension=pgsql
```
4. Install PostgreSQL and create a database to store the data (the credentials will be used in .env file).
   - I recommend also the instalation of pgAdmin to use the PostgreSQL.

## Installation
To install the project, follow these steps:

1- Clone the repository:
```
git clone https://github.com/Jonathanmoreiraa/api-laravel-proprli.git
```

2- Navigate to the project directory and install dependencies:
 ```
 composer install
 ```
3- Install PHPUnit and dump autoload:
```
composer require --dev phpunit/phpunit
composer dump-autoload 
```

## Project initialization
1- Rename the ```.env.example``` file to ```.env```  and modify the information according to your project and database. An example with database configuration:
```
DB_CONNECTION=pgsql
DB_HOST=localhost
DB_PORT=5432
DB_DATABASE=proprli
DB_USERNAME=postgres
DB_PASSWORD=root
```
2- Run the migrations with the command:
```
php artisan migrate
```
3- Seed the database with essential data:
```
php artisan db:seed
```
**Note**: This command will create some essential data and also create one task and one comment. Additional data can be created manually using the **Usage** section below.

4- Start the server:
```
php artisan serve
```

## Usage
To use the project after installation, you can use the following routes and import the ```api-proprli-insomnia.json``` into Insomnia, only changing the ```base_url```:
- **Users**:
  - `GET /users`: List all users
- **Buildings**:
  - `GET /buildings`: List all buildings
- **Task Statuses**:
  - `GET /status`: List all task statuses
- **Tasks**:
  - `POST /tasks`: Create a new task
  - `GET /tasks/{id}`: List tasks by building
  - `PUT /tasks/{id}`:  Update a task
- **Comments**:
  - `POST /comment`: Create a new comment
  - `PUT /comment/{id}`: Update a comment
- **Filters**:
  - `GET /filters`: List all filters

## License
This project is licensed under the MIT License.

## Contact
* LinkedIn: [Jonathan Moreira](https://www.linkedin.com/in/jonathanmoreira1/)
