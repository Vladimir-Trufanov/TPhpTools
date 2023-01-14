<?php
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
   $basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
   $Arti=new ttools\ArticlesMaker($basename,$username,$password);
   if (!file_exists($basename.'.db3')) $Arti-> BaseFirstCreate();

   $message='Получил '.$_POST['idCue'].'! Возвращаю.';
   exit($message);
?>