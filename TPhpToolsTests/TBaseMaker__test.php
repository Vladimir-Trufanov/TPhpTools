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
      OkMessage();

      // Делаем выборки нескольких записей метода queryRows и различными стилями
      PointMessage('Проверяется queryRows различными стилями выборки в набор данных');
      $sql='SELECT name,colours.colour,calories,vid FROM produkts,vids,colours '.
           'WHERE calories>? and produkts.[id-vid]=vids.[id-vid] and produkts.[id-colour]=colours.[id-colour]';
      
      // $fetchStyle=PDO::FETCH_ASSOC (по умолчанию) - массив, индексированный 
      // именами столбцов результирующего набора
      $prod=$db->queryRows($sql,[40]);
      $sign=array( 
         0=>array('name'=>'голубика','colour'=>'голубые',  'calories'=>41,'vid'=>'ягоды'), 
         1=>array('name'=>'брусника','colour'=>'красные',  'calories'=>41,'vid'=>'ягоды'), 
         2=>array('name'=>'груши',   'colour'=>'жёлтые',   'calories'=>42,'vid'=>'фрукты'),
         3=>array('name'=>'рябина',  'colour'=>'оранжевые','calories'=>81,'vid'=>'ягоды'),
         4=>array('name'=>'виноград','colour'=>'зелёные',  'calories'=>70,'vid'=>'фрукты')
      );
      $this->assertEqual($prod,$sign);
      
      // $fetchStyle=PDO::FETCH_BOTH - массив, индексированный именами столбцов 
      // результирующего набора, а также их номерами (начиная с 0)
      $prod=$db->queryRows($sql,[40],PDO::FETCH_BOTH);
      $sign=array(
         0=>array('name'=>'голубика',0=>'голубика','colour'=>'голубые',  1=>'голубые',  'calories'=>41,2=>41,'vid'=>'ягоды', 3=>'ягоды'),
         1=>array('name'=>'брусника',0=>'брусника','colour'=>'красные',  1=>'красные',  'calories'=>41,2=>41,'vid'=>'ягоды', 3=>'ягоды'),
         2=>array('name'=>'груши',   0=>'груши',   'colour'=>'жёлтые',   1=>'жёлтые',   'calories'=>42,2=>42,'vid'=>'фрукты',3=>'фрукты'),
         3=>array('name'=>'рябина',  0=>'рябина',  'colour'=>'оранжевые',1=>'оранжевые','calories'=>81,2=>81,'vid'=>'ягоды', 3=>'ягоды'),
         4=>array('name'=>'виноград',0=>'виноград','colour'=>'зелёные',  1=>'зелёные',  'calories'=>70,2=>70,'vid'=>'фрукты',3=>'фрукты'),
      );
      $this->assertEqual($prod,$sign);
      
      // $fetchStyle=PDO::FETCH_BOUND: возвращает true и присваивает значения 
      // столбцов результирующего набора переменным PHP, которые были привязаны
      // к этим столбцам методом PDOStatement::bindColumn()
      $prod=$db->queryRows($sql,[40],PDO::FETCH_BOUND);
      $sign=array(0=>1,1=>1,2=>1,3=>1,4=>1);
      $this->assertEqual($prod,$sign);
      
      // $fetchStyle=PDO::FETCH_CLASS: создаёт и возвращает объект запрошенного
      // класса, присваивая значения столбцов результирующего набора именованным
      // свойствам класса, и следом вызывает конструктор, если не задан 
      // PDO::FETCH_PROPS_LATE. Если fetch_style включает в себя атрибут 
      // PDO::FETCH_CLASSTYPE (например, PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE),
      // то имя класса, от которого нужно создать объект, будет взято из первого столбца
      // 
      // здесь без указания класса выводится сообщение:
      // message [SQLSTATE[HY000]: General error: fetch mode requires the classname argument]
      
      // $fetchStyle=PDO::FETCH_INTO: обновляет существующий объект запрошенного
      // класса, присваивая значения столбцов результирующего набора именованным 
      // свойствам объекта
      // 
      // здесь выводится сообщение:
      // message [SQLSTATE[HY000]: General error: fetch mode requires the object parameter]
      
      // $fetchStyle=PDO::FETCH_LAZY: комбинирует PDO::FETCH_BOTH и PDO::FETCH_OBJ,
      // создавая новый объект со свойствами, соответствующими именам столбцов 
      //результирующего набора
      // 
      // здесь выводится сообщение:
      // message [SQLSTATE[HY000]: General error: PDO::FETCH_LAZY can't be used 
      // with PDOStatement::fetchAll()]
      
      // $fetchStyle=PDO::FETCH_NAMED: возвращает массив такого же вида, как и 
      // PDO::FETCH_ASSOC, но, если есть несколько полей с одинаковым именем, 
      // то значением с этим ключом будет массив со всеми значениями из рядов, 
      // в которых это поле указано.
      $prod=$db->queryRows($sql,[40],PDO::FETCH_NAMED);
      $sign=array( 
         0=>array('name'=>'голубика','colour'=>'голубые',  'calories'=>41,'vid'=>'ягоды'), 
         1=>array('name'=>'брусника','colour'=>'красные',  'calories'=>41,'vid'=>'ягоды'), 
         2=>array('name'=>'груши',   'colour'=>'жёлтые',   'calories'=>42,'vid'=>'фрукты'),
         3=>array('name'=>'рябина',  'colour'=>'оранжевые','calories'=>81,'vid'=>'ягоды'),
         4=>array('name'=>'виноград','colour'=>'зелёные',  'calories'=>70,'vid'=>'фрукты')
      );
      $this->assertEqual($prod,$sign);
      
      // $fetchStyle=PDO::FETCH_NUM: возвращает массив, индексированный номерами
      // столбцов (начиная с 0).
      $prod=$db->queryRows($sql,[40],PDO::FETCH_NUM);
      $sign=array(
         0=>array(0=>'голубика',1=>'голубые',  2=>41,3=>'ягоды'),
         1=>array(0=>'брусника',1=>'красные',  2=>41,3=>'ягоды'),
         2=>array(0=>'груши',   1=>'жёлтые',   2=>42,3=>'фрукты'),
         3=>array(0=>'рябина',  1=>'оранжевые',2=>81,3=>'ягоды'),
         4=>array(0=>'виноград',1=>'зелёные',  2=>70,3=>'фрукты')
      );
      $this->assertEqual($prod,$sign);
      
      // тест с LIKE ?
      $sql='SELECT name,colours.colour,calories,vid FROM produkts,vids,colours '.
           'WHERE calories>? '.
           'and produkts.[id-vid]=vids.[id-vid] '.
           'and produkts.[id-colour]=colours.[id-colour] '.
           'and name LIKE ?';
      $prod=$db->queryRows($sql,[40,'%ру%']);
      $sign=array( 
         0=>array('name'=>'брусника','colour'=>'красные',  'calories'=>41,'vid'=>'ягоды'), 
         1=>array('name'=>'груши',   'colour'=>'жёлтые',   'calories'=>42,'vid'=>'фрукты')
      );
      print_r($prod);
      $this->assertEqual($prod,$sign);
      OkMessage();

      //echo '***'.$count.'***';
      //print_r($count);

      echo '</div>';
  }
}
// *************************************************** TBaseMaker__test.php ***
