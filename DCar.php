<?php
require "vendor/autoload.php";




$articles = 'https://www.siammm.cn/archives/292
https://www.siammm.cn/archives/291
https://www.siammm.cn/archives/289
https://www.siammm.cn/archives/288
https://www.siammm.cn/archives/285
https://www.siammm.cn/archives/283
https://www.siammm.cn/archives/267
https://www.siammm.cn/archives/265
https://www.siammm.cn/archives/263
https://www.siammm.cn/archives/256
https://www.siammm.cn/archives/255
https://www.siammm.cn/archives/254
https://www.siammm.cn/archives/251
https://www.siammm.cn/archives/248
https://www.siammm.cn/archives/242
https://www.siammm.cn/archives/240
https://www.siammm.cn/archives/239
https://www.siammm.cn/archives/236
https://www.siammm.cn/archives/161
https://www.siammm.cn/archives/221
https://www.siammm.cn/archives/174';

$articles = explode(PHP_EOL,$articles);


/**
 * 标题选择器
 */
$jsPathTitle ='div.article-title > a';
/**
 * 内容选择器
 */
$jsPathContent = '.article-body';

/**
 * 要过滤替换的 文字
 */
$replaceStr = [
    '被替换的文字'=>'要替换的文字',
];

/**
 * 删除 指定内容
 */
$jsPathDelS = [
    '.article-copyright',
    '.article-like',
    '.article-share',
    '.article-meta'
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
$DCar->setJsPathDelS($jsPathDelS);
$DCar->setImgSrcDe($imgSrcPrefix);
$count =  $DCar->collectArticles($articles,$jsPathTitle,$jsPathContent);//开始采集
echo "采集完毕 采集数量：{$count}".PHP_EOL;
$count =$DCar->uploadArticles($dbInfo);//开始上传
echo "上传完毕 上传数量：{$count}".PHP_EOL;
//需要判断 图片的路径是否是全路径



