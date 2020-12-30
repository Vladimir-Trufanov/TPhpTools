<?php
                                         
// PHP7/HTML5, EDGE/CHROME                          *** TBaseMaker_test.php ***

// ****************************************************************************
// * TPhpTools                                       Тест класса TBaseMaker - *
// *                               обслуживателя баз данных SQLite3 через PDO *
// *                                                                          *
// * v1.0, 30.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  18.12.2020 *
// ****************************************************************************

class TBaseMaker extends UnitTestCase 
{
   // Здесь все должно хорошо найтись в своих позициях
   function test_TBaseMaker_Simple()
   {
      //echo 'Тестируется TBaseMaker_test.php'.'<br>';
      
      MakeTitle('TCtrlDir','');
      $string='Это строка для проверки функции Findes';
      $preg='/Это/u';
      $prefix='Findes("'.$preg.'","'.$string.'"); ';
      $Result=prown\Findes($preg,$string);
      $this->assertEqual('Это',$Result);
      MakeTestMessage($prefix,'Подстрока '.'"Это"'.' найдена в строке',80);
      
   }
}
// **************************************************** TBaseMaker_test.php ***
