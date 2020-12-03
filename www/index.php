<?php
// PHP7/HTML5, EDGE/CHROME                                    *** index.php ***

// ****************************************************************************
// * TPhpTools-test             TPhpTools - библиотека PHP-прикладных классов *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.06.2019
// Copyright © 2019 tve                              Посл.изменение: 03.12.2020

// Инициализируем рабочее пространство: корневой каталог сайта и т.д.
session_start();
 
// Извлекаем переменные рабочего пространства
require_once $_SERVER['DOCUMENT_ROOT'].'/iniWorkSpace.php';
$_WORKSPACE=iniWorkSpace();

$SiteRoot    = $_WORKSPACE[wsSiteRoot];     // Корневой каталог сайта
$SiteAbove   = $_WORKSPACE[wsSiteAbove];    // Надсайтовый каталог
$SiteHost    = $_WORKSPACE[wsSiteHost];     // Каталог хостинга
$SiteDevice  = $_WORKSPACE[wsSiteDevice];   // 'Computer' | 'Mobile' | 'Tablet'
$UserAgent   = $_WORKSPACE[wsUserAgent];    // HTTP_USER_AGENT
$TimeRequest = $_WORKSPACE[wsTimeRequest];  // Время запроса сайта
$Ip          = $_WORKSPACE[wsRemoteAddr];   // IP-адрес запроса сайта
$SiteName    = $_WORKSPACE[wsSiteName];     // Доменное имя сайта
$PhpVersion  = $_WORKSPACE[wsPhpVersion];   // Версия PHP

// Подключаем сайт сбора сообщений об ошибках/исключениях и формирования 
// страницы с выводом сообщений, а также комментариев для PHP5-PHP7
require_once $SiteHost."/TDoorTryer/DoorTryerPage.php";
try 
{
   // Запускаем сценарий сайта
   //require_once $SiteRoot."/Main.php";
   $TPhpPrown=$SiteHost.'/TPhpPrown';
   require_once $TPhpPrown."/TPhpPrown/getTranslit.php";
   //echo  prown\getTranslit('загрузчик файлов на сервер').'<br>';
   echo 'Привет снова!<br>';
}
catch (E_EXCEPTION $e) 
{
   // Подключаем обработку исключений верхнего уровня
   DoorTryPage($e);
}
// ************************************************************** index.php ***
