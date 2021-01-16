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

      PointMessage('Создается база данных: "Калорийность некоторых продуктов"');
      CreateBaseTest();
      $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
      $pathBase='sqlite:'.$filename; 
      $username='tve';
      $password='23ety17';                                         
      $db = new ttools\BaseMaker($pathBase,$username,$password);
      OkMessage();
      
      PointMessage('Определяется количество видов продуктов');
      $sql='SELECT COUNT(*) FROM vids';

      $sign=2;
      $count=$db->queryValue($sql);
      $this->assertEqual($count,2);

      // $arr=array('BaseMaker'=>'notest'); - ассоциативный массив
      // $arr=array(1,2,3);                 - простой список значений
      $sign=array(2);
      $count = $db->queryValues($sql);
      $this->assertEqual($count,$sign);
      OkMessage();

      PointMessage('Определяется количество видов продуктов в диапазоне калорий');
      // Выполнение подготовленного запроса с передачей 
      // именованных переменных в массиве входных значений
      $calories = 81;
      $sql='SELECT COUNT(*) FROM produkts WHERE calories<:calories';
      $parm=array(':calories' => $calories);

      $sign=5;
      $count=$db->queryValue($sql,$parm);
      $this->assertEqual($count,$sign);
      $sign=array(5);
      $count=$db->queryValues($sql,$parm);
      $this->assertEqual($count,$sign);

      $calories = 41;
      $idvid=2;
      $sql='SELECT COUNT(*) FROM produkts WHERE calories>=:calories AND [id-vid] = :idvid';
      $parm=array(':calories' => $calories, ':idvid' => $idvid);

      $sign=3;
      $count=$db->queryValue($sql,$parm);
      $this->assertEqual($count,$sign);
      $sign=array(3);
      $count=$db->queryValues($sql,$parm);
      $this->assertEqual($count,$sign);
      // Выполнение подготовленного запроса с передачей 
      // безымянных (неименованных) переменных в массиве входных значений
      $calories = 81;
      $sql='SELECT COUNT(*) FROM produkts WHERE calories < ?';
      $parm=array($calories);

      $sign=5;
      $count=$db->queryValue($sql,$parm);
      $this->assertEqual($count,$sign);
      $sign=array(5);
      $count=$db->queryValues($sql,$parm);
      $this->assertEqual($count,$sign);

      $calories = 41;
      $idvid=2;
      $sql='SELECT COUNT(*) FROM produkts WHERE calories>=? AND [id-vid] = ?';
      $parm=array($calories, $idvid);

      $sign=3;
      $count=$db->queryValue($sql,$parm);
      $this->assertEqual($count,$sign);
      $sign=array(3);
      $count=$db->queryValues($sql,$parm);
      $this->assertEqual($count,$sign);
      OkMessage();

      //echo '***'.$count.'***';
      //print_r($count);

// $sql='SELECT COUNT(*) FROM produkts WHERE  calories < :calories AND id-colour = :id-colour');

//
//'SELECT name, colour, calories FROM fruit WHERE calories < ? AND colour = ?'
      
      echo '</div>';

      
      
      
      

      
  }

  
}


// *************************************************** TBaseMaker__test.php ***
