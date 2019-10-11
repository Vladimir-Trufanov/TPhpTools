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
   
   public function __construct($iSiteName,$iPageName)
   {
      $SiteName=$iSiteName;
      $PageName=$iPageName;
      
      session_start();
      if (!isset($_SESSION[$PageName]))
      {
         $agent=$_SERVER['HTTP_USER_AGENT'];
         $uri=$_SERVER['REQUEST_URI'];
         //$useri = 'PHP_AUTH_USER';
         $ip = $_SERVER['REMOTE_ADDR'];
         //$ref = '123'; //$_SERVER['HTTP_REFERER'];
         $dtime = date('r');
         $entry_line = 
            '***'.$PageName.'***'.
            "$dtime ".
            "IP: $ip ".
            "Agent: $agent ".
            "URL: $uri \r\n";
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
         $fp = fopen("logisi.txt", "a+");
         if (flock($fp, LOCK_EX)) 
         { // выполнить эксплюзивное запирание
            fputs($fp, $entry_line);
            flock($fp, LOCK_UN); // отпираем файл
            fclose($fp);
            $_SESSION[$PageName]='yes';
         } 
         else 
         {
            echo "Не могу запереть файл! [".$PageName.']';
         }
      }

      $agent = $_SERVER['HTTP_USER_AGENT'];
      $uri = $_SERVER['REQUEST_URI'];
      //$useri = 'PHP_AUTH_USER';
      $ip = $_SERVER['REMOTE_ADDR'];
      //$ref = '123'; //$_SERVER['HTTP_REFERER'];
      $dtime = date('r');
      $entry_line = 
         '==='.$PageName.'==='.
         "$dtime ".
         "IP: $ip ".
         "Agent: $agent ".
         "URL: $uri \r\n";
      $fp = fopen("logisi.txt", "a+");
      if (flock($fp, LOCK_EX)) 
      { // выполнить эксплюзивное запирание
         fputs($fp, $entry_line);
         flock($fp, LOCK_UN); // отпираем файл
         fclose($fp);
      } 
      else 
      {
         echo "Не могу запереть файл! [".$PageName.']';
      }
   }
   /*
   //----
   $f=fopen("stat.dat","a+");
   flock($f,LOCK_EX);
   $count=fread($f,100);
   @$count++;
   ftruncate($f,0);
   fwrite($f,$count);
   fflush($f);
   flock($f,LOCK_UN);
   fclose($f);
   */
   public function __destruct()
   {
   }
} 
// *************************************************** PageStarterClass.php ***
