<?php
// PHP7/HTML5, EDGE/CHROME                               *** TestsDiv.php ***

// ****************************************************************************
// * TPhpTools-test                          Развернуть правую часть экрана - *
// *                                                  выполнить заданный тест *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 25.08.2021

// Определяем каталог тестов библиотеки прикладных классов
$TPhpToolsTests=$SiteHost.'/TPhpTools/TPhpToolsTests';
// Строим разметку для проведения тестов
echo '<div class="container">';
// Принимаем команду на запуск тестов
$classTT=prown\getComRequest('test');
if ($classTT===NULL)
{
   $classTT='NULL';
   echo 'Класс для тестирования не выбран!<br>';
}
else 
{
   require_once $TPhpToolsTests."/T_ToolsTestCommon.php";
   // Определяем главный модуль тестирования класса и подключаем вспомогательные
   define ("ScenName",$TPhpToolsTests."/T".$classTT."__test.php");
   if ($classTT=='BaseMaker')
   {
      require_once $TPhpToolsTests."/TBaseMaker_CreateBaseTest.php";
      require_once $TPhpToolsTests."/TBaseMaker_PragmaTest.php";
      require_once $TPhpToolsTests."/TBaseMaker_ValueRow.php";
      require_once $TPhpToolsTests."/TBaseMaker_Query.php";
      require_once $TPhpToolsTests."/TBaseMaker_UpdateInsert.php";
   }
   else if ($classTT=='UploadToServer')
   {
      require_once $TPhpToolsTests."/TUploadToServer_Construct.php";
   }
   // Подключаем и вызываем тестовую оболочку
   require_once($SiteHost.'/TSimpleTest/autorun.php');
   class test_TTools extends UnitTestCase
   {
      function test_TPhpTools()
      {
         echo '<div id="TestsDiv">';
         $shellTest=$this; require_once (ScenName); // запустили главный модуль тестов класса
         echo '</div>';
      }
   }
   // Выводим меню для возврата в контрольное меню тестов
   echo '<ul>';
   echo 
      '<li id=li'.$classTT.' class="dropdown">'.
      '<input type="checkbox" name="test" value="'.$classTT.'">'.
      '<a href="'.$SpecSite.'/?control='.$classTT.'" data-toggle="dropdown">T'.$classTT.'</a>'.
      '</li>';
   echo '</ul>';
}   
echo '</div>';
// <!-- --> ************************************************** TestsDiv.php ***
