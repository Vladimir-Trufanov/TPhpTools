<?php 
                                          
// PHP7/HTML5, EDGE/CHROME                         *** PageStarterClass.php ***

// ****************************************************************************
// * TPhpTools  Стартер сессии страницы и регистратор пользовательских данных *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  11.10.2019
// Copyright © 2019 tve                              Посл.изменение: 11.10.2019

/**
 * Класс PageStarter обеспечивает запуск сессии страницы и регистрацию 
 * пользовательской информации в базе данных SQLite3
 * 
 * Пользовательская информация:
 * 
 * название (доменное имя) сайта, название страницы сайта. 
**/

// Свойства:
//
// $SiteName - название (доменное имя) сайта;
// $PageName - название страницы сайта;

// ----------------------- Константы управления передачей данных о загрузке ---
//define ("fltNotTransmit",  0); // данные не передаются  
//define ("fltWriteConsole", 1); // записываются в консоль
//define ("fltSendCookies",  2); // отправляются в кукисы
//define ("fltAll",          3); // записываются в консоль, отправляются в кукисы  

class PageStarter
{

   private $SiteName;
   private $PageName;
   private $dtime;
   private $ip;
   private $agent;
   private $uri;

   // *************************************************************************
   // *      Выбираем и формируем элементы пользовательской информации        *
   // *************************************************************************
   private function MakeOunInfo(&$dtime,&$ip,&$agent,&$uri)
   {
      $agent=$_SERVER['HTTP_USER_AGENT'];
      $uri=$_SERVER['REQUEST_URI'];
      //$useri = 'PHP_AUTH_USER';
      $ip = $_SERVER['REMOTE_ADDR'];
      //$ref = '123'; //$_SERVER['HTTP_REFERER'];
      $dtime = date('r');
      /*
      if($ref == "")
      {
         $ref = "None";
      }
      if($useri == "")
      {
         $useri = "None";
      }
      */
   }
   // *************************************************************************
   // *      Склеить строку пользовательской информации для лог-файла         *
   // *************************************************************************
   private function GlueString($PageName,$dtime,$ip,$agent,$uri,$BegSess=true)
   {
      // Формируем префикс строки по имени страницы. Если строка формируетя в 
      // начале сессии ($BegSess=true), то префикс заключаем в '***', если в
      // продолжении сессии, то префикс заключаем в '==='.
      if ($BegSess==true) $Prefix='***'.$PageName.'***';
      else $Prefix='==='.$PageName.'===';
      // Подклеиваем остальную пользовательскую информацию
      $Result=
         $Prefix.
         "$dtime ".
         "IP: $ip ".
         "Agent: $agent ".
         "URL: $uri \r\n";
      return $Result;
   }
   // *************************************************************************
   // *         Занести строку пользовательской информации в лог-файл         *
   // *           (лог-файл разместить в корневом каталоге страницы)          *
   // *************************************************************************
   private function PutString($String)
   {
      $fp = fopen("logis.txt","a+");
      if (flock($fp,LOCK_EX)) 
      { 
         fputs($fp,$String);
         flock($fp, LOCK_UN); 
         fclose($fp);
      } 
      else 
      {
         echo "Не могу запереть файл! [".$PageName.']';  // Далее уйти в исключение
      }
   }

   public function __construct($iSiteName,$iPageName)
   {
      $SiteName=$iSiteName;
      $PageName=$iPageName;
      
      session_start();
      if (!isset($_SESSION[$PageName]))
      {
         $this->MakeOunInfo($this->dtime,$this->ip,$this->agent,$this->uri);
         $entry_line=$this->GlueString($this->PageName,$this->dtime,$this->ip,$this->agent,$this->uri);
         $this->PutString($entry_line);
         $_SESSION[$PageName]='yes';
      }
      $this->MakeOunInfo($this->dtime,$this->ip,$this->agent,$this->uri);
      $entry_line=$this->GlueString($this->PageName,$this->dtime,$this->ip,$this->agent,$this->uri,false);
      $this->PutString($entry_line);
   }
   public function __destruct()
   {
   }
} 
// *************************************************** PageStarterClass.php ***
