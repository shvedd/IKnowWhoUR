 <?php
 if(file_exists('blog.txt')){
 	$file = ('result.txt');
 	//$steg = '<div class="comments">';  //Start Teg
 	//$eteg = '<a name="leavecomment">'; //End Teg
 	$fp2  = fopen('blog.txt','r');
 	while(!feof($fp2)){
 		$line = fgets($fp2); //,4048,"<p></p>"
	 	if(preg_match_all("|<[^>]+>(.*)</[^>]+>|is", $line, $result))
	 	//if(preg_match('#'.preg_quote($a).'(.*)'.preg_quote($b).'#Uis',$line,$result))
	 	{
	 		echo $result[1][1] . "\n";
	 		foreach($result as $match)
	 		{
	 			file_put_contents($file, $match, FILE_APPEND);
	 		}
	 	}//else echo "<h2>Совпадение по регулярке не найдено.</h2>";
 	}fclose($fp2);
 }
 ?>