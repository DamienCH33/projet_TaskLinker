# Application Symfony - Gestion de Projets

Application développée avec **Symfony 6** permettant la gestion et la consultation de projets selon les rôles utilisateurs (Admin / Employé).

## ⚙️ Prérequis

- PHP >= 8.2  
- Composer >= 2.5  
- Symfony CLI  
- MySQL 
- Git

## Installation

 1️⃣ Cloner le projet

git clone https://github.com/DamienCH33/projet_TaskLinker.git

 2️⃣ Installer les dépendances PHP

composer install

3️⃣ Configurer l’environnement

Copie le fichier `.env` en `.env.local` :

cp .env .env.local

Dans `.env.local`, modifie la ligne suivante avec tes identifiants MySQL :

DATABASE_URL="mysql://root:motdepasse@127.0.0.1:3306/nom_de_ta_bdd?serverVersion=8.0"

## 🗄️ Base de données

### Créer la base :

symfony console doctrine:database:create

### Lancer les migrations :

symfony console doctrine:migrations:migrate

### Charger les données de test :

symfony console doctrine:fixtures:load

## ▶️ Lancer le serveur

## 🧩 Commandes utiles

| Action | Commande |
|--------|-----------|
| Créer la BDD | `symfony console doctrine:database:create` |
| Lancer les migrations | `symfony console doctrine:migrations:migrate` |
| Charger les fixtures | `symfony console doctrine:fixtures:load` |
| Démarrer le serveur | `symfony serve -d` |
| Voir les routes | `symfony console debug:router` |

## 🧠 Infos techniques

- Symfony  
- Doctrine ORM  
- Twig  
- Bootstrap 
- PHP 8.2  
- MySQL

## 👨‍💻 Auteur

**Damien**  
Développeur Web PHP / Symfony  


