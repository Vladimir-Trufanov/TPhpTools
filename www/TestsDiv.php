<?php
// PHP7/HTML5, EDGE/CHROME                               *** TestsDiv.php ***

// ****************************************************************************
// * TPhpTools-test                          Развернуть правую часть экрана - *
// *                                                  протоколы работы тестов *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 29.12.2020

// ****************************************************************************
// *              ---Формируем общие начальные теги разметки страницы,           *
// *          --- разбираем параметры запроса и открываем страницу сайта         *
// ****************************************************************************
?>
<div id="TestsDiv">
<!--
<form id="fTests" action="http://localhost:99/">
-->
<div class="container">
   <ul>
   <li class="dropdown">
      <input type="checkbox" name="test" value="BaseMaker">
      <a href="http://localhost:99/?control=BaseMaker" data-toggle="dropdown">TBaseMaker</a>
   </li>
   </ul>
</div>
<!-- 
</form>
-->
<?php
echo
   '<div id="InfoRight">'.
   $SiteDevice." ".$c_PersName." ".$_SESSION['Counter'].".".$c_PersEntry."[".$c_BrowEntry."]". 
   '</div>';
?>
</div>
<?php


// <!-- --> ************************************************** TestsDiv.php ***
