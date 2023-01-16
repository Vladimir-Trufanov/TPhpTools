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
echo $NameGru;
exit;
// ********************************************************* getNameCue.php ***
