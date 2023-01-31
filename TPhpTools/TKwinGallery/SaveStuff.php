<?php

// PHP7/HTML5, EDGE/CHROME                                *** SaveStuff.php ***

// ****************************************************************************
// * TKwinGallery      По указанному идентификатору в аякс-запросе определить *
// *            наименование материала, а затем удалить запись из базы данных *
// *                                                                          *
// * v1.0, 29.01.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  13.11.2022 *
// ****************************************************************************

// Указываем тип базы данных (по сайту) для управления классом ArticlesMaker   
//define ("articleSite",'IttveMe'); 
// Указываем каталог размещения файлов, связанных c материалом
//define("editdir",'ittveEdit');

// Готовим начальные значения параметров возвращаемого сообщения
$NameArt='NoDefineiiii'; $Piati=0; $iif='NoDefineiii';
// Извлекаем пути к библиотекам прикладных функций и классов
define ("pathPhpPrown",$_POST['pathPrown']);
define ("pathPhpTools",$_POST['pathTools']);
// Подгружаем нужные модули библиотек
//require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
require_once pathPhpPrown."/CommonPrown.php";
require_once pathPhpTools."/CommonTools.php";
// Подключаем объект для работы с базой данных материалов
// (при необходимости создаем базу данных материалов)
//$basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
//$Arti=new ttools\ArticlesMaker($basename,$username,$password);
//$pdo=$Arti->BaseConnect();
// Выбираем запись по идентификатору группы материалов
//$table=$Arti->SelRecord($pdo,$_POST['idCue']); 
// Определяем количество найденных записей
//$count=count($table);
/*
// Если записей не найдено, то готовим сообщение
if ($count<1) $NameArt=gncNoCue;
// Иначе готовим удаление записи
else
{
   // Формируем сообщение удачного удаления
   $NameArt='Удаляется статья. '.$table[0]['NameArt'].'.';
   // Удаляем запись
   $messa=$Arti->DelRecord($pdo,$_POST['idCue']);
   // Если удаление с ошибкой, то готовим сообщение
   if ($messa<>imok) $NameArt='Ошибка. '.$messa;
}
*/
$GalleryText=$_POST['area'];
$json = '{"result":"truei", "count":42}';
\tools\PutString('$GalleryText='.$GalleryText.'<br><br>','proba.txt');
\tools\PutString('$json='.$json.'*<br><br>','proba.txt');

// Освобождаем память
unset($Arti); unset($pdo); unset($table);
// Возвращаем сообщение

$NameArt='Удаляется статья';
$NameArt='Delettt';


$message='{"NameArt":"'.$NameArt.'", "Piati":'.$Piati.', "iif":"'.$iif.'"}';
\tools\PutString('$message='.$message.'*<br><br>','proba.txt');






$message=\prown\makeLabel($message,'ghjun5','ghjun5');
echo $message;
exit;

// ********************************************************** SaveStuff.php ***
