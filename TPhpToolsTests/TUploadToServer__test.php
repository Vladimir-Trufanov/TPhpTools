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
$c_c1=prown\MakeCookie('c1');
echo $c_c1.' 888<br>'; 
$c_c1=prown\MakeCookie('c1',$c_c1+1,tInt);

// На первом этапе готовим загрузку файла
if (!(count($_FILES)>0))
{
   $max = 57200;
   ?>
   <form action="" method="post" enctype="multipart/form-data" id="uploadImage">
   <p>
   <label for="image">Выберите изображение:<br></label>
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
   // 
   $is=test_Construct($shellTest);
   if ($is<>Ok) PointMessage('=== '.$is.' ===<br>');
}
// ****************************************************************************
// *                  Создать новый каталог и задать его режим                *
// ****************************************************************************
function CreateDir($imgDir,$modeDir)
{
   // Если каталог существует, то удаляем его
   if (!is_dir($imgDir))
   {
      // Создаем каталог
      if (!mkdir($imgDir,$modeDir))
      {
         return 'Ошибка создания каталога в тесте: '.$imgDir;
      }
      // И отдельно (чтобы сработало на старых Windows) задаем права
      else
      {
         if (!chmod($imgDir,$modeDir))
         {
            return 'Ошибка изменения прав каталога в тесте: '.$imgDir.
               ' ['.$modeDir.'] --> ['.substr(sprintf('%o', fileperms($imgDir)),-4).']';
         }
         // Параметр режима состоит из четырех цифр:
         //
         // Первое число всегда равно нулю
         // Второе число указывает разрешения для владельца
         // Третье число указывает разрешения для группы пользователей владельца.
         // Четвертое число указывает разрешения для всех остальных.
         // Возможные значения (чтобы установить несколько разрешений, сложите следующие числа):
         //
         // 1 = выполнение
         // 2 = права на запись
         // 4 = разрешения на чтение
      }
   }
   return Ok;
}
// *********************************************** TUploadToServer_test.php ***
