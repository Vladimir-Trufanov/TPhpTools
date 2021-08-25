<?php

// PHP7/HTML5, EDGE/CHROME                     *** TUploadToServer_test.php ***

// ****************************************************************************
// * TPhpPrown      Тест класса TUploadToServer - загрузчика файлов на сервер *
// ****************************************************************************
// *                                                                          *
// * v2.0, 24.08.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  03.12.2020 *
// ****************************************************************************

// echo 'Тестируется TUploadToServer_test.php'.'<br>';
MakeTitle("TUploadToServer",'');

// На первом этапе готовим загрузку файла
if (!(count($_FILES)>0))
{
   $max = 157200;
   ?>
   <form action="" method="post" enctype="multipart/form-data" id="uploadImage">
   <p>
   <label for="image">Выберите изображение:</label>
   <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
   <input type="file" name="image" id="image">
   </p>
   <p>
   <input type="submit" name="upload" id="upload" value="Загрузите">
   </p>
   </form>
   <?php
}
// На втором этапе проводим тестирование
else
{
   // Выводим доступные параметры загруженного файла
   // print_r($_FILES);
   echo 'Загруженный файл:   '.$_FILES['image']['name'].    '<br>';
   echo 'Тип файла:          '.$_FILES['image']['type'].    '<br>';
   if (isset($_FILES['image']['tmp_name']))
   echo 'Временный файл:     '.$_FILES['image']['tmp_name'].'<br>';
   echo 'Состояние загрузки: '.$_FILES['image']['error'].   '<br>';
   echo 'Размер в байтах:    '.$_FILES['image']['size'].    '<br>';
   // Настраиваем текущий каталог
   $CurrDir=$_SERVER['DOCUMENT_ROOT'];  //'c:\TPhpTools\www';
   chdir($CurrDir);
   // Создаем каталог для хранения изображений, если его нет.
   // И отдельно (чтобы сработало на старых Windows) задаем права
   $imgDir="Gallery";
   if (!is_dir($imgDir))
   {
      mkdir($imgDir);      
      chmod($imgDir,0777);
   }
   // Создаем объект для переброски файла на сервер
   $upload = new ttools\UploadToServer($_SERVER['DOCUMENT_ROOT'].'/'.$imgDir.'/');
}









/*
// Указываем каталог для хранения изображений
$imgDir=$CurrDir.'/'."Gallery";
$destination = $imgDir.'/';
$upload = new ttools\UploadToServer($destination);

*/


/*
$max = 57200;

?>
<form action="" method="post" enctype="multipart/form-data" id="uploadImage">
<p>
<label for="image">Выберите изображение:</label>
<input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
<input type="file" name="image" id="image">
</p>
<p>
<input type="submit" name="upload" id="upload" value="Загрузите">
</p>
</form>
<?php
*/



//filemtime('генерируем отладочное исключение'); 
// Начинаем протоколировать тесты
//SimpleMessage();


/*

PointMessage('Проверяется существование и удаляется старый файл базы данных:');  
SimpleMessage(); PointMessage('--- '.'$filename');  
OkMessage();
      
PointMessage('Проверяются настройки целостности по внешним ключам:'); 

// Заново создаем базу данных и подключаем к ней TBaseMaker
$db = new ttools\BaseMaker($pathBase,$username,$password);
// Тестируем Values, Rows методы
test_ValueRow($db,$shellTest);

// PragmaBaseTest($pdo,$shellTest);
OkMessage();
*/
// *********************************************** TUploadToServer_test.php ***
