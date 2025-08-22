<?php

namespace app\core;

class Router
{

    public Request $request;
    protected array $routes = [];

    public function __construct(Request $request)
    {
        $this->request = $request;
    }
    public function get(string $path, callable | string $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    // handle to show view files -> started from here
    public function renderView(string $view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    protected function layoutContent()
    {
        
        /**
         * Renders the main layout view and returns its output as a string.
         * Uses output buffering to capture the rendered HTML content from the layout file
         * without sending it directly to the browser. This allows flexible composition
         * of views and templates within the application.
         *
         * @return string Rendered HTML content of the main layout.
         */

        ob_start();
        include_once Application::$ROOT_DIR . '/views/layouts/main.php';
        return ob_get_clean();
    }

    protected function renderOnlyView(string $view)
    {
        ob_start();
        include_once Application::$ROOT_DIR . '/views/' . $view . '.php';
        return ob_get_clean();
    }

    // handle to show view files - ended here

    public function resolve()
    {
        $path = $this->request->getPath();
        $method = $this->request->getMethod();
        // dd($this->routes);
        $callback = $this->routes[$method][$path] ?? false;

        if (!$callback) {
            http_response_code(404);
            return 'Not Found';
        }

        // render the view if the callback is a string
        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        // otherwise run the callback function
        return call_user_func($callback);
    }
}
