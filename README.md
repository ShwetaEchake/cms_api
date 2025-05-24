git clone https://github.com/username/project-name.git

cd project-name

composer install

copy .env.example .env

Then update .env with your local database configuration:
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password

php artisan key:generate

php artisan migrate:refresh --seed

php artisan serve



