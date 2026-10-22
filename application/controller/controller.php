<?php
require_once __DIR__ . '/../model/model.php';

class Controller {
    private $model;

    public function __construct() {
        $this->model = new Model();
    }

    public function apiGetProducts() {
        header('Content-Type: application/json');
        echo json_encode($this->model->getAllProducts());
    }

    public function apiGetProduct() {
        $id = $_GET['id'] ?? null;
        if (!is_string($id) || !ctype_digit($id) || (int)$id < 1 || (string)(int)$id !== $id) {
            http_response_code(400);
            echo json_encode(['error' => 'A positive integer id is required']);
            return;
        }
        $product = $this->model->getProductById($id);

        header('Content-Type: application/json');
        if (!$product) http_response_code(404);
        echo json_encode($product ?: ['error' => 'Product not found']);
    }
}
