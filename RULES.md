Créer une API RESTFul en PHP 8 sur base d’un squelette Mezzio (avec Composer)

Cette API proposera 2 services (CRUD) :
- Gestion des utilisateurs (/users)
- Gestion des livres détenus par ces utilisateurs (/books)
- La cardinalité est qu’un livre ne peut être détenu que par un utilisateur et peut ne pas être détenu.
- Un utilisateur contient identifiant unique (UUID) nom, prénom, email (unique).
- Un livre contient un identifiant unique (UUID), un titre, un nom d'auteur et une année.

- Tu peux choisir la couche de persistence (KISS : SQLite + PDO suffira !)
- L’API proposera du JSON uniquement (I/O)
- Tests unitaires of course :)

Lecture : [Mezzio](https://docs.mezzio.dev/mezzio)

Le but est de voir comment tu abordes la structure d’un projet API driven, l’écriture en PSR12, le découplage, DRY, OOP, SOLID, …
Tu peux créer un repo Github et m'en transmettre l’URL quand tu auras fini. Mardi maximum, lundi idéal.

Bonus :
- Dockerisation de l'environnement
- validation des données en input (avec Laminas InputFilter)
- log (PSR3) des opérations d’écritures.
