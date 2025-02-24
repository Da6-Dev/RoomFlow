<?php

class core
{
    public function run($Routes)
    {
        $url = '/';
        $url .= isset($_GET['url']) ? $_GET['url'] : '';

        ($url != '/') ? $url = rtrim($url, '/') : $url;

        foreach ($Routes as $path => $controller) {
            $pattern = '#^' . preg_replace('/@id/', '(\d+)', $path) . '$#';

            if (preg_match($pattern, $url, $matches)) {
                array_shift($matches); // Remove o primeiro item, que é a URL completa

                [$currentController, $action] = explode('@', $controller);

                require_once __DIR__ . "/../controllers/$currentController.php";

                $newController = new $currentController();

                // Passar parâmetros se existirem
                if (!empty($matches)) {
                    $newController->$action(...$matches);
                } else {
                    $newController->$action($matches);
                }

                return; // Interrompe a execução para evitar múltiplas verificações
            }
        }



        // Se não encontrou a rota, exibir a página 404
        require_once __DIR__ . "/../controllers/NotFoundController.php";
        $controller = new NotFoundController();
        $controller->index();
    }
}
