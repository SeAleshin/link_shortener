<?php

namespace Links\App;

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

class View {

    private object $twig;

    public function __construct()
    {
        $loader = new FilesystemLoader('pages');
        $this->twig = new Environment($loader);
    }

    /**
     * @param string
     * @param ?array
     * @return void
     */

    public function getPage(string $path, ?array $data = null) :void
    {
        if ($data !== null) {
            echo $this->twig->render($path, $data);
            die();
        } else {
            echo $this->twig->render($path);
            die();
        }
    }

    /**
     * @param array|string
     * @return void
     */

    public function showException(array|string $exception) :void
    {
        echo $this->twig->render('error.twig', $exception);
        die();
    }
}