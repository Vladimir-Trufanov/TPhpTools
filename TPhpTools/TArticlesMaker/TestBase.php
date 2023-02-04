<?php

// PHP7/HTML5, EDGE/CHROME                                 *** TestBase.php ***

// ****************************************************************************
// * TArticleMaker      Проверить целостность базы данных по десяти очередным *
// *   записям. Если при проверке текущих 10 записей нет ни одной, то выбирем все
         // uid и находим (принимаем, какая будет) следующую запись
         

// *                                                                          *
// * v1.0, 04.02.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  04.02.2023 *
// ****************************************************************************

// Указываем тип базы данных (по сайту) для управления классом ArticlesMaker   
// define ("articleSite",'IttveMe'); 
// Указываем каталог размещения файлов, связанных c материалом
// define("editdir",'ittveEdit');

// Готовим начальные значения параметров возвращаемого сообщения
$messa='NoDefine'; $TestPoint=$_POST['TestPoint']; $error=false;
// Извлекаем пути к библиотекам прикладных функций и классов
define ("pathPhpPrown",$_POST['pathPrown']);
define ("pathPhpTools",$_POST['pathTools']);
// Подгружаем нужные модули библиотек
require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
require_once pathPhpPrown."/CommonPrown.php";
require_once pathPhpTools."/CommonTools.php";

// Подключаем объект для работы с базой данных материалов
// (при необходимости создаем базу данных материалов)
$basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
$Arti=new ttools\ArticlesMaker($basename,$username,$password);
$pdo=$Arti->BaseConnect();
// Выбираем до 16 нарастающих записей, начиная с заданного Uid, если записей 
// нет, то выбираем, начиная с начала. 
$table=array();
$table=get16TestBase($pdo,$TestPoint,$messa); 
// Если выборка с ошибкой, то готовим сообщение
if ($messa<>imok) $error=true;
 
/*
\ttools\PutString(
    '  TestPoint='.$_POST['TestPoint'].
    '  pathPhpPrown='.$_POST['pathPrown'].
    '  pathPhpTools='.$_POST['pathTools'],'proba.txt');
*/

// Освобождаем память
unset($Arti); unset($pdo); unset($table);
// Возвращаем сообщение
$message='{"messa":"'.$messa.'", "TestPoint":'.$TestPoint.', "error":"'.$error.'"}';
$message=\prown\makeLabel($message,'ghjun5','ghjun5');
echo $message;
exit;
// ****************************************************************************
// *        Выбрать до 16 нарастающих записей, начиная с заданного Uid,       *
// *               если записей нет, то выбрать, начиная с начала.            *
// ****************************************************************************
function get16TestBase($pdo,$TestPoint,&$messa) 
{
   $table=array();
   $LastPoint=$TestPoint+17;
   try
   {
      $pdo->beginTransaction();
      $cSQL='SELECT uid FROM stockpw WHERE (uid > '.$TestPoint.') AND (uid < '.$LastPoint.')';
      $stmt = $pdo->query($cSQL);
      $table = $stmt->fetchAll();
      $pdo->commit();
      $messa=imok;
   } 
   catch (\Exception $e) 
   {
      $messa=$e->getMessage();
      if ($pdo->inTransaction()) $pdo->rollback();
   }
   return $table;
}
// *********************************************************** TestBase.php ***
