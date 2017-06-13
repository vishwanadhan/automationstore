<?php

$path` = 'wfs_data/ocum_2015-09-17-04-55-00-3982';

 if (is_dir($path)) { 
     $objects = scandir($path); 
     foreach ($objects as $object) { 
     	
       if ($object != "." && $object != "..") { 
         if (filetype($path."/".$object) == "dir") rrmdir($path."/".$object); else unlink($path."/".$object); 
       } 
     } 
     reset($objects); 
     rrmdir($path); 
   } 

   ?>
