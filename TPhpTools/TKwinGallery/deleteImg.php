<?php

// PHP7/HTML5, EDGE/CHROME                                *** deleteImg.php ***

// ****************************************************************************
// * TKwinGallery                     Удалить выбранное изображение c данными *
// *                  из базы данных по указанному идентификатору и транслиту *    
// *                                                                          *
// * v1.0, 17.02.2023                               Автор:      Труфанов В.Е. *
// * Copyright © 2022 tve                           Дата создания: 17.02.2023 *
// ****************************************************************************

// Готовим начальные значения параметров возвращаемого сообщения
$NameArt='NoDefineING'; $Piati=0; $iif='NoDefine';
// Извлекаем пути к библиотекам прикладных функций и классов
define ("pathPhpPrown",$_POST['pathPrown']);
define ("pathPhpTools",$_POST['pathTools']);
// Подгружаем нужные модули библиотек
require_once pathPhpTools."/TNotice/NoticeClass.php";
require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
require_once pathPhpPrown."/CommonPrown.php";
// Подключаем объект единообразного вывода сообщений
$note=new ttools\Notice();
// Назначаем обязательные константы для объекта работы с базой 
// данных материалов: тип базы данных (по сайту) для управления классом 
// ArticlesMaker, каталог размещения файлов, связанных c материалом
// и создаем сам объект
define("articleSite",'IttveMe'); 
define("editdir",'ittveEdit');
$basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
$Arti=new ttools\ArticlesMaker($basename,$username,$password,$note);
$pdo=$Arti->BaseConnect();

/*
// Выбираем запись по идентификатору группы материалов
$table=$Arti->SelRecord($pdo,$_POST['idCue']); 
// Определяем количество найденных записей
$count=count($table);

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
// Освобождаем память
unset($Arti); unset($pdo); unset($table);
*/
// Возвращаем сообщение
//$message='{"NameArt":"'.$NameArt.'", "Piati":'.$Piati.', "iif":"'.$iif.'"}';

$message='NameArt Привет! NameArt';
$message=\prown\makeLabel($message,'ghjun5','ghjun5');
echo $message;
exit;

// ********************************************************** deleteImg.php ***
