<?php
class Model {
    private $db;

    public function __construct() {
        $dbPath = __DIR__ . '/../../db/products.db';
        $dbDir = dirname($dbPath);
        if (!is_dir($dbDir)) {
            mkdir($dbDir, 0755, true);
        }

        $this->db = new PDO('sqlite:' . $dbPath);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->createTable();
        // Seed an empty catalogue, not every page visit.
        $this->db->beginTransaction();
        try {
            if ((int)$this->db->query('SELECT COUNT(*) FROM products')->fetchColumn() === 0) {
                $this->seedData();
            }
            $this->db->commit();
        } catch (Throwable $e) {
            $this->db->rollBack();
            throw $e;
        }
    }

    public function createTable() {
        $sql = "CREATE TABLE IF NOT EXISTS products (
            id              INTEGER PRIMARY KEY,
            title           TEXT NOT NULL,
            description     TEXT,
            model_file      TEXT,
            thumbnail       TEXT,
            has_animation   INTEGER DEFAULT 0,
            animation_label TEXT
        )";
        $this->db->exec($sql);
        return 'Table ready';
    }

    public function seedData() {
        $jsonPath = __DIR__ . '/data.json';
        if (!file_exists($jsonPath)) {
            throw new RuntimeException('Missing catalogue JSON');
        }

        $data = json_decode(file_get_contents($jsonPath), true);
        if (!$data || !isset($data['products']) || !is_array($data['products']) || !$data['products']) {
            throw new RuntimeException('Invalid catalogue JSON');
        }

        $stmt = $this->db->prepare(
            "INSERT INTO products
             (id, title, description, model_file, thumbnail, has_animation, animation_label)
             VALUES
             (:id, :title, :description, :model_file, :thumbnail, :has_animation, :animation_label)"
        );

        foreach ($data['products'] as $p) {
            $stmt->execute([
                ':id'              => $p['id'],
                ':title'           => $p['title'],
                ':description'     => $p['description'],
                ':model_file'      => $p['modelFile'],
                ':thumbnail'       => $p['thumbnail'],
                ':has_animation'   => !empty($p['hasAnimation']) ? 1 : 0,
                ':animation_label' => $p['animationLabel'] ?? ''
            ]);
        }

        return 'Synced ' . count($data['products']) . ' products from data.json';
    }

    public function getAllProducts() {
        $stmt = $this->db->query("SELECT * FROM products ORDER BY id");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getProductById($id) {
        $stmt = $this->db->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute([':id' => intval($id)]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
