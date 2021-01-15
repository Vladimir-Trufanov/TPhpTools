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
      $count = $db->queryValue('SELECT COUNT(*) FROM vids');
      $this->assertEqual($count,2);
      
      // $arr=array('BaseMaker' => 'notest'); - ассоциативный массив
      // $arr=array(1,2,3);                   - простой список значений
      $sign=array(2);
      $count = $db->queryValues('SELECT COUNT(*) FROM vids');
      $this->assertEqual($count,$sign);
      //print_r($count);
      OkMessage();
      
      echo '</div>';

      
      /*
      // Проверяем, есть ли тестовая база данных, для того чтобы ее удалить
      // и начать тест
      $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
      echo $filename.'<br>';
      if (file_exists($filename)) 
      {
         echo "The file $filename exists<br>";
      } 
      else 
      {
         echo "The file $filename does not exist<br>";
      }

      $string='Это строка для проверки функции Findes';
      $preg='/Это/u';
      $prefix='Findes("'.$preg.'","'.$string.'"); ';
      $Result=prown\Findes($preg,$string);
      $this->assertEqual('Это',$Result);
      
      //MakeTestMessage($prefix,'Подсtttтрока '.'"Это"'.' найдена в строке',80);
      echo $prefix.'***Подсtttтрока '.'"Это"'.' найдена в строке***<br>';
      
      $prefix='Findes("'.$preg.'","'.$string.'",$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,0);
      MakeTestMessage($prefix,'Фрагмtttент '.'"Это"'.' найден с позиции 0',80);
      
      
      //$i=0;
      //$j=5/$i;
      //echo $j;
      
      $preg="/Findes/u";
      $prefix='Findes("'.$preg.'","'.$string.'",$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,59);     // 59 позиция, а не 32, так как UTF8
      $this->assertNotEqual($point,32);  // если бы не UTF8
      MakeTestMessage($prefix,'Фрагмент '.'"Findes"'.' найден с байта 59 (не с 32, так как UTF8)',80);
      echo '</div>';
      */
      
      
      
      

      
  }

  
}


// *************************************************** TBaseMaker__test.php ***
