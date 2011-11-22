<?php
 $a = "<a>";
 $b = "</a>";

 #Нужно настроить время, чтобы было идентично как на
 $date   = date("F j, Y");
 $time   = date("g:i a");
 	//echo $date."\r\n".$time;

 if(file_exists('blog.html')){
 	$file = ('result.html');
 	$fp2  = fopen('blog.html','r');
 	while(!feof($fp2))
 	{
 		$line = strip_tags(fgets($fp2));
 		//$line = preg_replace("!(?<=Ваш\r\n).+!is", "", $line);
 		echo $line;
 	}
	//if(preg_match('#'.preg_quote($a).'(.*)'.preg_quote($b).'#Uis',$line,$result))
	if(preg_match('~(>(.*)</a)+~iUsmu',$line,$result))
	{
		foreach($result as $match)
		{
			file_put_contents($file, $line, FILE_APPEND);
		}
	}
 }fclose($fp2);
?>