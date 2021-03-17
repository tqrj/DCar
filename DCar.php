<?php
require "vendor/autoload.php";

/*$str = '<h1></h1><h3>222</h3>>';
$dom = new \DiDom\Document($str);
$node = $dom->find('h1')[0];
$node->setAttribute('src','121');
echo $dom->html();

return*/
/**
 * 要采集的文章链接放在这个数组里面
 */
$articles = [
    'https://zixuephp.net/article-517.html',
    'https://zixuephp.net/article-516.html',
    'https://zixuephp.net/article-504.html',
    'https://zixuephp.net/article-501.html',
    'https://zixuephp.net/article-516.html',
];


/**
 * 标题选择器
 */
$jsPathTitle ='body > div.article > div > div:nth-child(2) > div > div > h1';
/**
 * 内容选择器
 */
$jsPathContent = '#pcdetails';

/**
 * 要过滤替换的 文字
 */
$replaceStr = [
    '被替换的文字'=>'要替换的文字',
];


/**
 * 过滤关键词 有这些关键词的不采集
 */
$banKey = [
    'vpn',
];

/**
 * 图片如果不是全路径 无法显示 可以在这里填上前缀域名。
 */
$imgSrcPrefix = 'https://zixuephp.net/';


/**
 * 空标题和空内容自动过滤不采集进数据库
 */
$autoFilter = true;

/**
 * 数据库信息
 */
$dbInfo = [
    'database_type' => 'mysql',
    'database_name' => 'yuxyi_com',
    'server' => '103.79.54.11',
    'username' => 'yuxyi_com',
    'password' => 'Kw2YirMRceDeiH3a'
];

$DCar = new \DCar\DCar();
$DCar->autoFilter(true);//自动过滤 空标题 空内容
$DCar->setBanKeys($banKey);//过滤关键词
$DCar->setReplace($replaceStr);//设置替换指定内容
$DCar->setImgSrcDe($imgSrcPrefix);
$count =  $DCar->collectArticles($articles,$jsPathTitle,$jsPathContent);//开始采集
echo "采集完毕 采集数量：{$count}".PHP_EOL;
$count =$DCar->uploadArticles($dbInfo);//开始上传
echo "上传完毕 上传数量：{$count}".PHP_EOL;
//需要判断 图片的路径是否是全路径



