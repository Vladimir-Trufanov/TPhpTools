<?php

function file_force_download($file) 
{
   if (file_exists($file)) 
   {
      // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
      // если этого не сделать файл будет читаться в память полностью!
      if (ob_get_level()) 
      {
         ob_end_clean();
      }
      // заставляем браузер запустить окно сохранения файла
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.basename($file));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: '.filesize($file));
     // читаем файл и отправляем его пользователю
     readfile($file);
     exit;
   }
}

function file_force_download1($file) 
{
   if (file_exists($file)) 
   {
      // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
      // если этого не сделать файл будет читаться в память полностью!
      if (ob_get_level()) 
      {
         ob_end_clean();
      }
      // заставляем браузер показать окно сохранения файла
      header('Content-Description: File Transfer');
      header('Content-Type: application/octet-stream');
      header('Content-Disposition: attachment; filename='.basename($file));
      header('Content-Transfer-Encoding: binary');
      header('Expires: 0');
      header('Cache-Control: must-revalidate');
      header('Pragma: public');
      header('Content-Length: '.filesize($file));
      // читаем файл и отправляем его пользователю
      if ($fd = fopen($file,'rb')) 
      {
         while (!feof($fd)) 
         {
            print fread($fd,1024);
         }
         fclose($fd);
      }
      exit;
   }
}

function file_force_download2()
// https://sitear.ru/material/php_skript_download_file
{
   $filename = 'pic.png';
   // нужен для Internet Explorer, иначе Content-Disposition игнорируется
   if (ini_get('zlib.output_compression')) ini_set('zlib.output_compression','Off');
   
   $file_extension = strtolower(substr(strrchr($filename,"."),1));
   if( $filename == "" )
   {
      echo "ОШИБКА: не указано имя файла.";
      exit;
   } 
   elseif ( ! file_exists( $filename ) ) // проверяем существует ли указанный файл
   {  
      echo "ОШИБКА: данного файла не существует.";
      exit;
   };
   switch( $file_extension )
   {
      case "pdf": $ctype="application/pdf"; break;
      case "exe": $ctype="application/octet-stream"; break;
      case "zip": $ctype="application/zip"; break;
      case "doc": $ctype="application/msword"; break;
      case "xls": $ctype="application/vnd.ms-excel"; break;
      case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
      case "mp3": $ctype="audio/mp3"; break;
      case "gif": $ctype="image/gif"; break;
      case "png": $ctype="image/png"; break;  
      case "jpeg":
      case "jpg": $ctype="image/jpg"; break;
      default: $ctype="application/force-download";
   }
   header("Pragma: public"); 
   header("Expires: 0");
   header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
   header("Cache-Control: private",false); // нужен для некоторых браузеров
   header("Content-Type: $ctype");
   header("Content-Disposition: attachment; filename=\"".basename($filename)."\";" );
   header("Content-Transfer-Encoding: binary");
   header("Content-Length: ".filesize($filename)); // необходимо доделать подсчет размера файла по абсолютному пути
   readfile("$filename");
   exit();
}


function file_force_download3()
{
   // define error page
   $error = 'error.php';
   // define the path to the download folder
   $filepath = 'C:/fil/';

   $getfile = NULL;

   // block any attempt to explore the filesystem
   if (isset($_GET['file']) && basename($_GET['file']) == $_GET['file']) 
   {
      $getfile = $_GET['file'];
   } 
   else 
   {
      header("Location: $error");
      exit;
   }

   if ($getfile) 
   {
      $path = $filepath . $getfile;
      // check that it exists and is readable
      if (file_exists($path) && is_readable($path)) 
      {
         // send the appropriate headers
         header('Content-Type: application/octet-stream');
         header('Content-Length: '. filesize($path));
         header('Content-Disposition: attachment; filename=' . $getfile);
         header('Content-Transfer-Encoding: binary');
         // output the file content
         readfile($path);
      } 
      else 
      {
         header("Location: $error");
      }
   }
}


// https://habr.com/ru/post/151795/
// https://www.php.net/manual/en/function.readfile.php#example-2287
// https://daruse.ru/loadfile-php
// !!! https://snipp.ru/php/send-download
// http://itrobo.ru/programmirovanie/web/zagruzka-i-skachivanie-faila-v-php.html

$file='Main.php';
$file='pic.png';
if (!file_exists($file)) 
{
   echo $file.' не существует!<br>';
} 
else 
{
   //file_force_download($file);
   //file_force_download1($file);
   //file_force_download2();
   //file_force_download3();
   //echo '<p><a href="download.php?file=pic1.png">Download image 2</a></p>';


// Инициализируем рабочее пространство: корневой каталог сайта и т.д.
require_once 'iniWorkSpace.php';
$_WORKSPACE=iniWorkSpace();

$SiteRoot    = $_WORKSPACE[wsSiteRoot];     // Корневой каталог сайта
$SiteAbove   = $_WORKSPACE[wsSiteAbove];    // Надсайтовый каталог
$SiteHost    = $_WORKSPACE[wsSiteHost];     // Каталог хостинга
$SiteDevice  = $_WORKSPACE[wsSiteDevice];   // 'Computer' | 'Mobile' | 'Tablet'
$UserAgent   = $_WORKSPACE[wsUserAgent];    // HTTP_USER_AGENT
//echo '***'.$SiteRoot.'***'.'<br>';
/*
$TimeRequest = $_WORKSPACE[wsTimeRequest];  // Время запроса сайта
$Ip          = $_WORKSPACE[wsRemoteAddr];   // IP-адрес запроса сайта
$SiteName    = $_WORKSPACE[wsSiteName];     // Доменное имя сайта
*/

// Подключаем сайт сбора сообщений об ошибках/исключениях и формирования 
// страницы с выводом сообщений, а также комментариев для PHP5-PHP7
require_once $SiteHost."/TDoorTryer/DoorTryerPage.php";
try 
{

   // Подключаем файлы библиотеки прикладных классов:
   $TPhpTools=$SiteHost.'/TPhpTools';
   require_once $TPhpTools."/TPhpTools/TDownloadFromServer/DownloadFromServerClass.php";
   $download = new ttools\DownloadFromServer('');
   $download->move();
}
catch (E_EXCEPTION $e) 
{
   // Подключаем обработку исключений верхнего уровня
   DoorTryPage($e);
}



}
