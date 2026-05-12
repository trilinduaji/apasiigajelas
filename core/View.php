<?php
/**
 * SIPEDO - View Renderer
 */
class View {
    private static string $viewPath;

    public static function setPath(string $path): void {
        self::$viewPath = rtrim($path, '/');
    }

    /**
     * Render a view file with data
     * @param string $view  e.g. 'landing/index', 'auth/login'
     * @param array  $data  Variables extracted into view scope
     */
    public static function render(string $view, array $data = []): void {
        $file = self::$viewPath . '/' . $view . '.php';
        if (!file_exists($file)) {
            throw new \RuntimeException("View not found: {$file}");
        }
        extract($data, EXTR_SKIP);
        require $file;
    }
}
