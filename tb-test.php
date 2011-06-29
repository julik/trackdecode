#!/usr/bin/php
<?php

/***
  Trackback ping sampler
**/

/** Define encodings we wish to try out */
$charsets = array ("utf-8", "windows-1251", "koi8-r");

/** Define default ping destination */
$destUrl = 'http://julik.nl/cgi-bin/mt-tb.cgi/78';

//check for software
$reqFunctions = array (
  'mb_convert_variables'=>'You need to have mb_string linked',
  'curl_setopt'=>'You need to have lubcurl linked',
);

foreach ($reqFunctions as $f=>$m) {
  if (!function_exists($f)) {
    fwrite (STDERR, "ERROR = $m\n");
    exit (-1);

  }
}

if (isset($_SERVER['argv'][1])) {
  $destUrl = $_SERVER['argv'][1];
}

mb_internal_encoding("utf-8");

$formData = array (
  'title'=>'Russian trackback test',
  'url'=>'http://live.julik.nl/xxx' . rand(),
  'excerpt'=>'Пробуем здесь... С прицепом:  - Как долго продержится магия? Поначалу никто не ответил на вопрос Роланда, поэтому он задал его вновь, на этот раз подняв глаза на двух мэнни, которые сидели напротив него в гостиной дома отца Каллагэна, Хенчека и...',
  'blog_name'=>'julik-nl-probe-' . date('H:i:s', time()),
);


foreach ($charsets as $charset) {
  $formDataCp = $formData;
  mb_convert_variables ($charset, "utf-8", $formDataCp);
  $formDataCp['title'] = strtoupper($charset) . ' ' . $formDataCp['title'];

  echo "\n", "Sending ping in " . strtoupper($charset) . " using URL encoding (blind)\n";
  $result =  sendPingUrlencoded ($formDataCp, $charset, $destUrl, $sendHeader = false);
  
  sleep(30);

  echo "\n", "Sending ping in " . strtoupper($charset) . " using URL encoding with charset header\n";
  $result =  sendPingUrlencoded ($formDataCp, $charset, $destUrl, $sendHeader = true);

  sleep(30);

  echo "\n", "Sending ping in " . strtoupper($charset) . " using multipart encoding\n";
  $result =  sendPingMultipart ($formDataCp, $charset, $destUrl);

  sleep(30);

}


########

function sendPingMultipart($pingData, $pingCharset, $destUrl) {
  $pingData['url'] = $pingData['url'] . rand();
  $formDest = $destUrl;
  $formMethod = 'POST';
  $formEnctype = "multipart/form-data";
  
  $pingData['title'] = "MPF: " . $pingData['title'];
  
  // init curl handle
  $ch = curl_init($formDest);
  curl_setopt($ch, CURLOPT_HEADER, 1);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION,1); 
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
  curl_setopt($ch, CURLOPT_POSTFIELDS, $pingData);
  curl_setopt($ch, CURLOPT_MUTE, 1);
  
  // perform post
  $reqResult=curl_exec($ch);
  curl_close($ch);
//  while (substr_count($reqResult, "\n\n") 
//      || substr_count($reqResult, "  ")
//      || substr_count($reqResult, " \n")) {
//    $reqResult = str_replace(" \n","\n", $reqResult);
//    $reqResult = str_replace("\n\n","\n", $reqResult);
//    $reqResult = str_replace("  "," ", $reqResult);
//  }
//  echo $reqResult;
  if (substr_count($reqResult, '<error>0</error>')) {
    echo "OK\n";
    sleep (1);
    return true;
  } else {
    die ($reqResult);
  }
}


function sendPingUrlencoded($pingData, $pingCharset, $destUrl, $sendHeader  = false) {

  $pingData['url'] = $pingData['url'] . rand();
  $sendHeader ? $and=" (with charset header)" : $and ='';
  $pingData['title'] = "URL{$and}: " . $pingData['title'];
  $ar = parse_url($destUrl);
  // init curl handle
  $server = $ar['host'];
  $parameters = '';

  foreach ($pingData as $key=>$value) {
    $parameters .= '&' . $key . '=' . urlencode(stripslashes($value));
  }
  //open socket to the server
  
  $fp = fsockopen($server, 80, $errno, $errstr, 30);
  if (!$fp) {
    fwrite (STDERR, "ERROR = Could not connect\n");
    exit (-1); //let cron know that we have a problem
  }
  if ($sendHeader) {
    $contentType = 'Content-Type: application/x-www-form-urlencoded; charset=' . $pingCharset;
  } else {
    $contentType = 'Content-Type: application/x-www-form-urlencoded';
  }  
  $headers = array('POST ' . $ar['path'] . ' HTTP/1.0',
            'Host: ' . $ar['host'],
            $contentType,
            'Content-Length: ' . strlen($parameters),
            'Connection: close' . "\r\n\r\n",
          );
  $header = implode ($headers, "\r\n");
  

  fputs($fp, $header . $parameters);
  
  $string='';
  while (!feof($fp)) {
    $res = fgets($fp, 1024);
  //  echo 'Packet: ', $res;
    $string .= $res;
    if ($res == '</response>') {
      break;
    }
  }
  fclose ($fp);


//  while (substr_count($reqResult, "\n\n") 
//      || substr_count($reqResult, "  ")
//      || substr_count($reqResult, " \n")) {
//    $reqResult = str_replace(" \n","\n", $reqResult);
//    $reqResult = str_replace("\n\n","\n", $reqResult);
//    $reqResult = str_replace("  "," ", $reqResult);
//  }
  if (substr_count($string, '<error>0</error>')) {
    echo "OK\n";
    return true;
  } else {
    fwrite (STDERR, "ERROR = $string");
    exit (-1); //let cron know that we have a problem
  }  
}
?>