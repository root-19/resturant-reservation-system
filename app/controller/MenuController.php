<?php

require_once __DIR__ . '/../models/Menu.php';

class MenuController {
    private $menu;

    public function __construct($pdo) {
        $this->menu = new Menu($pdo);
    }

    public function index() {
        $items = $this->menu->getAll();
        include __DIR__ . '/../views/menu/index.php';
    }

    public function store() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $foodName = $_POST['food_name'];
            $price = $_POST['price'];
            $image = $_FILES['image_path'];
            $description = $_POST['description'];

            if ($image['error'] === UPLOAD_ERR_OK) {
                $targetDir = __DIR__ . '/../../uploads/';
                $fileName = basename($image["name"]);
                $targetFile = $targetDir . time() . "_" . $fileName;
                $webPath = 'uploads/' . time() . "_" . $fileName;

                if (!is_dir($targetDir)) {
                    mkdir($targetDir, 0777, true);
                }

                if (move_uploaded_file($image["tmp_name"], $targetFile)) {
                    $this->menu->create($foodName, $price, $webPath, $description);
                    header("Location: " . $_SERVER['REQUEST_URI']); // just refresh the page
                    exit;
                } else {
                    echo "Failed to upload image.";
                }
            } else {
                echo "Error uploading image.";
            }
        }
    }
}
