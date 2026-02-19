<?php

require_once 'BookController.php';

// Récupère l'URI de la requête et retire le nom du script
$uri = $_SERVER['REQUEST_URI'];
$uri = parse_url($uri, PHP_URL_PATH);
$uri = str_replace('/index.php', '', $uri);

// Extrait l'ID numérique en fin d'URI (ex: /5 → id = 5)
$id = null;
if (preg_match('/\/(\d+)$/', $uri, $matches)) {
    $id = (int)$matches[1];
}

// Délègue le traitement au contrôleur
$controller = new BookController();
$controller->handleRequest($id);
