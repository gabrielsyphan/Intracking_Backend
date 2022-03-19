<?php

namespace Source\App;

use CoffeeCode\Router\Router;
use League\Plates\Engine;
use Source\Models\Email;
use Source\Models\User;

/**
 * Class Web
 *
 * @package Source\App
 */
class Web
{
    /**
     * @var Router
     */
    private $router;

    /**
     * @var Engine
     */
    private $view;

    /**
     * Web constructor.
     */
    public function __construct($router)
    {
        $this->router = $router;
        $this->view = Engine::create(THEMES, 'php');
        $this->view->addData([
            'router' => $router,
        ]);

        setlocale(LC_TIME, 'pt_BR', 'pt_BR.utf-8', 'pt_BR.utf-8', 'portuguese');
        date_default_timezone_set('America/Sao_Paulo');
    }

    /**
     * @return void
     * Home page
     */
    public function home(): void
    {
        echo $this->view->render('home', [
            'title' => "Home " . SITE
        ]);
    }

    /**
     * @return void
     * Sign Out
     */
    public function logout(): void
    {
        if(!empty($_SESSION['user'])){
            unset($_SESSION['user']);
        }

        $this->router->redirect('web.home');
    }
}
