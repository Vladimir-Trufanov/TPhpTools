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

      PointMessage('Создается тестовая база данных');  // "Калорийность некоторых продуктов"
      CreateBaseTest();
      $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
      $pathBase='sqlite:'.$filename; 
      $username='tve';
      $password='23ety17';                                         
      $db = new ttools\BaseMaker($pathBase,$username,$password);
      OkMessage();
      
      PointMessage('Проверяются методы queryValue(s) по запросам без параметров');
      $sql='SELECT COUNT(*) FROM vids';

      $sign=2;
      $count=$db->queryValue($sql);
      $this->assertEqual($count,2);
      
      // $arr=array('BaseMaker'=>'notest'); - ассоциативный массив
      // $arr=array(1,2,3);                 - простой список значений
      $sign=array(2);
      $count = $db->queryValues($sql);
      $this->assertEqual($count,$sign);

      $sql='SELECT vid FROM vids';
      $sign=array('фрукты','ягоды');
      $list = $db->queryValues($sql);
      $this->assertEqual($list,$sign);
      OkMessage();
   
      // Выполняем методы queryValue(s) с подготовленными запросами и с 
      // передачей именованных переменных в массиве входных значений
      PointMessage('Проверяются queryValue(s) по запросам с именованными параметрами');
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
      OkMessage();

      // Выполняем методы queryValue(s) с подготовленными запросами и передачей 
      // безымянных (неименованных) переменных в массиве входных значений
      PointMessage('Проверяются queryValue(s) по запросам с безымянными параметрами');
      $sql='SELECT COUNT(*) FROM produkts WHERE calories < ?';
      $calories = 81;
      $parm=array($calories);

      $sign=5;
      $count=$db->queryValue($sql,$parm);
      $this->assertEqual($count,$sign);

      $sign=array(5);
      $count=$db->queryValues($sql,$parm);
      $this->assertEqual($count,$sign);

      $sql='SELECT COUNT(*) FROM produkts WHERE calories>=? AND [id-vid] = ?';
      $calories = 41; $idvid=2;
      $parm=array($calories, $idvid);

      $sign=3;
      $count=$db->queryValue($sql,$parm);
      $this->assertEqual($count,$sign);

      $sign=array(3);
      $count=$db->queryValues($sql,$parm);
      $this->assertEqual($count,$sign);

      $sql='SELECT name FROM produkts WHERE calories>=? AND [id-vid] = ?';
      $calories = 41; $idvid=2;
      $parm=[$calories, $idvid];
      $sign=['голубика','брусника','рябина'];
      $list = $db->queryValues($sql,$parm);
      $this->assertEqual($list,$sign);
      OkMessage();

      // Делаем выборку по одной записи метода queryRow
      PointMessage('Выполняются проверки метода queryRow выборками записей');
      // Делаем запрос на всю базу, метод возвращает первую запись
      $sql='SELECT * FROM produkts';
      $prod1=$db->queryRow($sql);
      $sign=['name'=>'голубика','id-colour'=>2,'calories'=>41,'id-vid'=>2];
      $this->assertEqual($prod1,$sign);
      // Делаем запрос по именованному ключу
      $sql='SELECT * FROM produkts WHERE name=:name';
      $prod1=$db->queryRow($sql, array(':name' => 'рябина'));
      $prod2=$db->queryRow($sql, [':name' => 'рябина']);
      $this->assertEqual($prod1,$prod2);
      // Делаем запрос по не именованному ключу
      $sql='SELECT * FROM produkts WHERE name=?';
      $prod2=$db->queryRow($sql, array('виноград'));
      $sign=array('name'=>'виноград','id-colour'=>5,'calories'=>70,'id-vid'=>1);
      $this->assertEqual($prod2,$sign);
      // Делаем поименный запрос
      $sql='SELECT name,[id-colour],calories,[id-vid] FROM produkts WHERE name=?';
      $prod1=$db->queryRow($sql,['виноград']);
      $this->assertEqual($prod2,$prod1);
      // Делаем реляционный однострочный набор данных по запросу из трех таблиц
      $sql='SELECT COUNT(*) FROM produkts,vids,colours '.
           'WHERE name=? and produkts.[id-vid]=vids.[id-vid] and produkts.[id-colour]=colours.[id-colour]';
      $prod1=$db->queryRow($sql,['земляника']);
      $this->assertEqual($prod1,['COUNT(*)' => 1]);

      $sql='SELECT name,colours.colour,calories,vid FROM produkts,vids,colours '.
           'WHERE name=? and produkts.[id-vid]=vids.[id-vid] and produkts.[id-colour]=colours.[id-colour]';
      $prod1=$db->queryRow($sql,['груши']);
      $this->assertEqual($prod1,['name'=>'груши','colour'=>'жёлтые','calories'=>42,'vid'=>'фрукты']);

      print_r($prod1);
      OkMessage();

      //echo '***'.$count.'***';
      //print_r($count);

      echo '</div>';
  }
}
// *************************************************** TBaseMaker__test.php ***
