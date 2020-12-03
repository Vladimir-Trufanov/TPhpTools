<?php

// PHP7/HTML5, EDGE/CHROME                              *** Findes_test.php ***
// ****************************************************************************
// * TPhpPrown             Тест функции Findes - Выбрать из строки подстроку, *
// *                                    соответствующую регулярному выражению *
// ****************************************************************************
// *                                                                          *
// * v1.1, 23.05.2019                              Автор:       Труфанов В.Е. *
// * Copyright © 2019 tve                          Дата создания:  02.04.2019 *
// ****************************************************************************

class test_Findes extends UnitTestCase 
{
   // Здесь все должно хорошо найтись в своих позициях
   function test_Findes_Simple()
   {
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
      
      $preg="/Findes/u";
      $prefix='Findes("'.$preg.'","'.$string.'",$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,59);     // 59 позиция, а не 32, так как UTF8
      $this->assertNotEqual($point,32);  // если бы не UTF8
      MakeTestMessage($prefix,'Фрагмент '.'"Findes"'.' найден с байта 59 (не с 32, так как UTF8)',80);
  }
   // Здесь поиск неудачный
   function test_Findes_Nofind()
   {
      $string='Это строка для проверки функции Findes';
      $preg="/".'строк'."/u";
      $prefix='Findes("'.$preg.'","'.$string.'",$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,7);
      MakeTestMessage($prefix,'Фрагмент '.'"строк"'.' найден с 7 позиции',80);
      
      $preg="/".'строки'."/u";
      $prefix='Findes("'.$preg.'","'.$string.'",$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,-1);
      $this->assertEqual('',$Result);
      $this->assertEqual($Result,'');
      MakeTestMessage($prefix,'Фрагмент '.'"строки"'.' не найден',80);
  }
   // Здесь строка представлена, как число. Ожидалось исключение, но 
   // поиск обработан. Пусть так и остается.
   function test_Findes_Except()
   {
      $string=1234;
      $preg="/12/u";
      $prefix='$string=1234; '.'$preg="/12/u"; '.'Findes($preg,$string,$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,0);
      MakeTestMessage($prefix,'Поиск в строке, представленной как число, обработан!',80);
  }
}
// ******************************************************** Findes_test.php ***
