## Supermetrics assignment solution

### Project installation

1- Set up local docker and docker-compose tools.

2- Go to the project root folder and create `.env` file:
```bash
cp .env.dist .env
```
The default configuration will work from the box.

3- Start the containers
```bash
docker-compose up -d
```

4- Connect to project PHP container, install packages, import MySQL schema and run parse posts command
```
docker exec -it sm_api_php bash
composer install
mysql -hmysql -usm -psm sm < docker/mysql/main.sql
bin/parse-posts
```

5- Once all posts are parsed open and check posts statistics on main page in browser: [http://localhost:8003/](http://localhost:8003/)

JSON is pretty printed and can be viewed conveniently in page source. 


### Used libraries explanation

* `guzzlehttp/guzzle` - needed to parse Supermetrics posts API. The tool is more convenient and controllable than plain Curl calls. 
* `doctrine/orm` - used to manipulate database information and calculate statistics using MySQL queries capabilities
* `beberlei/doctrineextensions` - adds missing MySQL native functions support to Doctrine
* `symfony/dotenv` - needed to parse environment variables to use them as project configuration in an easy and robust way