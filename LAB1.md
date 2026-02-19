# 📚 Lab 1 : API REST de Gestion de Livres

## 🎯 Objectif

Créer une API REST complète en PHP pour gérer une bibliothèque de livres avec les opérations CRUD (Create, Read, Update, Delete).

## ⏱️ Durée estimée

1-3 heures

## 📋 Ce que vous allez apprendre

- Créer une API REST avec PHP
- Gérer les méthodes HTTP (GET, POST, PUT, DELETE)
- Valider les données entrantes
- Retourner des réponses JSON
- Utiliser les bons codes de statut HTTP
- Structurer un projet API proprement
- Tester une API avec cURL

---

## 🏗️ Structure du projet à créer

```
e89_lab1/
├── api/
│   ├── index.php           # Point d'entrée et routing
│   ├── BookController.php  # Gestion des requêtes
│   ├── BookModel.php       # Accès aux données
│   └── .htaccess          # Réécriture d'URL (optionnel)
├── data/
│   └── books.json         # Stockage des données (créé automatiquement)
└── README.md              # Documentation
```

---

## 📝 Spécifications de l'API

### Modèle de données : Book

Chaque livre doit contenir :
- `id` : Identifiant unique (entier, auto-généré)
- `title` : Titre du livre (string, requis)
- `author` : Auteur du livre (string, requis)
- `isbn` : Numéro ISBN (string, optionnel)
- `year` : Année de publication (entier, optionnel)

**Exemple de livre :**
```json
{
  "id": 1,
  "title": "Clean Code",
  "author": "Robert C. Martin",
  "isbn": "978-0132350884",
  "year": 2008
}
```

---

## 🔌 Endpoints à implémenter

### 1. GET /api/books
**Description :** Récupérer tous les livres

**Réponse attendue (200 OK) :**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "title": "Clean Code",
      "author": "Robert C. Martin",
      "isbn": "978-0132350884",
      "year": 2008
    }
  ],
  "message": "Books retrieved successfully"
}
```

---

### 2. GET /api/books/{id}
**Description :** Récupérer un livre spécifique

**Réponse attendue (200 OK) :**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Clean Code",
    "author": "Robert C. Martin",
    "isbn": "978-0132350884",
    "year": 2008
  },
  "message": "Book retrieved successfully"
}
```

**Réponse si livre non trouvé (404 Not Found) :**
```json
{
  "success": false,
  "message": "Book not found"
}
```

---

### 3. POST /api/books
**Description :** Créer un nouveau livre

**Corps de la requête :**
```json
{
  "title": "The Pragmatic Programmer",
  "author": "Andrew Hunt",
  "isbn": "978-0135957059",
  "year": 2019
}
```

**Réponse attendue (201 Created) :**
```json
{
  "success": true,
  "data": {
    "id": 2,
    "title": "The Pragmatic Programmer",
    "author": "Andrew Hunt",
    "isbn": "978-0135957059",
    "year": 2019
  },
  "message": "Book created successfully"
}
```

**Réponse si validation échoue (400 Bad Request) :**
```json
{
  "success": false,
  "message": "Validation failed",
  "errors": {
    "title": "Title is required",
    "author": "Author is required"
  }
}
```

---

### 4. PUT /api/books/{id}
**Description :** Mettre à jour un livre existant

**Corps de la requête :**
```json
{
  "title": "Clean Code - Edition 2",
  "author": "Robert C. Martin",
  "isbn": "978-0132350884",
  "year": 2020
}
```

**Réponse attendue (200 OK) :**
```json
{
  "success": true,
  "data": {
    "id": 1,
    "title": "Clean Code - Edition 2",
    "author": "Robert C. Martin",
    "isbn": "978-0132350884",
    "year": 2020
  },
  "message": "Book updated successfully"
}
```

---

### 5. DELETE /api/books/{id}
**Description :** Supprimer un livre

**Réponse attendue (200 OK) :**
```json
{
  "success": true,
  "message": "Book deleted successfully"
}
```

---

## ✅ Règles de validation

### Lors de la création (POST) :
- `title` : **Obligatoire**, non vide
- `author` : **Obligatoire**, non vide
- `isbn` : Optionnel, format valide (10-17 caractères avec chiffres et tirets)
- `year` : Optionnel, entre 1000 et année actuelle

### Lors de la mise à jour (PUT) :
- Mêmes règles mais les champs sont optionnels (on met à jour seulement ce qui est fourni)

---

## 🔢 Codes de statut HTTP à utiliser

| Code | Situation |
|------|-----------|
| 200 OK | GET, PUT, DELETE réussis |
| 201 Created | POST réussi |
| 400 Bad Request | Erreur de validation |
| 404 Not Found | Ressource non trouvée |
| 405 Method Not Allowed | Méthode HTTP non supportée |

---

## 🚀 Étapes de réalisation

### Étape 1 : Créer la structure (10 min)

```bash
mkdir -p e89_lab1/api
mkdir -p e89_lab1/data
cd e89_lab1
```

### Étape 2 : Créer BookModel.php (30 min)

Créer la classe qui gère le stockage des données dans `data/books.json`.

**Méthodes à implémenter :**
- `getAll()` : Retourne tous les livres
- `getById($id)` : Retourne un livre par son ID
- `create($data)` : Crée un nouveau livre
- `update($id, $data)` : Met à jour un livre
- `delete($id)` : Supprime un livre

**Conseils :**
- Utilisez `file_get_contents()` et `file_put_contents()` pour lire/écrire le JSON
- Générez les IDs automatiquement (max ID + 1)
- Retournez `null` si un livre n'existe pas

### Étape 3 : Créer BookController.php (40 min)

Créer la classe qui gère les requêtes HTTP et la validation.

**Méthodes à implémenter :**
- `handleRequest()` : Route vers la bonne méthode selon HTTP method
- `index()` : GET tous les livres
- `show($id)` : GET un livre
- `store()` : POST créer un livre
- `update($id)` : PUT mettre à jour
- `destroy($id)` : DELETE supprimer
- `validate($data, $isUpdate = false)` : Valider les données

**Conseils :**
- Utilisez `$_SERVER['REQUEST_METHOD']` pour la méthode HTTP
- Utilisez `json_decode(file_get_contents('php://input'), true)` pour lire le body
- Utilisez `http_response_code()` pour définir le code de statut
- Utilisez `header('Content-Type: application/json')` pour les réponses JSON

### Étape 4 : Créer index.php (20 min)

Point d'entrée de l'API qui :
- Parse l'URL pour extraire l'ID
- Instancie le controller
- Gère les erreurs globales

**Exemple de routing :**
```
/api/books      → index()
/api/books/1    → show(1)
```

### Étape 5 : Tester avec cURL (30 min)

Démarrer le serveur :
```bash
cd api
php -S localhost:8000
```

**Tests à effectuer :**

```bash
# 1. Créer un livre
curl -X POST http://localhost:8000/index.php \
  -H "Content-Type: application/json" \
  -d '{"title":"Clean Code","author":"Robert C. Martin","isbn":"978-0132350884","year":2008}'

# 2. Lister tous les livres
curl http://localhost:8000/index.php

# 3. Récupérer le livre ID 1
curl http://localhost:8000/index.php/1

# 4. Mettre à jour le livre ID 1
curl -X PUT http://localhost:8000/index.php/1 \
  -H "Content-Type: application/json" \
  -d '{"title":"Clean Code - Updated"}'

# 5. Supprimer le livre ID 1
curl -X DELETE http://localhost:8000/index.php/1

# 6. Tester la validation (doit échouer)
curl -X POST http://localhost:8000/index.php \
  -H "Content-Type: application/json" \
  -d '{"title":""}'
```

---

## ✅ Checklist de validation

Vérifiez que :

- [ ] GET /api/books retourne tous les livres (200)
- [ ] GET /api/books/1 retourne le livre 1 (200)
- [ ] GET /api/books/999 retourne 404
- [ ] POST avec données valides crée un livre (201)
- [ ] POST sans title retourne 400 avec erreur
- [ ] POST sans author retourne 400 avec erreur
- [ ] PUT avec données valides met à jour (200)
- [ ] PUT sur ID inexistant retourne 404
- [ ] DELETE supprime le livre (200)
- [ ] DELETE sur ID inexistant retourne 404
- [ ] Toutes les réponses sont en JSON
- [ ] Les codes HTTP sont corrects

---

## 🎁 En plus

Si vous avez terminé, ajoutez ces fonctionnalités :

### 1. Filtrage et recherche
```bash
GET /api/books?author=Martin
GET /api/books?year=2008
GET /api/books?search=clean
```

### 2. Pagination
```bash
GET /api/books?page=1&limit=10
```

### 3. Tri
```bash
GET /api/books?sort=title&order=asc
```

### 4. Validation ISBN avancée
- Vérifier le format ISBN-10 ou ISBN-13
- Calculer le checksum

### 5. Documentation README.md
- Documenter tous les endpoints
- Ajouter des exemples de requêtes
- Expliquer comment lancer le projet

---

## 📚 Ressources utiles

- [PHP $_SERVER](https://www.php.net/manual/fr/reserved.variables.server.php)
- [HTTP Status Codes](https://httpstatuses.com/)
- [JSON en PHP](https://www.php.net/manual/fr/book.json.php)
- [cURL Documentation](https://curl.se/docs/manual.html)

---

## 💡 Conseils

1. **Commencez simple** : Faites d'abord fonctionner GET, puis POST, puis le reste
2. **Testez souvent** : Après chaque méthode, testez avec cURL
3. **Gérez les erreurs** : Utilisez try/catch pour les erreurs JSON
4. **Soyez cohérent** : Même format de réponse partout
5. **Commentez** : Expliquez les parties complexes

---

**Bon courage ! 🚀**
