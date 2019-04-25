## Hotel Inventory
### This application is using Laravel and Vue

#### How to Run
- clone this repository `git clone git@github.com:troazz/travlr-developer-test.git`
- run docker-compose `docker-compose up -d`
- install vendors for application `docker-compose exec app composer install`
- migrate all tables `docker-compose exec php artisan migrate`
- seed all tables `docker-compose exec php artisan db:seed`  [OPTIONAL]
- All Set! Now you can open application in browser with this url [http://localhost:8080](http://localhost:8080) 

#### Credential 
**Email**: admin@here.com
**Password**: password
