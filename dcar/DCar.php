<?php

namespace DCar;
use DiDom\Document;
use DiDom\Element;
use GuzzleHttp\Client;
use Medoo\Medoo;

class DCar implements DCarInterface
{

    private bool $autoFilter = true;

    private array $banKey = [];

    private array $deArticles = [];

    private array $replace = [];

    private string $imgSrcPrefix = '';

    public function setReplace(array $conf)
    {
        // TODO: Implement RegExpReplace() method.
        $this->replace = $conf;
    }

    public function setBanKeys(array $conf)
    {
        // TODO: Implement BanKeys() method.
        $this->banKey = $conf;
    }

    public function autoFilter(bool $whether)
    {
        // TODO: Implement autoFilter() method.
        $this->autoFilter = $whether;
    }

    public function collectArticles(array $articles, string $jsPathTitle, string $jsPathContent,array $options = ['verify' => false]):int
    {
        // TODO: Implement capArticles() method.

        array_walk($articles,function ($articleUrl,$index,array $options)use ($jsPathTitle,$jsPathContent){
            $client = new Client();
            $response = $client->get($articleUrl,$options,);
            $dom = new Document($response->getBody()->getContents());
            $this->deImg($dom);
            if(count($title = $dom->find($jsPathTitle))> 0 and count($content = $dom->find($jsPathContent))> 0){
                //代码
                if ($this->autoFilter and(empty($title[0]->text()) or empty($content[0]->innerHtml()))){
                    return;
                }

                foreach ($this->banKey as $key){
                    if (strpos($title[0]->text(),$key) !== false or strpos($content[0]->innerHtml(),$key) !== false){
                        return;
                    }
                }

                foreach ($this->replace as $key=>$value){
                    $title = str_replace($key,$value,$title[0]->text());
                    $content = str_replace($key,$value,$content[0]->innerHtml());

                }
                $date = date("Y-m-d H:i:s");
                $this->deArticles[] = [
                    'post_title'=>$title,
                    'post_content' => $content,
                    'post_author'=>1,
                    'post_status'=>"draft",
                    'post_excerpt'=>'',
                    'comment_status'=>'closed',
                    'ping_status'=>'closed',
                    'post_password'=>'',
                    'post_name'=>'',
                    'to_ping'=>'',
                    'pinged'=>'',
                    'post_date'=>$date,
                    'post_date_gmt'=>$date,
                    'post_modified' =>$date,
                    'post_modified_gmt' =>$date,
                    'post_content_filtered'=>'',
                    'post_parent'=>0,
                    'guid'=>'',
                    'menu_order'=>0,
                    'post_type'=>'post',
                    'post_mime_type'=>'',
                    'comment_count'=>0
                ];
            }

        },$options);
        return count($this->deArticles);
    }

    public function uploadArticles($dbConf): int
    {
        // TODO: Implement uploadArticles() method.
        if (count($this->deArticles) <= 0){
            return 0;
        }
        $medoo = new Medoo($dbConf);

        $doc = $medoo->insert('wp_posts',$this->deArticles);
        return $doc->rowCount();
    }

    private function deImg(Document $doc)
    {
            if (empty($this->imgSrcPrefix)){
                return;
            }
            $nodes =  $doc->find('img');
            foreach ($nodes as $node){
                $node->setAttribute('src',$this->imgSrcPrefix.$node->getAttribute('src'));
            }
    }

    public function setImgSrcDe(string $prefix)
    {
        // TODO: Implement setImgSrcDe() method.
        $this->imgSrcPrefix = $prefix;
    }
}