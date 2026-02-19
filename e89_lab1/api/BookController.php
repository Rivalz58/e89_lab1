<?php

require_once 'BookModel.php';

// Contrôleur REST pour la ressource Book
// Gère le routage des requêtes HTTP vers les bonnes actions
class BookController {
    private $model;

    public function __construct() {
        $this->model = new BookModel();
    }

    // Point d'entrée : route selon la méthode HTTP et la présence d'un ID
    public function handleRequest($id = null) {
        header('Content-Type: application/json');
        header('X-Content-Type-Options: nosniff');
        header('X-Frame-Options: DENY');
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'GET':
                if ($id) {
                    $this->show($id);
                } else {
                    $this->index();
                }
                break;
            case 'POST':
                $this->store();
                break;
            case 'PUT':
                $this->update($id);
                break;
            case 'DELETE':
                $this->destroy($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(['success' => false, 'message' => 'Method not allowed']);
        }
    }

    // GET / — retourne tous les livres
    public function index() {
        $books = $this->model->getAll();
        echo json_encode([
            'success' => true,
            'data' => $books,
            'message' => 'Books retrieved successfully'
        ]);
    }

    // GET /{id} — retourne un livre par son ID
    public function show($id) {
        $book = $this->model->getById($id);
        if ($book) {
            echo json_encode([
                'success' => true,
                'data' => $book,
                'message' => 'Book retrieved successfully'
            ]);
        } else {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Book not found']);
        }
    }

    // POST / — crée un nouveau livre
    public function store() {
        $raw = file_get_contents('php://input', false, null, 0, 1048576); // limite à 1 Mo
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            return;
        }
        $errors = $this->validate($data);

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }

        $book = $this->model->create($data);
        http_response_code(201);
        echo json_encode([
            'success' => true,
            'data' => $book,
            'message' => 'Book created successfully'
        ]);
    }

    // PUT /{id} — met à jour un livre existant (champs partiels acceptés)
    public function update($id) {
        $book = $this->model->getById($id);
        if (!$book) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Book not found']);
            return;
        }

        $raw = file_get_contents('php://input', false, null, 0, 1048576); // limite à 1 Mo
        $data = json_decode($raw, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid JSON']);
            return;
        }
        $errors = $this->validate($data, true);

        if (!empty($errors)) {
            http_response_code(400);
            echo json_encode([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $errors
            ]);
            return;
        }

        $updated = $this->model->update($id, $data);
        echo json_encode([
            'success' => true,
            'data' => $updated,
            'message' => 'Book updated successfully'
        ]);
    }

    // DELETE /{id} — supprime un livre
    public function destroy($id) {
        $book = $this->model->getById($id);
        if (!$book) {
            http_response_code(404);
            echo json_encode(['success' => false, 'message' => 'Book not found']);
            return;
        }

        $this->model->delete($id);
        echo json_encode([
            'success' => true,
            'message' => 'Book deleted successfully'
        ]);
    }

    // Valide les données entrantes
    private function validate($data, $isUpdate = false) {
        $errors = [];

        if (!$isUpdate) {
            if (empty($data['title'])) {
                $errors['title'] = 'Title is required';
            }
            if (empty($data['author'])) {
                $errors['author'] = 'Author is required';
            }
        }

        // ISBN : 10 à 13 chiffres (tirets/espaces tolérés)
        if (isset($data['isbn']) && !empty($data['isbn'])) {
            if (strlen(preg_replace('/[^0-9X]/', '', $data['isbn'])) < 10 || strlen($data['isbn']) > 17) {
                $errors['isbn'] = 'Invalid ISBN format';
            }
        }

        // Année : entre 1000 et l'année courante
        if (isset($data['year']) && !empty($data['year'])) {
            $year = (int)$data['year'];
            if ($year < 1000 || $year > date('Y')) {
                $errors['year'] = 'Year must be between 1000 and ' . date('Y');
            }
        }

        return $errors;
    }
}
