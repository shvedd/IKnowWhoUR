<?php
$fgc = file_get_contents("http://blogs.namba.kg/post.php?id=32079");
$fpc = file_put_contents("temp.txt",$fgc);
      
        if(file_exists('blog.txt'))
        {
            if(filesize('temp.txt') > filesize('blog.txt'))
            {
                file_put_contents('blog.txt',$fgc);
            }            
        }else echo "Файла нет";
        
?>