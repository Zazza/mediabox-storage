mediabox-storage
================

http://blog.8x86.ru/fm/

Simple php storage for mediabox

## Install:
```
git clone https://github.com/Zazza/mediabox-storage.git
cd mediabox-storage/

curl -s https://getcomposer.org/installer | php
php composer.phar install

mkdir upload
chmod 770 upload
```

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
