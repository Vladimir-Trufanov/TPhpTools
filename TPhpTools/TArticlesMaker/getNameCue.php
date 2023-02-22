<?php

// PHP7/HTML5, EDGE/CHROME                               *** getNameCue.php ***

// ****************************************************************************
// * TArticleMaker       По указанному идентификатору в аякс-запросе вытащить *
// *                         данные о группе материалов и вернуть на страницу *
// *                                                                          *
// * v1.0, 04.02.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  13.11.2022 *
// ****************************************************************************

// Инициализируем константы
define("articleSite",  'IttveMe');   // тип базы данных для управления классом ArticlesMaker 
define("editdir",      'ittveEdit'); // каталог файлов, связанных c материалом
define("stylesdir",    'Styles');    // каталог стилей элементов разметки и фонтов
define("jsxdir",       'Jsx');       // каталог файлов на javascript
define("imgdir",       'Images');    // каталог служебных изображений

// Готовим начальные значения параметров возвращаемого сообщения
$NameGru='NoDefine'; $Piati=0; $iif='NoDefine';
// Извлекаем пути к библиотекам прикладных функций и классов
define ("pathPhpPrown",$_POST['pathPrown']);
define ("pathPhpTools",$_POST['pathTools']);
// Подгружаем нужные модули библиотек
require_once pathPhpTools."/TNotice/NoticeClass.php";
require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
require_once pathPhpPrown."/CommonPrown.php";
// Подключаем объект единообразного вывода сообщений
$note=new ttools\Notice();
// Подключаем объект для работы с базой данных материалов
// (при необходимости создаем базу данных материалов)
$NameGru='Группа материалов';
$basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
$Arti=new ttools\ArticlesMaker($basename,$username,$password,$note);
$pdo=$Arti->BaseConnect();
// Выбираем запись по идентификатору группы материалов
$table=$Arti->SelRecord($pdo,$_POST['idCue']); 
// Выделяем из записи элементы
$NameGru=$table[0]['NameArt'];
// Освобождаем память
unset($Arti); unset($pdo); unset($table); unset($note);
// Возвращаем сообщение
$message='{"NameGru":"'.$NameGru.'", "Piati":'.$Piati.', "iif":"'.$iif.'"}';
$message=\prown\makeLabel($message,'ghjun5','ghjun5');
echo $message;
exit;

// ********************************************************* getNameCue.php ***
