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
if (!(count($_FILES)>0))
{
   // Через кукис готовим 2 этап "Выполнение первой группы тестов TUploadToServer"
   // после будущей перезагрузки страницы из формы  
   $c_UploadToServer=prown\MakeCookie('UploadToServer',upl2etap,tStr); 
   // Отмечаем начало 1 этапа "Загрузка файла для первой группы тестов"
   MakeTitle("TUploadToServer [".upl1etap."]",'');
   // Выполняем 1 этап "Загрузка файла для первой группы тестов"
   $max = 3200000; // 3145728 = 3Мб;
   ?>
   <form action="" method="post" enctype="multipart/form-data" id="uploadImage">
   <p>
   <label for="image">Выберите изображение, размером не более 3M байт:<br></label>
   <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
   <input type="file" name="image" id="image">
   </p>
   <p>
   <input type="submit" name="upload" id="upload" value="Загрузите">
   </p>
   </form>
   <?php
}
// На следующих этапах проводим тестирование
else
{
   $c_UploadToServer=prown\MakeCookie('UploadToServer');  
            
   // Перед выполнением 2 и третьего этапов готовим через кукис 4 этап 
   // "Выполнение второй группы тестов" после будущей перезагрузки страницы 
   // после 3 этапа  
   if ($c_UploadToServer==upl2etap) prown\MakeCookie('UploadToServer',upl4etap,tStr); 
   // Перед выполнением 4 этапа перенастраиваем кукис на 1 этап 
   if ($c_UploadToServer==upl4etap) prown\MakeCookie('UploadToServer',upl1etap,tStr); 

   // Если требуется выполнение 1 группы тестов
   if ($c_UploadToServer==upl2etap)
   {
      // Отмечаем выполнение первой группы тестов TUploadToServer  
      MakeTitle("TUploadToServer [".upl2etap."]",'');
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

      // Отмечаем начало 3 этапа "Загрузка файла для второй группы тестов"
      SimpleMessage();
      MakeTitle("TUploadToServer [".upl3etap."]",'');
      //
      $max = 2300000;
      ?>
      <form action="" method="post" enctype="multipart/form-data" id="uploadImage">
      <p>
      <label for="image">Выберите это же изображение для загрузки с измененным PHP.INI:<br></label>
      <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max; ?>">
      <input type="file" name="image" id="image">
      </p>
      <p>
      <input type="submit" name="upload" id="upload" value="Загрузите">
      </p>
      </form>
      <?php
   }
   // Если требуется выполнение второй группы тестов
   if ($c_UploadToServer==upl4etap)
   {
      // Отмечаем выполнение первой группы тестов TUploadToServer  
      MakeTitle("TUploadToServer [".upl4etap."]",'');
   }
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
