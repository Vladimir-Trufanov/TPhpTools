<?php 
                                          
// PHP7/HTML5, EDGE/CHROME                         *** PageStarterClass.php ***

// ****************************************************************************
// * TPhpTools  Стартер сессии страницы и регистратор пользовательских данных *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  11.10.2019
// Copyright © 2019 tve                              Посл.изменение: 22.10.2019

/**
 * Класс PageStarter обеспечивает запуск сессии страницы и регистрацию 
 * пользовательской информации в базе данных SQLite3
 * 
 * Пользовательская информация:
 * 
 * страница сайта. 
**/

// Свойства:
//
// $Page - название страницы сайта;
// $Uagent - браузер пользователя;

define ("BeginSession",  1); // Флаг первого обращения к объекту в сессии  

class PageStarter
{

   private $Page;    // страница сайта
   private $dtime;
   private $ip;
   private $Uagent;  // браузер пользователя
   private $uri;

   // *************************************************************************
   // *      Выбираем и формируем элементы пользовательской информации        *
   // *************************************************************************
   private function MakeOunInfo(&$dtime,&$ip,&$Uagent,&$uri)
   {
      $Uagent=$_SERVER['HTTP_USER_AGENT'];
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
   private function GlueString($Page,$dtime,$ip,$Uagent,$uri,$BegSess=true)
   {
      // Формируем префикс строки по имени страницы. Если строка формируется в 
      // начале сессии ($BegSess=true), то префикс заключаем в '***', если в
      // продолжении сессии, то префикс заключаем в '==='.
      if ($BegSess==true) $Prefix='***'.$Page.'***';
      else $Prefix='==='.$Page.'===';
      // Подклеиваем остальную пользовательскую информацию
      $Result=
         $Prefix.
         "$dtime ".
         "IP: $ip ".
         "URL: $uri \r\n".
         "Agent: $Uagent \r\n";
      return $Result;
   }
   // *************************************************************************
   // *         Занести строку пользовательской информации в лог-файл         *
   // *           (лог-файл разместить в корневом каталоге страницы)          *
   // *************************************************************************
   private function PutString($String,$Page)
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
         echo "Не могу запереть файл! [".$Page.']';  // Далее уйти в исключение
      }
   }

   public function __construct($iPage)
   {
      $this->Page=$iPage;
      session_start();
      // Регистрируем первое открытие страницы в сессии
      if (!isset($_SESSION[$this->Page]))
      {
         $this->MakeOunInfo($this->dtime,$this->ip,$this->Uagent,$this->uri);
         $entry_line=$this->GlueString($this->Page,$this->dtime,$this->ip,$this->Uagent,$this->uri);
         $this->PutString($entry_line,$this->Page);
         $_SESSION[$this->Page]=BeginSession; // отметили первое обращение к странице в сессии
      }
      // Регистрируем повторные обращения к странице в сессии
      else
      {
         $this->MakeOunInfo($this->dtime,$this->ip,$this->Uagent,$this->uri);
         $entry_line=$this->GlueString($this->Page,$this->dtime,$this->ip,$this->Uagent,$this->uri,false);
         $this->PutString($entry_line,$this->Page);
      }
   }
   public function __destruct()
   {
   }
} 
// *************************************************** PageStarterClass.php ***
