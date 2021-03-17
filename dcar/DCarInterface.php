<?php


namespace DCar;


interface DCarInterface
{

    public function setReplace(array $conf);

    public function setBanKeys(array $conf);

    public function collectArticles(array $articles,string $jsPathTitle,string $jsPathContent);

    public function uploadArticles($dbConf):int;

    public function autoFilter(bool $whether);

    public function setImgSrcDe(string $prefix);

}