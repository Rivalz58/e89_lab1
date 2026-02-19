<?php

class BookModel {
    private $filePath;

    public function __construct() {
        $this->filePath = __DIR__ . '/../data/books.json';
        // Crée le fichier de données s'il n'existe pas encore
        if (!file_exists($this->filePath)) {
            file_put_contents($this->filePath, json_encode([]));
        }
    }

    // Lit et retourne tous les livres depuis le fichier JSON
    private function readData() {
        return json_decode(file_get_contents($this->filePath), true) ?: [];
    }

    // Sauvegarde le tableau de livres dans le fichier JSON
    private function saveData($data) {
        file_put_contents($this->filePath, json_encode($data, JSON_PRETTY_PRINT));
    }

    // Retourne tous les livres
    public function getAll() {
        return $this->readData();
    }

    // Retourne un livre par son ID, ou null s'il n'existe pas
    public function getById($id) {
        $books = $this->readData();
        foreach ($books as $book) {
            if ($book['id'] == $id) {
                return $book;
            }
        }
        return null;
    }

    // Crée un nouveau livre avec un ID auto-incrémenté
    public function create($data) {
        $books = $this->readData();

        // Calcule le prochain ID (max existant + 1)
        $maxId = 0;
        foreach ($books as $book) {
            if ($book['id'] > $maxId) {
                $maxId = $book['id'];
            }
        }

        $newBook = [
            'id' => $maxId + 1,
            'title' => $data['title'],
            'author' => $data['author'],
            'isbn' => $data['isbn'] ?? null,
            'year' => $data['year'] ?? null
        ];
        $books[] = $newBook;
        $this->saveData($books);
        return $newBook;
    }

    // Met à jour les champs fournis d'un livre existant
    public function update($id, $data) {
        $books = $this->readData();
        foreach ($books as &$book) {
            if ($book['id'] == $id) {
                if (isset($data['title']))  $book['title']  = $data['title'];
                if (isset($data['author'])) $book['author'] = $data['author'];
                if (isset($data['isbn']))   $book['isbn']   = $data['isbn'];
                if (isset($data['year']))   $book['year']   = $data['year'];
                $this->saveData($books);
                return $book;
            }
        }
        return null;
    }

    // Supprime un livre par son ID et réindexe le tableau
    public function delete($id) {
        $books = $this->readData();
        foreach ($books as $key => $book) {
            if ($book['id'] == $id) {
                unset($books[$key]);
                $this->saveData(array_values($books));
                return true;
            }
        }
        return false;
    }
}
