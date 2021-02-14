<?php
                                         
// PHP7/HTML5, EDGE/CHROME                         *** TBaseMaker__test.php ***

// ****************************************************************************
// * TPhpTools                                       Тест класса TBaseMaker - *
// *                               обслуживателя баз данных SQLite3 через PDO *
// *                                                                          *
// * v1.0, 30.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  18.12.2020 *
// ****************************************************************************

require_once $TPhpTools."/TPhpToolsTests/T".$classTT."_CreateBaseTest.php";
require_once $TPhpTools."/TPhpToolsTests/T".$classTT."_ValueRow.php";
require_once $TPhpTools."/TPhpToolsTests/T".$classTT."_Query.php";
require_once $TPhpTools."/TPhpToolsTests/T".$classTT."_UpdateInsert.php";

// ****************************************************************************
// *            Проверить существование и удалить файл базы данных            *
// ****************************************************************************
function UnlinkFileBase($filename)
{
   if (file_exists($filename)) 
   {
      if (!unlink($filename))
      {
         // Выводим сообщение о неудачном удалении файла базы данных в случаях:
         // а) база данных подключена к стороннему приложению;
         // б) база данных еще привязана к другому объекту класса;
         // в) прочее
         throw new Exception("Не удалось удалить тестовую базу данных $filename!");
      } 
   } 
}
// ****************************************************************************
// *                         Выполнить тесты TBaseMaker                       *
// ****************************************************************************
class test_TBaseMaker extends UnitTestCase 
{
   function test_TBaseMaker_Simple()
   {
      echo '<div id="TestsDiv">';
      MakeTitle("TBaseMaker",'');
      // Тестовое исключение: деление на ноль
      // $i=0; $j=5/$i; echo '$j';
      // Начинаем протоколировать тесты
      SimpleMessage();
      // Проверяем существование и удаляем файл базы данных 
      $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
      UnlinkFileBase($filename);

      // Заново создаем базу данных и подключаем к ней TBaseMaker
      PointMessage('Создается тестовая база данных');  // "Калорийность некоторых продуктов"
      $pathBase='sqlite:'.$filename; 
      $username='tve';
      $password='23ety17';                                         
      CreateBaseTest($pathBase,$username,$password);
      $db = new ttools\BaseMaker($pathBase,$username,$password);
      OkMessage();
    
      // Тестируем Values, Rows методы
      test_ValueRow($db,$this);
      // Тестируем метод sql
      unset($db); // удалили объект класса
      UnlinkFileBase($filename);
      $db = new ttools\BaseMaker($pathBase,$username,$password);
      test_Query($db,$this);
      
      // Тестируем Update, Insert методы
      //test_UpdateInsert($db,$this);
   
      echo '</div>';
  }
}

// *************************************************** TBaseMaker__test.php ***
