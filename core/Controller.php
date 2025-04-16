<?php

class Controller {
    // Load the view file and pass the data to it
    public function view($view, $data = []) {
        extract($data);  // Convert array keys to variables
        require_once __DIR__ . '/../app/views/' . $view . '.php';
    }
}
?>
