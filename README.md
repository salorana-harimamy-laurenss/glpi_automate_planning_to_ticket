<h1 align="center">SCRIPT CREATION TICKET FROM PLANNING GLPI</h1>
<div align="center"> 
    <img src="https://glpi-project.org/wp-content/uploads/GLPI_Logo-color.png" width="150px"/>
</div>

> [!NOTE]
> C'est un script, permettant de récupérer les tâches planifiés dans le planning, et de créer automatiquement des tickets à partir de ces planning réguliers. On a utilisé l' **API GLPI** pour la révupération des informations et la création des tickets.


> [!IMPORTANT]
> #### Prérequis
> Il faut d'abord que vous avez déjà installer GLPI dans un environnement. Pour vous aider à l'installation , voir ce [Documentation sur l'installation](https://glpi-install.readthedocs.io/fr/latest/install/index.html) .Assurez vous que vous avez un PHP installé sur votre environnement. Vérifier que vous avez la module PHP_CURL instalé et activé pour la bonne utilisation du script.

**Préférence** : PHP >= 7.4

# Utilisation

Après avoir cloner le dépôt , installer les dépendances .env pour le développement en local.
**NB:** A noter que vous devez d'abord avoir **composer** installé sur votre environnement.

```bash

    $ composer install

```
Créer un fichier *.env* à la racine de ce répertoire, et spécifier les variables suivant :

- **API_URL** : Votre URL pour le API GLPI
- **USER_TOKEN** : Le Token de l'utilisateur ayant droit à l'accès à l'API
- **APP_TOKEN** : Le Token de l'application GLPI sur le API GLPI
- **WORKDIR_PATH** : La racine de répertoire courant

Décommenter les lignes où les chargements de Dotenv et les déclarations sont . 

Dans le dossier **script** , les classes utiles pour le script principal du **script_main.php**. Pour lancer le script :

```bash

    $ php script_main.php

```    

**NB:** Modifier les valeurs des variables d'environnement selon votre configuration.

# Documentation

Pour se documenter, voici quelques documentations :

- [Documentation GLPI Générale](https://glpi-project.org/documentation/)  
- [Documentation API Developper GLPI](https://glpi-developer-documentation.readthedocs.io/en/master/devapi/index.html)  
- [Documentation API GLPI](https://glpi-user-documentation.readthedocs.io/fr/latest/modules/configuration/general/api.html)  
