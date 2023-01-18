<?php

// PHP7/HTML5, EDGE/CHROME                               *** getNameCue.php ***

// ****************************************************************************
// * TArticleMaker       По указанному идентификатору в аякс-запросе вытащить *
// *                         данные о группе материалов и вернуть на страницу *
// *                                                                          *
// * v1.0, 18.01.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  13.11.2022 *
// ****************************************************************************

/* 19.01.2023 -----------------------------------------------------------------
// Указываем тип базы данных (по сайту) для управления классом ArticlesMaker   
define ("articleSite",'IttveMe'); 
// Указываем каталог размещения файлов, связанных c материалом
define("editdir",'ittveEdit');
// Если не передан путь к библиотекам прикладных функций,
// то возвращаем сообщение об ошибке
if (!isset($_POST['pathPrown'])) 
{
   $NameGru=gncPrown; $message='["'.$NameGru.'"]';
}
// Если не передан путь к библиотекам прикладных классов,
// то возвращаем сообщение об ошибке
else if (!isset($_POST['pathTools'])) 
{
   $NameGru=gncTools;
   $message='["'.$NameGru.'"]';
}
// Если не передан идентификатор группы материалов,
// то возвращаем сообщение об ошибке
else if (!isset($_POST['idCue'])) 
{
   $NameGru=gncIdCue;
   $message='["'.$NameGru.'"]';
}
else
{
   // Извлекаем пути к библиотекам прикладных функций и классов
   define ("pathPhpPrown",$_POST['pathPrown']);
   define ("pathPhpTools",$_POST['pathTools']);
   // Подгружаем нужные модули библиотеки прикладных классов
   require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
   // Подключаем объект для работы с базой данных материалов
   // (при необходимости создаем базу данных материалов)
   $NameGru='Группа материалов';
   $basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
   $Arti=new ttools\ArticlesMaker($basename,$username,$password);
   $pdo=$Arti->BaseConnect();
   $table=$Arti->SelRecord($pdo,$_POST['idCue']); 
   $NameGru=$table[0]['NameArt'];
   unset($Arti); unset($pdo); unset($table);
   $NameGru='Привет ура'; 
   //$NameGru=$basename; 
   $message='["'.$NameGru.'"]';
}
//restore_error_handler();
echo $message;
//exit;
*/ 
/* 19.01.2023 -----------------------------------------------------------------
  $NameGru='POST[pathTools])';
  $message='["'.$NameGru.'", 5, "false"]';
*/

/*
// ****************************************************************************
// *                          Обыграть ошибки                                 *
// ****************************************************************************
function getNameCueHandler($errno,$errstr,$errfile,$errline)
{
   $modul='getNameCueHandler';
   \prown\putErrorInfo($modul,$errno,$errstr,$errfile,$errline);
}
*/

/* ---13/01/2023------------------------------------------------
*/
// Указываем тип базы данных (по сайту) для управления классом ArticlesMaker   
define ("articleSite",'IttveMe'); 
// Указываем каталог размещения файлов, связанных c материалом
define("editdir",'ittveEdit');
// Извлекаем пути к библиотекам прикладных функций и классов
define ("pathPhpPrown",$_POST['pathPrown']);
define ("pathPhpTools",$_POST['pathTools']);
// Подгружаем нужные модули библиотеки прикладных классов
require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
// Подключаем обработку ошибки
set_error_handler("getNameCueHandler");
// Подключаем объект для работы с базой данных материалов
// (при необходимости создаем базу данных материалов)
$NameGru='Группа материалов';
$basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
$Arti=new ttools\ArticlesMaker($basename,$username,$password);
$pdo=$Arti->BaseConnect();
$table=$Arti->SelRecord($pdo,$_POST['idCue']); 
$NameGru=$table[0]['NameArt'];
unset($Arti); unset($pdo); unset($table);
PutString('$NameGru='.$NameGru);
restore_error_handler();
echo $NameGru;
exit;
/*
*/ 
function PutString($String)
{
      $fp = fopen("LogName.txt","a+");
      if (flock($fp,LOCK_EX)) 
      { 
         fputs($fp,$String);
         flock($fp, LOCK_UN); 
         fclose($fp);
      } 
      else 
      {
         echo "Не могу запереть файл! [".$this->LogName.".txt".']';  // Далее уйти в исключение
      }
}
// ****************************************************************************
// *                          Обыграть ошибки                                 *
// ****************************************************************************
function getNameCueHandler($errno,$errstr,$errfile,$errline)
{
   $modul='getNameCueHandler';
   \prown\putErrorInfo($modul,$errno,$errstr,$errfile,$errline);
}

// ********************************************************* getNameCue.php ***
