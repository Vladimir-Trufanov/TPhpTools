<?php
// PHP7/HTML5, EDGE/CHROME                                    *** Proba.php ***

// ****************************************************************************
// * TPhpTools-test                          Развернуть правую часть экрана - *
// *                                                  выполнить заданный тест *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 30.12.2020



//function probatest($classTT)
//{
class test_Findes extends UnitTestCase 
{
   // Здесь все должно хорошо найтись в своих позициях
   function test_Findes_Simple()
   {
      echo '<div id="TestsDiv">';

      MakeTitle("Findes",'');
      $string='Это строка для проверки функции Findes';
      $preg='/Это/u';
      $prefix='Findes("'.$preg.'","'.$string.'"); ';
      $Result=prown\Findes($preg,$string);
      $this->assertEqual('Это',$Result);
      
      MakeTestMessage($prefix,'Подстрока '.'"Это"'.' найдена в строке',80);
      
      $prefix='Findes("'.$preg.'","'.$string.'",$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,0);
      MakeTestMessage($prefix,'Фрагмент '.'"Это"'.' найден с позиции 0',80);
      
      
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
      
  }
}
//}

//echo $SiteHost."/TPhpTools/TPhpToolsTests/T".$classTT."_test.php";
//require_once $SiteHost."/TPhpTools/TPhpToolsTests/T".$classTT."_test.php";

//echo 'Класс: '.$classTT.'<br>';

// <!-- --> ************************************************** TestsDiv.php ***
