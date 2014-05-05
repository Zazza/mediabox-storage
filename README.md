mediabox-storage
================

Simple php storage for mediabox

## Install:

git clone https://github.com/Zazza/mediabox-storage.git

cd mediabox-storage/web/silex/

curl -s https://getcomposer.org/installer | php

php composer.phar install

cd ..

mkdir upload

mkdir upload/_thumb

chown -R www-data:www-data upload 

chmod 770 upload
