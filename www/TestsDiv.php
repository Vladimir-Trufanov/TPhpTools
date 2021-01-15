<?php
// PHP7/HTML5, EDGE/CHROME                               *** TestsDiv.php ***

// ****************************************************************************
// * TPhpTools-test                          Развернуть правую часть экрана - *
// *                                                  выполнить заданный тест *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 30.12.2020

// Принимаем команду на запуск тестов
$classTT=prown\getComRequest('test');
echo '<div class="container">';
if ($classTT===NULL)
{
   $classTT='NULL';
   echo 'Класс для тестирования не выбран!<br>';
}
else 
{
   require_once($SiteHost.'/TSimpleTest/autorun.php');
   require_once $TPhpTools."/TPhpToolsTests/T".$classTT."__test.php";
   //require_once "Proba.php";
   //probatest($classTT);

   // Выводим меню для возврата в контрольное меню тестов
   echo '<ul>';
   echo 
      '<li id=li'.$classTT.' class="dropdown">'.
      '<input type="checkbox" name="test" value="'.$classTT.'">'.
      '<a href="'.$SpecSite.'/?control='.$classTT.'" data-toggle="dropdown">T'.$classTT.'</a>'.
      '</li>';
   echo '</ul>';

   //echo
   //   '<div id="InfoRight">'.
   //   $SiteDevice." ".$c_PersName." ".$_SESSION['Counter'].".".$c_PersEntry."[".$c_BrowEntry."]". 
   //   '</div>';
   // Возвращаемся в меню выбора тестов
   // echo "****".$SpecSite.'****';
  // Header("Location: ".$SpecSite);
   
}   
echo '</div>';



 /*
}
echo 
   '</div>';
// Если тест не выбран, возвращаемся в контрольное меню   
if ($classTT===NULL)
{
   Header("Location: http://".$_SERVER['HTTP_HOST'].$SpecSite);
}
*/

// <!-- --> ************************************************** TestsDiv.php ***
