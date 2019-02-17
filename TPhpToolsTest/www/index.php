<?php
// PHP7/HTML5, EDGE/CHROME                                    *** index.php ***

// ****************************************************************************
// *  *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 14.01.2019


session_start(); 

// Инициализируем корневой каталог сайта, надсайтовый каталог, каталог хостинга
require_once "iGetAbove.php";
$SiteRoot = $_SERVER['DOCUMENT_ROOT'];  // Корневой каталог сайта
$SiteAbove = iGetAbove($SiteRoot);      // Надсайтовый каталог
$SiteHost = iGetAbove($SiteAbove);      // Каталог хостинга

echo $SiteHost."/TPhpTools/TException/ExceptionClass.php"."<br>";
require_once $SiteHost."/TPhpTools/TException/ExceptionClass.php";
$w2e = new Exceptionizer(E_ALL);

try 
{
require_once "Main.php";
} 
// Можно перехватить и пользовательскую ошибку/сообщение
// catch (E_USER_ERROR $e) {MakeUserMessage($e);}
// Перехватываем ошибку сайта
catch (E_EXCEPTION $e) 
{
   
   echo "Системное исключение: {$e->getMessage()}";
   echo '<pre>';
   echo $e->getTraceAsString();
   echo '</pre>';
   
   // При необходимости выводим дополнительную информацию
   // Header("Content-type: text/plain");
   // $headers = getallheaders();
   // print_r($headers);
   // print_r($_SERVER);
}

// ************************************************************** index.php ***
