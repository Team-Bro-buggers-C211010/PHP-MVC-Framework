<?php

namespace app\core;

class Router
{

    public Request $request;
    public Response $response;
    protected array $routes = [];

    public function __construct(Request $request, Response $response)
    {
        $this->request = $request;
        $this->response = $response;
    }
    public function get(string $path, callable | string $callback)
    {
        $this->routes['GET'][$path] = $callback;
    }

    public function post(string $path, callable | string $callback)
    {
        $this->routes['POST'][$path] = $callback;
    }

    // handle to show view files -> started from here
    public function renderView(string $view)
    {
        $layoutContent = $this->layoutContent();
        $viewContent = $this->renderOnlyView($view);

        return str_replace('{{content}}', $viewContent, $layoutContent);
    }

    public function renderContent($viewContent) 
    {
        $layoutContent = $this->layoutContent();
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
            $this->response->setStatusCode(404);
            return $this->renderView("_404");
        }

        // render the view if the callback is a string
        if (is_string($callback)) {
            return $this->renderView($callback);
        }

        // otherwise run the callback function
        return call_user_func($callback);
    }
}
