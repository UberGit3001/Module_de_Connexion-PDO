-- moduleconnexion.sql
-- Crée la BDD et la table utilisateurs, insère un admin (voir note)
CREATE DATABASE IF NOT EXISTS moduleconnexion CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE moduleconnexion;

DROP TABLE IF EXISTS utilisateurs;
CREATE TABLE utilisateurs (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  login VARCHAR(255) NOT NULL UNIQUE,
  prenom VARCHAR(255) NOT NULL,
  nom VARCHAR(255) NOT NULL,
  password VARCHAR(255) NOT NULL,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- NOTE:
-- Pour faciliter l'exercice, on insère ici admin avec mot de passe 'admin' en clair.
-- Le code PHP fournit prend en charge les mots de passe hachés (pour les nouveaux comptes).
-- Si tu veux, tu peux remplacer la valeur ci-dessous par un hash obtenu via password_hash('admin', PASSWORD_DEFAULT)
INSERT INTO utilisateurs (login, prenom, nom, password)
VALUES ('admin', 'admin', 'admin', 'admin');
