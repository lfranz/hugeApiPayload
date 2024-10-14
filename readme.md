# hugeApiPayload

This is a laravel project that taking a curl like the following

```
curl --location 'http://localhost:80/api/hugeApiPayload' \
--header 'X-CSRF-TOKEN: tokenvaluetobeinserted235kwgeiOIulgsk' \
--form 'filename="testHugeApiPayload.json"'
```

get a file from an s3 bucket and save flyers into a local db.


The idea is to, having a request like the following,
```
{
  "CrawlerRequest": {
    "retailer_id": "3249",
    "crawler_id": "1385",
    "data": {
      "flyers": [
        {
          "Flyer": {
            "id": "c6584f6de7",
            "title": "Flyer Title",
            "url": "https://website.com/flyers/flyer.pdf",
            "start_date": "2016-11-28",
            "end_date": "2016-12-01",
            "flyer_url": "https://website.com",
            "flyer_files": [
              "http://www.website.com/flyer_front.jpg"
            ]
          }
        },
        {
          ....
        }]
      ]
    }
  }
}
```
add all flyers information into the database calling an API using the API Gateway.

Unfortunately is not possible, due to api gateway hard limits, to send request greater than 10Mb and than the solution is to use a pre-signed url to upload the request as a file in an s3 bucket.

Here I've reported a flow to generate pre-signed url and upload file into an s3 bucket:
```mermaid
sequenceDiagram
    User->>Api gw: Post /upload
    Api gw->>Lambda: call Lambda function
    Lambda-->>Lambda: generate S3 signed url
    Lambda-->>Api gw: S3 signed url
    Api gw-->>User: {'s3_url': 'http://....'}
    User-->>S3: upload file using signed url
```

# Usage
create a .env file into src folder adding th following data:

**NOTE: the AWS_SECRET_ACCESS_KEY has been sent via email**

```
APP_NAME=Laravel
APP_ENV=local
APP_KEY=base64:2KtMI/cjhEKaWx5cSiVOo+MSwemuuc3Ry0MKTM8kFC0=
APP_DEBUG=true
APP_TIMEZONE=UTC
APP_URL=http://localhost

APP_LOCALE=en
APP_FALLBACK_LOCALE=en
APP_FAKER_LOCALE=en_US

APP_MAINTENANCE_DRIVER=file
# APP_MAINTENANCE_STORE=database

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=debug

DB_CONNECTION=mysql
DB_HOST=db
DB_PORT=3306
DB_DATABASE=hugeApiPayload
DB_USERNAME=hugeApiPayload
DB_PASSWORD=test1234

SESSION_DRIVER=database
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database
CACHE_PREFIX=

MEMCACHED_HOST=127.0.0.1

REDIS_CLIENT=phpredis
REDIS_HOST=127.0.0.1
REDIS_PASSWORD=null
REDIS_PORT=6379

MAIL_MAILER=log
MAIL_HOST=127.0.0.1
MAIL_PORT=2525
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=AKIAUYY76MWHAKWXURS6
AWS_SECRET_ACCESS_KEY=<sent via mail>
AWS_DEFAULT_REGION=eu-west-1
AWS_BUCKET=hugeapipayload
AWS_USE_PATH_STYLE_ENDPOINT=false

VITE_APP_NAME="${APP_NAME}"
```
I've created an s3 bucket with a small pre-uploaded json file. 

After that we can run the projects running the command 

```docker compose up -d```

inside the app folder.

Then a local environment with app, db and other stuffs, is almost ready to use.
We need to run the following commands to have a full working environment:

``` docker-compose run --rm artisan key:generate```

``` docker-compose run --rm composer install```

``` docker-compose run --rm artisan migrate``` 

Now it's possible to run the curl 
```
curl --location 'http://localhost:80/api/hugeApiPayload' \
--header 'X-CSRF-TOKEN: tokenvaluetobeinserted235kwgeiOIulgsk' \
--form 'filename="testHugeApiPayload.json"'
```
to add into the database all the flyers information taken from the json added into the s3 bucket.

## Note
I haven't created the lambda function to generate the pre-signed url and the other one that had to be triggered by the new file added into the s3 bucket for 2 reasons:
1. I'm not too expert of the aws eco-system, I'm studying it but on the architectural side.
2. I could have copy the lambda functions from internet in python or node.js

I've represented the flow used to generate a pre-signed url, at that point is missing the lambda function triggered when a new file was added into the s3 bucket that call the api, passing the filename and all the flow is completed.

Another point that I would to talk about is the usage of serverless, sincerely for me it's not clear the usage of this tool.
