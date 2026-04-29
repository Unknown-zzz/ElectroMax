<?php
class Controller {
    protected function render(string $view, array $data = []): void {
        extract($data);
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (!file_exists($viewPath)) {
            http_response_code(404);
            echo "<h1>Vista no encontrada: {$view}</h1>";
            return;
        }
        require $viewPath;
    }

    protected function redirect(string $route): void {
        header('Location: ' . APP_URL . '/index.php?r=' . $route);
        exit;
    }

    protected function json(mixed $data, int $status = 200): void {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    protected function isAjax(): bool {
        return isset($_GET['ajax']) && $_GET['ajax'] === '1';
    }
}
