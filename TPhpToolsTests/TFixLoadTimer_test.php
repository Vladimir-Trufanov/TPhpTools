<?php

// PHP7/HTML5, EDGE/CHROME                       *** TFixLoadTimer_test.php ***
// ****************************************************************************
// * TPhpPrown                                    Тест класса TFixLoadTimer - *
// *                                   регистратора времени загрузки страницы *
// ****************************************************************************
// *                                                                          *
// * v1.0, 03.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  03.12.2020 *
// ****************************************************************************

class test_TFixLoadTimer extends UnitTestCase 
{
   // Здесь строка представлена, как число. Ожидалось исключение, но 
   // поиск обработан. Пусть так и остается.
   function test_TFixLoadTimer_Except()
   {
      MakeTitle('TFixLoadTimer','');
      $string=1234;
      $preg="/12/u";
      $prefix='$string=1234; '.'$preg="/12/u"; '.'Findes($preg,$string,$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,0);
      MakeTestMessage($prefix,'Поиск в строке, представленной как число, обработан!',80);
  }
}
// ************************************************* TFixLoadTimer_test.php ***
