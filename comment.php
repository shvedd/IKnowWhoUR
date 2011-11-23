<?php
###################################################
# IKnowWhoUR: Namba Comment Sender v0.1 beta      #
# © 2011 by Mekan Shved Bayryew                   #
# shved(at)xakep(dot)ru                           #
###################################################
 
 Header("Content-type: image/png"); 
 $im = ImageCreateFromGif('img/yeah.gif');
 ImageGif($im);
 ImageDestroy($im);
  
 $sendto = "190739303";     //ICQ номер на который слать мессаги
 
 $ip     = $_SERVER['REMOTE_ADDR'];
 $id     = 32079;
 $url    = "http://blogs.namba.kg/post.php?id=$id";
 /*
 $host   = gethostbyaddr($_SERVER['REMOTE_ADDR']);
 $url2   = "http://".$host.$_SERVER['REQUEST_URI'];
 $arg    = parse_url($url2,PHP_URL_FRAGMENT);

 if(!empty($arg['fragment'])){
    $cid = $arg['fragment']
 }
 */

 if(isset($_SERVER['HTTP_REFERER']))
 {
    $referer = trim($_SERVER['HTTP_REFERER']);
    
    if($referer == "http://blogs.namba.kg/post.php?id=$id")
    {
        $fgc = file_get_contents("http://blogs.namba.kg/post.php?id=$id");
        
        if(file_exists('blog.txt'))
        {
            if(filesize($fgc) != filesize('blog.txt'))
            {
                file_put_contents('blog.txt',$fgc);
            }else exit();
        } else {
            file_put_contents('blog.txt',$fgc);
        }
        $blog = file_get_contents('blog.txt');
    }else die();
 }else die();
 
 //Узнаем title Блога 
 preg_match("/<title>(.*)<\/title>/siU",$blog, $matches);
 $btitle = $matches[1];
 $btitle = preg_replace('/\s\s/','',$btitle);

##################################################################################
 //Подключаем класс HTML_DOM_PARSER
 include_once('html_dom.php');
 //Клева жэ что есть такое счастье? ^_^

 function namba_comments($i)
 {
    $source = file_get_html('blog.txt');

    foreach($source->find('div.commentlist') as $comments)
    {
       $count = count($comments);
       $parsed['username'] = trim($comments->find('a.username', $i)->plaintext);
       $parsed['comment']  = trim($comments->find('p', $i)->plaintext);       
       
       $array[] = $parsed;
    }

    $source->clear();
    unset($source);

    return $array;
 }
###################################################################################

 //Запускаем функцию поиска автаров и их комменты
 $array = namba_comments(0);

 foreach($array as $v)
 {   
    $username = $v["username"];
    $message  = $v["comment"];
    //Удаляем лишние html'лские пробелы
    $message  = preg_replace('/&nbsp;/', '', $message);
 }

 if(!empty($username))
 {
 	//Подключаем класс для работы с аськой
    include('WebIcqLite.class.php');
    //Формируем сообщение для отправки в аську
    $added_comment = "Господин, в вашем блоге ~$btitle~ ($url) новый коммент.\r\nIP комментатора: $ip\r\nНикнейм: $username\r\nКоммент: $message";
    
    $added_comment = iconv("UTF-8","cp1251",$added_comment);

    define('UIN', 626566206);       //Бот uin  (регаем на icq.com)
    define('PASSWORD', 'wc3dfclm'); //Бот пасс

    $icq = new WebIcqLite();
    
    if($icq->connect(UIN, PASSWORD))
    {
       if(!$icq->send_message("$sendto", "$added_comment"))
       {
        $icq->error();
       }
    }
    $icq->disconnect();

 }else echo "Блять хуй пойми че те еще нужно?!";

 die();

#######################################################################
# Осталось сделать так, чтобы выбирался только последний комментарий. #
# Но это хуйня.                                                       #
#######################################################################

?>