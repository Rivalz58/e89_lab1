# Books API

API REST en PHP pour gérer une bibliothèque de livres (CRUD), avec stockage JSON.

## Lancer le projet

```bash
cd api
php -S localhost:8080
```

## Endpoints

| Méthode | URL | Description |
|---------|-----|-------------|
| GET | `/index.php` | Liste tous les livres |
| GET | `/index.php/{id}` | Récupère un livre |
| POST | `/index.php` | Crée un livre |
| PUT | `/index.php/{id}` | Met à jour un livre |
| DELETE | `/index.php/{id}` | Supprime un livre |

## Exemples

```bash
# Lister les livres
curl http://localhost:8080/index.php

# Créer un livre
curl -X POST http://localhost:8080/index.php \
  -H "Content-Type: application/json" \
  -d '{"title":"1984","author":"George Orwell","year":1949}'

# Mettre à jour
curl -X PUT http://localhost:8080/index.php/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Nouveau titre"}'

# Supprimer
curl -X DELETE http://localhost:8080/index.php/1
```

## Structure

```
e89_lab1/
├── api/
│   ├── index.php           # Point d'entrée et routing
│   ├── BookController.php  # Gestion des requêtes HTTP
│   └── BookModel.php       # Accès aux données (books.json)
└── data/
    └── books.json          # Stockage des données
```

## Modèle de données

```json
{
  "id": 1,
  "title": "Le Petit Prince",
  "author": "Saint-Exupery",
  "isbn": "9782070408504",
  "year": 1943
}
```

`title` et `author` sont obligatoires à la création. `isbn` et `year` sont optionnels.
