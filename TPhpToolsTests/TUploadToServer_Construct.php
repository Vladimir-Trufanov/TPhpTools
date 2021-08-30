 <?php
// PHP7/HTML5, EDGE/CHROME                *** TUploadToServer_Construct.php ***

// ****************************************************************************
// * TPhpTools.TBaseMaker           Протестировать методы Values,Rows, quotes *
// *                                                                          *
// * v1.0, 30.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  14.02.2021 *
// ****************************************************************************

function test_Construct($thiss)
{
   PointMessage('Проверяется все учтенные сообщения:');
   SimpleMessage();
   // Создаем каталог для хранения изображений, если его нет.
   // И отдельно (чтобы сработало на старых Windows) задаем права
   $imgDir="Gallery";
   $is=ReCreateDir($imgDir,0777,'для 0777');
   echo '<br>***'.substr(sprintf('%o', fileperms($imgDir)), -4).'***<br>'; 
   if ($is<>Ok)
   {
      return $is;
   }
   else 
   {
      // Обрабатываем ошибку при переброске файла на сервер с несуществующим каталогом
      $upload = new ttools\UploadToServer($_SERVER['DOCUMENT_ROOT'].'/'.'i'.$imgDir.'/');
      $MessUpload=$upload->move(); 
      if ($MessUpload<>Ok) echo $MessUpload; 
      if ($thiss!==NULL) $thiss->assertNotEqual($MessUpload,Ok);
      if ($thiss!==NULL) $thiss->assertEqual($MessUpload,'[TPhpTools] Каталог для загрузки файла отсутствует');
      OkMessage();
      
      // Выполняем "успешную" переброску файла на сервер с верным каталогом
      $upload = new ttools\UploadToServer($_SERVER['DOCUMENT_ROOT'].'/'.$imgDir.'/');
      $MessUpload=$upload->move(); echo $MessUpload;
      if ($thiss!==NULL) $thiss->assertEqual($MessUpload,Ok);
      OkMessage();

      // Пытаемся выполнить переброску файла в каталог, защищенный от записи
      $is=ReCreateDir($imgDir,0555,'для 0555');
      if ($is<>Ok)
      {
         return $is;
      }
      else 
      {
         // Трассируем режимы каталога
         echo '<br>***'.substr(sprintf('%o', fileperms($imgDir)), -4).'***<br>';
         
         // Пробовать и отладить режим destroi...
         
         return Ok;
      }
   }
   /*

   $upload = new ttools\UploadToServer($_SERVER['DOCUMENT_ROOT'].'/'.$imgDir.'/');
   $MessUpload=$upload->move(); echo $MessUpload;
   //if ($thiss!==NULL) $thiss->assertEqual($MessUpload,'[TPhpTools] Каталог для загрузки файла отсутствует');
   OkMessage();
   */
   
}

// ****************************************** TUploadToServer_Construct.php ***
