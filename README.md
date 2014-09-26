Важно: по всем изменениям планирую позже отписаться подробнее

---

mediabox-storage
================

Simple php storage for mediabox

## Install:

```
git clone https://github.com/Zazza/mediabox-storage.git
cd mediabox-storage/

curl -s https://getcomposer.org/installer | php
php composer.phar install

chmod +w app/runtime
mkdir upload
chmod +w upload

# mysql -u root -p <password>
mysql> CREATE USER '[USER]'@'localhost' IDENTIFIED BY '[PASSWORD]'; 
mysql> CREATE DATABASE [DBNAME]; 
mysql> GRANT SELECT , INSERT , UPDATE , DELETE ON `[DBNAME]` . * TO '[USER]'@'localhost'; 
mysql> USE [DBNAME];
mysql> SOURCE docs/schema.sql; 
mysql> SOURCE docs/data.sql; 
mysql> QUIT

```

Go to http://[STORAGE_URL]

login/password: admin/admin

Change:

Storage authorization, Oauth (Client id и Client secret), Access-Control-Allow-Origin, Upload path ([virtual_root]/upload/ by default)

---

docs/nginx/virtual_config.example (with CORS)

## Increase upload filesize limits:
###php.ini:
```
upload_max_filesize = [NUM]M
post_max_size = [NUM]M
```

###nginx.conf:
```
client_max_body_size = [NUM]M
```
