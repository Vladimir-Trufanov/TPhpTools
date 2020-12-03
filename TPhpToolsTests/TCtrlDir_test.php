<?php

// PHP7/HTML5, EDGE/CHROME                            *** TCtrlDir_test.php ***
// ****************************************************************************
// * TPhpPrown          Тест класса TCtrlDir - контроллера каталогов и файлов *
// ****************************************************************************
// *                                                                          *
// * v1.0, 03.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  03.12.2020 *
// ****************************************************************************

class test_TCtrlDir extends UnitTestCase 
{
   // Здесь все должно хорошо найтись в своих позициях
   function test_TCtrlDir_Simple()
   {
      MakeTitle('TCtrlDir','');
      $string='Это строка для проверки функции Findes';
      $preg='/Это/u';
      $prefix='Findes("'.$preg.'","'.$string.'"); ';
      $Result=prown\Findes($preg,$string);
      $this->assertEqual('Это',$Result);
      MakeTestMessage($prefix,'Подстрока '.'"Это"'.' найдена в строке',80);
   }
}
// ****************************************************** TCtrlDir_test.php ***
