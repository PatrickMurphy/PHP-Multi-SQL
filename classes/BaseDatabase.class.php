<?php

/*
 * In this file we can collect some common functionality between all databases mostly for query filtering / security. 
 */
class BaseDatabase {
  
  public function custom_escape_string($value){
    $return = '';
    for($i = 0; $i < strlen($value); ++$i) {
        $char = $value[$i];
        $ord = ord($char);
        if($char !== "'" && $char !== "\"" && $char !== '\\' && $ord >= 32 && $ord <= 126){
            $return .= $char;
        } else {
            $return .= '\\x' . dechex($ord);
        }
    }
    return $return;
  }
  
  public function cleanInput($input) {
    $str = @trim($input);
    if(get_magic_quotes_gpc()) {
      $str = stripslashes($str);
    }
    return $this->custom_escape_string($str);
  }
}
