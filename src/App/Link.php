<?php

namespace Links\App;

use Links\Database\Connect;
use Links\App\View;

class Link {

    /**
     * @return object
     */

    private function setConnect() :object
    {
        $connect = new Connect();
        return $connect::getConnect();
    }

    /**
     * @param string 
     * @return object
     */

    private function createShortLink() :string
    {
        $randomSymbols = bin2hex(random_bytes(10));

        if ($this->checkSymbolsInDB($randomSymbols) === $randomSymbols) {
            self::createShortLink();
        }

        return $randomSymbols;
    }

    /**
     * @param string
     * @return bool
     */

    private function matchUserLink(string $link) :bool
    {
        if (strpos($link, 'http://') !== false || strpos($link, 'https://') !== false)
        {
            return true;
        }

        return false;
    }

    /**
     * @param string
     * @return mixed
     */

    public function saveLink(string $link) :mixed
    {
        if (!$this->matchUserLink($link)) {
            echo (new View())->getPage('main.twig', ['message' => 'Ссылка указана неверно!', 'alert' => true]);
        }

        $checkLink = $this->checkLink($link);

        if ($checkLink !== false) {
            return $checkLink;
        }

        $randomSymbols = $this->createShortLink();

        $connect = $this->setConnect();

        $sql = $connect->prepare('INSERT INTO users_links (user_link, short_link) VALUES (?, ?);');
        $sql->execute([$link, $randomSymbols]);

        return $randomSymbols;
    }

    /**
     * @param string
     * @return mixed
     */

    private function checkLink(string $link) :mixed
    {
        $connect = $this->setConnect();

        $sql = $connect->prepare('SELECT user_link, short_link FROM users_links WHERE user_link = ?');
        $sql->execute([$link]);
        $res = $sql->fetch();

        if ($res["user_link"] == $link) {
            return $res["short_link"];
        }

        return false;
    }

    private function checkSymbolsInDB(string $randomSymbols)
    {
        $connect = $this->setConnect();

        $sql = $connect->prepare('SELECT short_link FROM users_links WHERE short_link = ?');
        $sql->execute([$randomSymbols]);
        $res = $sql->fetch();
        
        if ($res["short_link"] === $randomSymbols) {
            return false;
        }

        return true;
    }

    /**
     * @param string
     * @return string|null
     */

    public function findSite(string $randomSymbols) :string|null
    {
        $connect = $this->setConnect();

        $sql = $connect->prepare('SELECT user_link FROM users_links WHERE short_link = ?');
        $sql->execute([$randomSymbols]);
        $res = $sql->fetch();

        if (!isset($res["user_link"])) {
            (new View())->showException(['message' => 'Ссылка указана неверно!']);
        }

        return $res["user_link"];
    }
}