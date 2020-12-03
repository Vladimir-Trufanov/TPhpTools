<?php
// PHP7/HTML5, EDGE/CHROME                                    *** index.php ***

// ****************************************************************************
// * TPhpPrown-test             TPhpPrown - библиотека PHP-прикладных функций *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 15.02.2020

echo 'Привет!<br>';
/*

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
   // Запускаем примеры ошибок и исключений
   //require_once $_SERVER['DOCUMENT_ROOT']."/MainDoorTry.php";
}
catch (E_EXCEPTION $e) 
{
   // Подключаем обработку исключений верхнего уровня
   DoorTryPage($e);
}
// ************************************************************** index.php ***
*/