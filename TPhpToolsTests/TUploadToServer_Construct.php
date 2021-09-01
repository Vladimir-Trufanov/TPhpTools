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
   
   // Пытаемся выполнить переброску файла в каталог, защищенный от записи
   // 01.09.2021 - не получается этот кусок!
   /*
   $imgDir="Gallery000";
   $is=CreateDir($imgDir,0000);
   if ($is<>Ok)
   {
      return $is;
   }
   else 
   {
      $upload = new ttools\UploadToServer($_SERVER['DOCUMENT_ROOT'].'/'.$imgDir.'/');
      $MessUpload=$upload->move(); echo $MessUpload;
      //if ($thiss!==NULL) $thiss->assertEqual($MessUpload,'[TPhpTools] Каталог для загрузки файла отсутствует');
      // Трассируем режимы каталога
      echo '<br>**=*'.substr(sprintf('%o', fileperms($imgDir)), -4).'*=**<br>';
      OkMessage();
      return Ok;
   }
   */
   // Создаем каталог для хранения изображений, если его нет.
   // И отдельно (чтобы сработало на старых Windows) задаем права
   $imgDir="Gallery777";
   $is=CreateDir($imgDir,0777);
   //echo '<br>***'.substr(sprintf('%o', fileperms($imgDir)), -4).'***<br>'; 
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
      // '[TUploadToServer] Каталог для загрузки файла отсутствует');
      if ($thiss!==NULL) $thiss->assertTrue(strpos($MessUpload,DirDownloadMissing)); 
      OkMessage();
      
      /*
      // Выполняем "успешную" переброску файла на сервер с верным каталогом
      $upload = new ttools\UploadToServer($_SERVER['DOCUMENT_ROOT'].'/'.$imgDir.'/');
      $MessUpload=$upload->move(); echo $MessUpload;
      //if ($thiss!==NULL) $thiss->assertEqual($MessUpload,Ok);
      OkMessage();
      */
   }
}

// ****************************************** TUploadToServer_Construct.php ***
