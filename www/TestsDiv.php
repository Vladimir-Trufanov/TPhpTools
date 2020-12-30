<?php
// PHP7/HTML5, EDGE/CHROME                               *** TestsDiv.php ***

// ****************************************************************************
// * TPhpTools-test                          Развернуть правую часть экрана - *
// *                                                  выполнить заданный тест *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 30.12.2020

echo '<div id="TestsDiv">';

// Принимаем команду на запуск тестов
$classTT=prown\getComRequest('test');
if ($classTT===NULL)
{
   $classTT='NULL';
   echo 'Класс для тестирования не выбран!<br>';
   echo $SpecSite;
}
else 
{
   // Выводим меню для возврата в контрольное меню тестов
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
   echo
      '<div id="InfoRight">'.
      $SiteDevice." ".$c_PersName." ".$_SESSION['Counter'].".".$c_PersEntry."[".$c_BrowEntry."]". 
      '</div>';
   require_once "Proba.php";
}
echo 
   '</div>';
// Если тест не выбран, возвращаемся в контрольное меню   
if ($classTT===NULL)
{
   Header("Location: http://".$_SERVER['HTTP_HOST'].$SpecSite);
}


// <!-- --> ************************************************** TestsDiv.php ***
