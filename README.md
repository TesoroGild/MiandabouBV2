# Miandabou Accessoire Backend App
Coming soon...

# Utilisation
Télecharger les dépendances
```bash
composer install
```

Installer une dépendance
```bash
composer require symfony/[nom]
```

Lancer le serveur
```bash
symfony server:start
```

Arrêter le serveur
```bash
symfony server:stop
```

# Développement
## Symfony
Ajouter une librairie
```bash
composer require [lib]
composer require --dev symfony/[lib]
```

Supprimer le cache
```bash
php bin/console cache:clear
```

## Création
Créer une classe d'authentification
```bash
symfony console make:auth
```

Créer une table
```bash
symfony console make:entity
```

Créer un controller
```bash
symfony console make:controller
```

## Base de données
Créer la base de données
```bash
php bin/console doctrine:database:create
```

Valider les changements
```bash
symfony console make:migration
```

Pousser les changements dans la bd
```bash
symfony console d:m:m
```

## Docker
Voir les containers
```bash
docker container ls
``` 

Voir les logs d'un container
```bash
docker logs [IDNAME]
```

Supprimer une table en ligne de commande
```bash
docker exec -it [nom_du_container] -U [utilisateur] -d [nom_base_de_donnees] -c "DROP DATABASE [table];"
```