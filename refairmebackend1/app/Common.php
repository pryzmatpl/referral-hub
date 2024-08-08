<?php

function renderPHP($path, $data = null) {
    if (!is_null($data)) extract($data);
    ob_start();
    include($path);
    return ob_get_clean();
}

function renderEmailTemplate($template, $data) {
    return renderPHP(__DIR__ . '/../resources/emails/' . $template . '.php', $data);
}

function trimPort($url) {
    $url = explode(':', $url);
    if (in_array($url[0], ['http', 'https']) && count($url) == 2) return implode(':', $url);
    if (count($url) == 1) return implode(':', $url);
    unset($url[count($url) - 1]);
    return implode(':', $url);
}

function cc($array) {
    $is_assoc = isAssoc($array);
    foreach ($array as $key => $value) {
        if (is_array($value)) $array[$key] = cc($value);
        if ($is_assoc) {
            $cc_key = camel_case($key);
            if ($cc_key != $key) {
                $array[$cc_key] = $value;
                unset($array[$key]);
            }
        }
    }
    return $array;
}

function sc($array) {
    $is_assoc = isAssoc($array);
    foreach ($array as $key => $value) {
        if (is_array($value)) $array[$key] = sc($value);
        if ($is_assoc) {
            $sc_key = snake_case($key);
            if ($sc_key != $key) {
                $array[$sc_key] = $value;
                unset($array[$key]);
            }
        }
    }
    return $array;
}

function isAssoc(array $arr) {
  if (array() === $arr) return false;
  return array_keys($arr) !== range(0, count($arr) - 1);
}

function hasOneOfWords($string, array $words, $return_word = false) {
    foreach ($words as $word) {
        if (strpos($string, $word) !== false) return $return_word ?  $word : true;
    }
    return false;
}

function throwIfNone(&$val){
  if( ($val === '') || ($val === NULL) ){
    throw new Exception("Value should not be empty : ".print_r($val));
  }
}

function dotify($arr, $exceptions=0){
  $retarr=[];
  
  if( !is_null($arr) ){

    foreach ( $arr as $key => $value){
      $newkey = str_replace('_','.',$key);
      $retarr[$newkey] = $value;
    }

  }
  
  return $retarr;
}