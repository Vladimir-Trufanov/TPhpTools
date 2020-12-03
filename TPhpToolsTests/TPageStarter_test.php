<?php

// PHP7/HTML5, EDGE/CHROME                        *** TPageStarter_test.php ***
// ****************************************************************************
// * TPhpPrown                            Тест класса TPageStarter - cтартера *
// *                   сессии страницы и регистратора пользовательских данных *
// ****************************************************************************
// *                                                                          *
// * v1.0, 03.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  03.12.2020 *
// ****************************************************************************

class test_TPageStarter extends UnitTestCase 
{
   // Здесь строка представлена, как число. Ожидалось исключение, но 
   // поиск обработан. Пусть так и остается.
   function test_TPageStarter_First()
   {
      MakeTitle('TPageStarter','');
      $string=1234;
      $preg="/12/u";
      $prefix='$string=1234; '.'$preg="/12/u"; '.'Findes($preg,$string,$point); ';
      $Result=prown\Findes($preg,$string,$point);
      $this->assertEqual($point,0);
      MakeTestMessage($prefix,'Поиск в строке, представленной как число, обработан!',80);
  }
}
// ************************************************** TPageStarter_test.php ***
