# Kernel Panic Soumissions
![PHP](https://img.shields.io/badge/-PHP-777BB4?style=flat&logo=php&logoColor=ffffff) ![Licence](https://img.shields.io/github/license/panic-at-the-kerne/soumissions)

Ce dépôt contient le code du site permettant de collecter les soumissions aux concours de Kernel Panic.


### Modifier le site

Une fois le dépôt cloner, installer les dépendances avec composer.
```shell
composer install 
```

Lancer le projet avec le serveur interne de php.
```shell
cd public
php -S 0.0.0.0:80
```

### Mise en production

Il n'y a pas d'image docker pour le moment, le site actuel est heberger avec Plesk.

Installation des dépendances et optimisations
````shell
composer install --no-dev -o
````
