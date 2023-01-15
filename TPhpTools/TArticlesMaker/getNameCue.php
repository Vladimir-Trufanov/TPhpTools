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
   $NameGru='Группа материалов';
   $basename=$_SERVER['DOCUMENT_ROOT'].'/ittve'; $username='tve'; $password='23ety17'; 
   $Arti=new ttools\ArticlesMaker($basename,$username,$password);
   $pdo=$Arti->BaseConnect();
   $table=$Arti->SelRecord($pdo,$_POST['idCue']); 
   //PutString('$NameGru1='.$NameGru); 
   $NameGru=$table[0]['NameArt'];
   //PutString('$NameGru='.$NameGru); 
   unset($Arti); unset($pdo); unset($table);
   echo($NameGru);
   
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
