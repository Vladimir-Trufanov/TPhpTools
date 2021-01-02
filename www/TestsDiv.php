<?php
// PHP7/HTML5, EDGE/CHROME                               *** TestsDiv.php ***

// ****************************************************************************
// * TPhpTools-test                          Развернуть правую часть экрана - *
// *                                                  выполнить заданный тест *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 30.12.2020

$classTT=prown\getComRequest('test');



// Принимаем команду на запуск тестов
//$classTT=prown\getComRequest('test');
if ($classTT===NULL)
{
   $classTT='NULL';
   echo 'Класс для тестирования не выбран!<br>';
   echo $SpecSite;
}
else 
{
   require_once($SiteHost.'/TSimpleTest/autorun.php');
   require_once $TPhpTools."/TPhpToolsTests/T".$classTT."_test.php";
   //require_once "Proba.php";
   //probatest($classTT);

   // Выводим меню для возврата в контрольное меню тестов
   echo $classTT.'<br>';

   echo '<div class="container">';
   echo '<ul>';
   
   echo 
      '<li id=li'.$classTT.' class="dropdown">'.
      '<input type="checkbox" name="test" value="'.$classTT.'">'.
      '<a href="'.$SpecSite.'/?control='.$classTT.'" data-toggle="dropdown">T'.$classTT.'</a>'.
      '</li>';
   echo 
      '</ul>'.
      '</div>';
   //echo
   //   '<div id="InfoRight">'.
   //   $SiteDevice." ".$c_PersName." ".$_SESSION['Counter'].".".$c_PersEntry."[".$c_BrowEntry."]". 
   //   '</div>';
   // Возвращаемся в меню выбора тестов
   // echo "****".$SpecSite.'****';
  // Header("Location: ".$SpecSite);
   
}   
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
