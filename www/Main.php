<?php
// PHP7/HTML5, EDGE/CHROME                                     *** Main.php ***

// ****************************************************************************
// * TPhpTools-test                                  Подключить модули сайта, *
// *                                        выполнить начальную инициализацию * 
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 28.12.2020

// Подключаем файлы библиотеки прикладных модулей:
$TPhpPrown=$SiteHost.'/TPhpPrown';
require_once $TPhpPrown."/TPhpPrown/MakeCookie.php";
require_once $TPhpPrown."/TPhpPrown/MakeSession.php";
/*
require_once $TPhpPrown."/TPhpPrown/CommonPrown.php";
require_once $TPhpPrown."/TPhpPrown/DebugInfo.php";
require_once $TPhpPrown."/TPhpPrown/Findes.php";
require_once $TPhpPrown."/TPhpPrown/getTranslit.php";
require_once $TPhpPrown."/TPhpPrown/iniConstMem.php";
*/
//require_once $TPhpPrown."/TPhpPrown/MakeUserError.php";
/*
require_once $TPhpPrown."/TPhpPrown/MakeUserError.php";
require_once $TPhpPrown."/TPhpPrown/ViewGlobal.php";
require_once $TPhpPrown."/TPhpPrown/ViewSimpleArray.php";
*/

session_start();

// Инициализируем переменные-кукисы
$c_UserName=prown\MakeCookie('UserName',"Гость",tStr,true);            // логин авторизованного посетителя
$c_PersName=prown\MakeCookie('PersName',"Гость",tStr,true);            // логин посетителя
$c_BrowEntry=prown\MakeCookie('BrowEntry',0,tInt,true);                // число запросов сайта из браузера
$c_PersEntry=prown\MakeCookie('PersEntry',0,tInt,true);                // счетчик посещений текущим посетителем

// Инициализируем сессионные переменные (сессионные переменные инициируются после
// переменных-кукисов, так как некоторые переменные-кукисы переопределяются появившимися
// сессионными переменными. Например: $s_ModeImg --> $c_ModeImg)
$s_Counter=prown\MakeSession('Counter',0,tInt,true);              // посещения за сессию
//$s_isJScript=prown\MakeSession('isJScript','no',tInt,false);    // JavaScript не включен

// Инициализируем параметры страницы сайта 
//$p_ittveLife="ittve01-001-20130201-Особенности-устройства-винтиков-в-моей-голове.html";
//$p_ittveNews="ittve01-001-20130201-Особенности-устройства-винтиков-в-моей-голове.html";


// Изменяем счетчик запросов сайта из браузера и, таким образом,       
// регистрируем новую загрузку страницы
$c_BrowEntry=prown\MakeCookie('BrowEntry',$c_BrowEntry+1,tInt);  
// Изменяем счетчик посещений текущим посетителем      
$c_PersEntry=prown\MakeCookie('PersEntry',$c_PersEntry+1,tInt);
// Изменяем счетчик посещений за сессию                 
$s_Counter=prown\MakeSession('Counter',$s_Counter+1,tInt);   





// Подключаем модуль обеспечения тестов
require_once $TPhpPrown."/TPhpPrownTests/FunctionsBlock.php";

// Подключаем файлы библиотеки прикладных классов:
$TPhpTools=$SiteHost.'/TPhpTools';
require_once $TPhpTools."/TPhpTools/iniErrMessage.php";
require_once $TPhpTools."/TPhpTools/TDownloadFromServer/DownloadFromServerClass.php";
require_once $TPhpTools."/TPhpTools/TUploadToServer/UploadToServerClass.php";
require_once $TPhpTools."/TPhpTools/TBaseMaker/BaseMakerClass.php";

// Выполняем начальную инициализацию
require_once "Common.php";     // Всегда 1-ый корневой модуль в списке
require_once "iniMem.php";     // Всегда 2-ой корневой модуль в списке
require_once "UpSite.php";

/*
function merr($errno,$msg,$file,$ins)
{
echo 'ошибка <br>';
}
set_error_handler("merr",E_ALL);
filemtime('spoon');
*/

      /*
      $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
      $pathBase='sqlite:'.$filename; 
      $username='tve';
      $password='23ety17';                                         
      $db = new ttools\BaseMaker($pathBase,$username,$password);
      // Выборка одного значения
      $count = $db->queryValue('SELECT COUNT(*) FROM vids');
      echo "*** $count ***<br>";
      $count = $db->queryValues('SELECT COUNT(*) FROM vids');
      print_r($count);
      */

   
// При необходимости выводим дополнительную информацию
// Header("Content-type: text/plain");
// $headers = getallheaders();
// print_r($headers);
// print_r($_SERVER);

/**
 * Сайт работает следующим образом:
 * 
 * На сайте раскрываются 2 страницы, по пол-экрана по горизонтали.
 * Левая половина - это трассирующее меню с выбором тестов.
 * Правая половина - протоколы тестов.
 * 
**/

// *************************************************************** Main.php ***
