<?php

namespace Links\App;

use Links\App\Link;
use Links\App\View;

class Router
{
    private array $pages = [];

    /**
     * @param string
     * @param string
     * @return void
     */

    public function addRoute(string $url, string $path) :void
    {
        $this->pages[$url] = $path;
    }

    /**
     * @param string
     * @return void
     */

    public function route(string $url) :void
    {
        $path = $this->pages[$url];
        $filedir = 'pages/' . $path;
        $twig = new View();

        if ($url == '/create') {
            $userLink = trim(htmlspecialchars($_POST['link']));

            $save = new Link();
            $link = $save->saveLink($userLink);

            if ($link !== '') {
                $result = 'http://links/site/' . $link;
                echo $twig->getPage($path, ['link' => $result]);
            }
        }


        if (preg_match('/site/', $url)) {
            
            $symbols = trim(htmlspecialchars(mb_substr($url, 6)));

            $link = new Link();
            $site = $link->findSite($symbols);
            header('Location:' . $site);
        }
        
        if (!$path == "" && file_exists($filedir)) {
            echo $twig->getPage($path);
        } else {
            echo $twig->showException(['message' => 'Такой страницы не существует!', 'code' => 404]);
        }
    }
}