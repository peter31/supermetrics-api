## Supermetrics assignment solution

### Project installation

Supported OS are Linux and MacOS.

Set up local docker and docker-compose tools.

Go to the project root folder and create `.env` file:
```bash
cp .env.dist .env
```

The default configuration will work from the box.

Start the containers
```bash
docker-compose up -d
```

Connect to project PHP container, import MySQL schema and run parse posts command
```
docker exec -it sm_api_php bash
mysql -hmysql -usm -psm sm < docker/mysql/main.sql
bin/parse-posts
```

Once all posts are parsed open and check posts statistics on main page in browser:
[http://localhost:8003/](http://localhost:8003/)


### Used libraries explanation

* `guzzlehttp/guzzle` - needed to parse Supermetrics posts API. The tool is more convenient and controllable than plain Curl calls. 
* `doctrine/orm` - used to manipulate database information and calculate statistics using MySQL queries capabilities
* `beberlei/doctrineextensions` - adds missing MySQL native functions support to Doctrine
* `symfony/dotenv` - needed to parse environment variables to use them as project configuration in an easy and robust way