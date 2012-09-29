<?php

error_reporting(0);
set_time_limit(120);

######################################################
# @"Checka" v0.1 beta                                #
# @2011 by Mekan Shved Bayryew                       #
# @izotvorec@namba.kg                                #
######################################################
 
Header("Content-type: image/png"); 
$im = ImageCreateFromGif('img/yeah.gif');
ImageGif($im);
ImageDestroy($im);

$uin   = "478261996";       //UIN
$phone = "996555214751";    //Phone number, if you want to send comments directly to your phone

$host = gethostbyaddr($_SERVER['REMOTE_ADDR']);
$host = "http://".$host.$_SERVER['REQUEST_URI'];
$arg  = parse_url($host, PHP_URL_FRAGMENT);

if ( !empty($arg['fragment']) ) {
    $bid = $arg['fragment']
}

if (isset($_SERVER['HTTP_REFERER'])) {
    $ref = $_SERVER['HTTP_REFERER'];
    if ($ref === "{$host}{$bid})" {
        $content = get_page_content($ref);
        if (preg_match("/<title>(.*)<\/title>/siU", $content, $m)) {
            $title = $m[1];
            $title = preg_replace('/\s\s/', '', $title);
        }
        if (!file_exists('blog.txt')) {
            file_put_contents('blog.txt', $content);
        } elseif(filesize($content) !== filesize('blog.txt')) {
            $commentData = get_comment_data($content);
            send($commentData);
        }
    }
}


function get_page_content($url)
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_USERAGENT, 'PHP comment viewer bot')

    $result = curl_exec($ch);

    curl_close($ch);

    return $result;
}


function get_comment_data($content)
{
    require_once 'html_dom.php';
    
    $content = file_get_html($content);
    $parsed  = array();

    foreach($content->find('div.commentlist') as $comments)
    {
       $count = count($comments);
       $parsed['username'] = trim($comments->find('a.username', $content)->plaintext);
       $parsed['comment']  = trim($comments->find('p', $content)->plaintext);       
    }

    $content->clear();
    
    unset($content);

    return $parsed;
}


function send($commentData)
{
    global $uin, $phone;

    require_once 'WebIcqLite.class.php';

    foreach ($commentData as $data) {
        $user    = $data['username'];
        $comment = $data['comment'];
        $comment = preg_replace('/&nbsp;/', '', $comment);
    }

    $comment = "{$phone}\r\n'{$comment}' in '{$title}' by '{$user}'";
    $comment = iconv("UTF-8", "cp1251", $comment);

    define('UIN', 478261996);           //Бот uin
    define('PASSWORD', 'password');     //Бот пасс

    $icq = new WebIcqLite();

    if($icq->connect(UIN, PASSWORD)) {
        $icq->send_message($uin, $comment);
    }

    $icq->disconnect();
}

exit;