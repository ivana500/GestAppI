<?php

/**
* 
* @param type $url
*/
function redirect($url){
   die('<meta http-equiv="refresh" content="0;URL='.$url.'">');
}

/**
 * 
 * @param type $url
 */
function redirection($url){ 
   header("Location: ".$url);
}

