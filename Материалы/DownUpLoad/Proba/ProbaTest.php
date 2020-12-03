<?php
// PHP7/HTML5, EDGE/CHROME                                *** ProbaTest.php ***

// ****************************************************************************
// * DoorTry                                   Заготовка для пробной страницы *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 07.12.2019

// Подключаем файлы библиотеки прикладных модулей:
$TPhpPrown=$_SERVER['DOCUMENT_ROOT'];
//$TPhpPrown='https://doortry.ru';
require_once $TPhpPrown."/TPhpPrown/getAbove.php";
require_once $TPhpPrown."/TPhpPrown/ViewGlobal.php";
// Инициализируем корневой каталог, надсайтовый каталог и каталог хостинга
$SiteRoot=$_SERVER['DOCUMENT_ROOT'];
$SiteAbove=prown\GetAbove($SiteRoot);      // Надсайтовый каталог
$SiteHost=prown\GetAbove($SiteAbove);      // Каталог хостинга

// Пример простейшей загрузки файла во временный каталог
// (вообще при загрузке может быть много ошибок, их нужно обыгрывать)
/*
?>
<!DOCTYPE html>
<html lang='ru'>
<head>
   <title>PHP автоматически создает переменные при закачке</title>
   <meta charset='utf-8'>
</head>
<body>
   <?php ## PHP автоматически создает переменные при закачке.
      if (@$_REQUEST['doUpload'])
      echo '<pre>Содержимое $_FILES: '.print_r($_FILES,true)."</pre><hr />";
   ?>
   Выберите какой-нибудь файл в форме ниже:
   <form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
      <input type="file" name="myFile">
      <input type="submit" name="doUpload" value="Закачать">
   </form>
   <br>
<?php
?>
</body>
</html>
<?php
*/

/*
// Простейший фотоальбом с возможностью закачки.
  $imgDir = "img";        // каталог для хранения изображений
  @mkdir($imgDir, 0777);  // создаем, если его еще нет

  // Проверяем, нажата ли кнопка добавления фотографии.
  if (@$_REQUEST['doUpload']) {
    $data = $_FILES['file'];
    $tmp = $data['tmp_name'];
    // Проверяем, принят ли файл.
    if (is_uploaded_file($tmp)) {
      $info = @getimagesize($tmp);
      // Проверяем, является ли файл изображением.
      if (preg_match('{image/(.*)}is', $info['mime'], $p)) {
        // Имя берем равным текущему времени в секундах, а 
        // расширение - как часть MIME-типа после "image/".
        $name = "$imgDir/".time().".".$p[1];
        // Добавляем файл в каталог с фотографиями.
        move_uploaded_file($tmp, $name);
      } else {
        echo "<h2>Попытка добавить файл недопустимого формата!</h2>";
      }
    } else {
      echo "<h2>Ошибка закачки #{$data['error']}!</h2>";
    }
  }

  // Теперь считываем в массив наш фотоальбом.
  $photos = array();
  foreach (glob("$imgDir/*") as $path) {
    $sz = getimagesize($path); // размер
    $tm = filemtime($path);    // время добавления
    // Вставляем изображение в массив $photos.
    $photos[$tm] = [
      'time' => $tm,              // время добавления
      'name' => basename($path),  // имя файла
      'url'  => $path,            // его URI   
      'w'    => $sz[0],           // ширина картинки
      'h'    => $sz[1],           // ее высота
      'wh'   => $sz[3]            // "width=xxx height=yyy"
    ];
  }
  // Ключи массива $photos - время в секундах, когда была добавлена
  // та или иная фотография. Сортируем массив: наиболее "свежие" 
  // фотографии располагаем ближе к его началу.
  krsort($photos);
  // Данные для вывода готовы. Дело за малым - оформить страницу.
?>
<!DOCTYPE html>
<html lang='ru'>
<head>
  <title>Простейший фотоальбом с возможностью закачки</title>
  <meta charset='utf-8'>
</head>
<body>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
<input type="file" name="file"><br>
<input type="submit" name="doUpload" value="Закачать новую фотографию">
<hr>
</form>
<?php foreach($photos as $n=>$img) {?>
  <p><img 
   src="<?=$img['url']?>"
   <?=$img['wh']?> 
   alt="Добавлена <?=date("d.m.Y H:i:s", $img['time'])?>"
  >
<?php } ?>
</body>
</html>
 <?php
*/


// ---------------------------------------------------------------------------
/*
// Простейший набор файлов php с возможностью закачки
$filesDir = $SiteRoot."/TPhpProw";   // каталог для хранения изображений
@mkdir($filesDir, 0777);  // создаем, если его еще нет

// Проверяем, была ли нажата кнопка добавления фотографии.
if (@$_REQUEST['doUpload']) 
{
   echo '<pre>Содержимое $_FILES: '.print_r($_FILES,true)."</pre><hr />";
   echo '---<br>'; 
   echo print_r($_FILES['file'],true);
   echo '<br>---<br>'; 
   echo '---<br>'; 
   echo print_r($_FILES['file']['name'],true);
   $name="$filesDir/".$_FILES['file']['name'];
   echo '<br>---<br>'; 
   $data = $_FILES['file'];
   $tmp = $data['tmp_name'];
   // Проверяем, принят ли файл.
   if (is_uploaded_file($tmp)) 
   {
      //$info = @getimagesize($tmp);
      // Проверяем, является ли файл изображением.
      //if (preg_match('{image/(.*)}is', $info['mime'], $p)) 
      //{
         // Имя берем равным текущему времени в секундах, а
         // расширение - как часть MIME-типа после "image/"
         //$name = "$filesDir/".time().".".$p[1];
         echo '$tmp='.$tmp.'***';
         //$name = "$filesDir/".time().".".'phpn';
         // Добавляем файл в каталог с фотографиями
         move_uploaded_file($tmp, $name);
      //} 
      //else 
      //{
      //   echo "<h2>Попытка добавить файл недопустимого формата!</h2>";
      //}
   } 
   else 
   {
      echo "<h2>Ошибка закачки #{$data['error']}!</h2>";
   }
}

// Теперь считываем в массив наш фотоальбом.
$photos = array();
foreach (glob("$filesDir/*") as $path) 
{
   //$sz = getimagesize($path); // размер
   $tm = filemtime($path);    // время добавления
   // Вставляем изображение в массив $photos.
   $photos[$tm] = 
   [
      'time' => $tm,              // время добавления
      'name' => basename($path),  // имя файла
      'url'  => $path,            // его URI   
      'w'    => '$sz[0]',           // ширина картинки
      'h'    => '$sz[1]',           // ее высота
      'wh'   => '$sz[3]'            // "width=xxx height=yyy"
   ];
}

// Ключи массива $photos - время в секундах, когда была добавлена
// та или иная фотография. Сортируем массив: наиболее "свежие" 
// фотографии располагаем ближе к его началу.
krsort($photos);

// Данные для вывода готовы. Дело за малым - оформить страницу.
?>
<!DOCTYPE html>
<html lang='ru'>
<head>
  <title>Простейший набор файлов php с возможностью закачки</title>
  <meta charset='utf-8'>
</head>
<body>
<form action="<?=$_SERVER['SCRIPT_NAME']?>" method="POST" enctype="multipart/form-data">
<input type="file" name="file"><br>
<input type="submit" name="doUpload" value="Закачать новый файл PHP">
<hr>
</form>
<?php 
echo '==='.$filesDir.'===<br>';

foreach($photos as $n=>$img) 
{
   //echo '***'.$n.'***<br>';
   //echo '<pre>Содержимое $img: '.print_r($img,true)."</pre><hr />";
   //echo '<pre>Содержимое $img: '.print_r($img['name'],true)."</pre><hr />";
   echo '<pre>'.$img['name'].'</pre><br>';
} 
?>
</body>
</html>
<?php
*/

?>
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>Download Images</title>
</head>

<body>
    <p><a href="download.php?file=maiko.jpg">Download image 1</a></p>
    <p><a href="download.php?file=basin.jpg">Download image 2</a></p>
    <p><a href="download.php?file=logis.txt">logis.txt</a></p>
</body>
</html>
<?php
//include 'C:/DoorTry/www/Pages/Proba/images/logis.txt'; 
$c='C:/DoorTry/www/Pages/Proba/images/logis.txt'; 
$c=$SiteRoot.'/Pages/Proba/images/logis.txt'; 
$c='C:/DoorTry/www/Pages/Proba/images/getTranslit.php'; 
$c=$SiteRoot.'/Pages/Proba/images/getTranslit.php'; 
eval(file_get_contents($c));
//echo file_get_contents($c);
echo  prown\getTranslit('вывести сообщение об ошибке на php').'<br>';
echo 'Завершение до четвертого варианта!<br>';

// <!-- --> ************************************************* ProbaTest.php ***
