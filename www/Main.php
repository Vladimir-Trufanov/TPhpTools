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
require_once $TPhpPrown."/TPhpPrown/DebugInfo.php";
require_once $TPhpPrown."/TPhpPrown/CommonPrown.php";
require_once $TPhpPrown."/TPhpPrown/getTranslit.php";
require_once $TPhpPrown."/TPhpPrown/MakeCookie.php";
require_once $TPhpPrown."/TPhpPrown/MakeSession.php";
require_once $TPhpPrown."/TPhpPrown/Findes.php";
/*
require_once $TPhpPrown."/TPhpPrown/MakeUserError.php";
require_once $TPhpPrown."/TPhpPrown/ViewGlobal.php";
require_once $TPhpPrown."/TPhpPrown/ViewSimpleArray.php";
*/
// Подключаем модуль обеспечения тестов
require_once $TPhpPrown."/TPhpPrownTests/FunctionsBlock.php";

// Подключаем файлы библиотеки прикладных классов:
$TPhpTools=$SiteHost.'/TPhpTools';
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
      ttools\ee('<br>123');
      $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
      $pathBase='sqlite:'.$filename; 
      $username='tve';
      $password='23ety17';                                         
      $db = new ttools\BaseMaker($pathBase,$username,$password);
      ttools\ee('45<br>');
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
