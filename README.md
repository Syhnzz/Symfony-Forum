##Forum Symfony – Projet Web PHP SYMFONY :

Application web développée sous Symfony permettant la gestion d’un: forum complet.

## Technologies
- PHP 8
- Symfony 6
- Twig
- Doctrine ORM
- MySQL
- Composer

## Fonctionnalités
- Inscription / Connexion utilisateur
- Création de sujets
- Commentaires / réponses
- Gestion des catégories
- Système de rôles (admin / utilisateur) si existant
- Templates avec Twig
- Validation des formulaires

## Structure du projet
/src
├── Controller
├── Entity
├── Repository
├── Form
└── Security


## Installation

1. Cloner le dépôt :
git clone https://github.com/Syhnzz/Symfony-Forum.git cd Symfony-Forum

2. Installer les dépendances :
composer install


3. Configurer `.env` :
DATABASE_URL="mysql://root:@127.0.0.1:3306/forum"

4. Créer la base :
php bin/console doctrine:database:create php bin/console doctrine:migrations:migrate


5. Lancer le serveur :
symfony server:start


## Captures d’écran


## Ce que j’ai appris
- Développement d’un site web complet avec Symfony
- Architecture MVC
- Routing, formulaires, contrôleurs
- Doctrine ORM & migrations
- Sécurisation d’accès utilisateurs
