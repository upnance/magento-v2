## Upnance Gateway

We highly suggest to test it on a staging/development environment first.

Tested on Magento 2.3 / 2.4 (PHP version up to 8.3).
Compatible with Magento 2.4.7
If you have trouble running it on an older Magento version, please, try to update it first.

### Installation
```
composer require upnance/magento-v2
php bin/magento module:enable Upnance_Gateway
php bin/magento setup:upgrade
php bin/magento setup:static-content:deploy
php bin/magento setup:di:compile
php bin/magento cache:clean
``` 

**Please note that FTP installation will not work as this module has requirements that will be auto installed when using composer**

Pull requests welcome.
