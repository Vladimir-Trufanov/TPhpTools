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
$NameArt='NoDefine'; $Piati=0; $iif='NoDefine';
// Извлекаем пути к библиотекам прикладных функций и классов
define ("pathPhpPrown",$_POST['pathPrown']);
define ("pathPhpTools",$_POST['pathTools']);
// Подгружаем нужные модули библиотек
//require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";
require_once pathPhpPrown."/CommonPrown.php";
require_once pathPhpTools."/CommonTools.php";


// Выбираем массив файлов галереи для записи в базу данных 
$GalleryText=$_POST['area'];
//$GalleryText=htmlspecialchars($GalleryText);

//$pref='src=\"ittveEdit/ittve2-3-';
//$pattern="src=\"ittveEdit/ittve2-3-([0-9a-zA-Zа-яёА-ЯЁ\s\.\$\n\r\(\)-_:,=&;]+)\"/u";
//$pattern="input([0-9a-zA-Zа-яёА-ЯЁ\s\.\$\n\r\(\)-_:,=&;]+)hidden/u";
//$pattern="/tve2/u";
//$pattern='/src=\"([0-9a-zA-Zа-яёА-ЯЁ-=\.\"]+)\"/u';
//$pattern='/src=&quot;([0-9a-zA-Zа-яёА-ЯЁ\.-=&;]+)&quot;/u';
//$pattern='/tve2([0-9a-zA-Zа-яёА-ЯЁ\.-=&;]+)jpg/u';

//$pattern="/ittveEdit/ittve2-3-([0-9a-zA-Zа-яёА-ЯЁ\s\.\$\n\r\(\)\/-_:,=&;]+)jpg/u";

//$pattern='/tve2([0-9a-zA-Zа-яёА-ЯЁ\.-=&;]+)jpg/u';


$pattern='/tve2([0-9a-zA-Zа-яёА-ЯЁ\.-]+)\./u';
$pattern='/tve2([0-9a-zA-Zа-яёА-ЯЁ\.-]+)jpg/u';
$pattern='/tve2([0-9a-zA-Zа-яёА-ЯЁ\.-]+)(jpg|png|jpeg|gif)/u';

// Выбираем файлы изображений по регулярным выражениям
$pattern='/ittve2-3-([0-9a-zA-Zа-яёА-ЯЁ\.-]+)(jpg|png|jpeg|gif)/u';
preg_match_all($pattern,$GalleryText,$matches,PREG_SET_ORDER);


 
$NameArt='Галерея записана!';
foreach ($matches as $val) 
{
   $NameArt=$NameArt.$val[0];
   //$NameArt=$NameArt.$val[0]."\n";
}

$Piati=count($matches);






//define ("Pattern",     "/\/\/\sФункция([0-9a-zA-Zа-яёА-ЯЁ\s\.\$\n\r\(\)-_:,=&;]+)function/u");
//define ("Replacement", "function");
//$FileItog=preg_replace($pattern,$replacement,$FileContent);


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
// Освобождаем память
unset($Arti); unset($pdo); unset($table);
// Возвращаем сообщение
$message='{"NameArt":"'.$NameArt.'", "Piati":'.$Piati.', "iif":"'.$iif.'"}';
$message=\prown\makeLabel($message,'ghjun5','ghjun5');












/*
\ttools\PutString(
    '$GalleryText='.$GalleryText.
    '  pathPhpPrown='.$_POST['pathPrown'].
    '  pathPhpTools='.$_POST['pathTools'],'proba.txt');
*/
//\ttools\PutString('$pattern='.$pattern);
\ttools\PutString($GalleryText,'proba.txt');
echo $message;
exit;

function CodePart($TPhpPrown,$FuncFile,$pattern,$replacement)
{
$FileSpec=$TPhpPrown.'/TPhpPrown/'.$FuncFile;
$FileContent=file_get_contents($FileSpec);
// Вырезаем комментарий, который уже представлен
$FileItog=preg_replace($pattern,$replacement,$FileContent);
// Преобразуем текст в раскрашенный код и показываем его
$FileCode=highlight_string($FileItog,true);
echo $FileCode;
};

// ********************************************************** SaveStuff.php ***
