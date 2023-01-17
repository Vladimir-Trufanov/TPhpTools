<?php

// PHP7/HTML5, EDGE/CHROME                               *** getNameCue.php ***

// ****************************************************************************
// * TArticleMaker       По указанному идентификатору в аякс-запросе вытащить *
// *                         название группы материалов и вернуть на страницу *
// *                                                                          *
// * v1.0, 16.01.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  13.11.2022 *
// ****************************************************************************

// Указываем тип базы данных (по сайту) для управления классом ArticlesMaker   
define ("articleSite",'IttveMe'); 
// Указываем каталог размещения файлов, связанных c материалом
define("editdir",'ittveEdit');
// Извлекаем пути к библиотекам прикладных функций и классов
//define ("pathPhpPrown",$_POST['pathPrown']);
//define ("pathPhpTools",$_POST['pathTools']);
// Подгружаем нужные модули библиотеки прикладных классов
//require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
// Подключаем файлы библиотеки прикладных модулей:
//require_once pathPhpPrown."/CommonPrown.php";
// Подключаем обработку ошибки
set_error_handler("getNameCueHandler");


if(isset($_POST))
{
   if (isset($_POST['pathPrown'])) 
   {
      $c='POST[pathPrown])';
   }
   else
   {
      $c='empty';
   }
}
/*
$c='werty';
if(isset($_GET))
{
   $c='post';
   if (empty($_GET["data"])) 
   {
      $c='empty';
   }
   else
   {
      $c='$_GET["data"])';
   }
   
   if (isset($_GET['idCue'])) 
   {
      $c='$_GET["idCue"])';
   }
   else
   {
      $c='2empty';
   }
   
}
*/

//$data=json_decode($_POST['data']);



// Передаем данные в формате JSON
// (если нет передачи данных, то по аякс-запросу вернется ошибка)
$user_info=array(); 
//$user_info[]=array('text'=>'texti');
//$user_info[]=array('NameGru'=>'NameGruiii');
$user_info[]=array("status"=>1,"err"=>$c,);

$c='Привет!';
restore_error_handler();
//echo json_encode($user_info);
echo '["'.$c.'", 5, "false"]';

/*   
// Указываем тип базы данных (по сайту) для управления классом ArticlesMaker   
define ("articleSite",'IttveMe'); 
// Указываем каталог размещения файлов, связанных c материалом
define("editdir",'ittveEdit');
// Извлекаем пути к библиотекам прикладных функций и классов
define ("pathPhpPrown",$_POST['pathPrown']);
define ("pathPhpTools",$_POST['pathTools']);
// Подгружаем нужные модули библиотеки прикладных классов
require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
// Подключаем файлы библиотеки прикладных модулей:
require_once pathPhpPrown."/CommonPrown.php";
// Подключаем обработку ошибки
//set_error_handler("getNameCueHandler");
*/

/*
// Подключаем объект для работы с базой данных материалов
// (при необходимости создаем базу данных материалов)
$NameGru='Группа материалов';
$basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
$Arti=new ttools\ArticlesMaker($basename,$username,$password);
$pdo=$Arti->BaseConnect();
$table=$Arti->SelRecord($pdo,$_POST['idCue']); 
$NameGru=$table[0]['NameArt'];
unset($Arti); unset($pdo); unset($table);

// Передаем данные в формате JSON
// (если нет передачи данных, то по аякс-запросу вернется ошибка)
$user_info=array(); 
$user_info[]=array('NameGru'=>'NameGru');

$options=array(
"status"=>1
//,
//"err"=>'POSTi',
 );
echo json_encode($options);
//restore_error_handler();
//echo json_encode($user_info);
//echo $NameGru;
//exit;
*/

// ****************************************************************************
// *                          Обыграть ошибки                                 *
// ****************************************************************************
function getNameCueHandler($errno,$errstr,$errfile,$errline)
{
   $modul='getNameCueHandler';
   \prown\putErrorInfo($modul,$errno,$errstr,$errfile,$errline);
}

// ********************************************************* getNameCue.php ***
