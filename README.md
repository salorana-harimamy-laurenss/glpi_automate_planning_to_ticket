## SCRIPT CREATION TICKET FROM PLANNING GLPI

<h1 align="center"></h1>
<div align="center"> 
    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fglpi-project.org%2Ffr%2F&psig=AOvVaw1R23cL02_969Lu4wSiBclb&ust=1727269983952000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCIj8-p_U24gDFQAAAAAdAAAAABAE" width="150px"/>
</div>

> [!NOTE]
> C'est un script, permettant de récupérer les tâches planifiés dans le planning, et de créer automatiquement des tickets à partir de ces planning réguliers. On a utilisé l' **API GLPI** pour la révupération des informations et la création des tickets.


> [!IMPORTANT]
> #### Prérequis
> Il faut d'abord que vous avez déjà installer GLPI dans un environnement. Pour vous aider à l'installation , voir ce [Documentation sur l'installation](https://glpi-install.readthedocs.io/fr/latest/install/index.html)
> Assurez vous que vous avez un PHP installé sur votre environnement. Vérifier que vous avez la module PHP_CURL instalé et activé pour la bonne utilisation du script.

**Préférence** : PHP >= 7.4

#### Utilisation

Dans le dossier **script** , les classes utiles pour le script principal du **script_main.php**. Pour lancer le script :

'''bash
php script_main.php
''''       

#### Documentation

Pour se documenter, voici quelques documentations :
    - [Documentation GLPI Générale](https://glpi-project.org/documentation/)
    - [Documentation API Developper GLPI](https://glpi-developer-documentation.readthedocs.io/en/master/devapi/index.html)
    - [Documentation API GLPI](https://glpi-user-documentation.readthedocs.io/fr/latest/modules/configuration/general/api.html)
