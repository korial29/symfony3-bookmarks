A Symfony project created on December 14, 2017, 4:28 pm.
# symfony3-bookmarks

## Récupération du code 

Récupérer le code avec GIT, avec la commande suivante : 
```bat
git clone https://github.com/korial29/symfony3-bookmarks.git
```

## Installer l’application 

Se positionner dans le dossier racine de l'application où se trouve le fichier “composer.json”. 
Lancer cette commande : 
```bat
php composer.phar install
```

## Installer la base de données

Création de la base de données : 
```bat
php bin/console doctrine:database:create
```
Création des tables :
```bat
php bin/console doctrine:schema:update --force
```

## Dump assets

Pour déployer les fichiers js et css, lancer la commande suivante : 
```bat
php bin/console assetic:dump --env=prod
```

## Lancement de l’application 

Démarrer l’application dans le navigateur avec l’adresse suivante : 
http://localhost:8080/symfony3-bookmarks/web/

