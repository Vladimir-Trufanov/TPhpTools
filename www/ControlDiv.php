<?php
// PHP7/HTML5, EDGE/CHROME                               *** ControlDiv.php ***

// ****************************************************************************
// * TPhpTools-test                           Развернуть левую часть экрана - *
// *                                                  меню управления тестами *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 29.12.2020

// ****************************************************************************
// *              ---Формируем общие начальные теги разметки страницы,           *
// *          --- разбираем параметры запроса и открываем страницу сайта         *
// ****************************************************************************
   ?>

   <style>
      #liAllTests           > [data-toggle="dropdown"]:before {color: white;}
      #liBaseMaker          > [data-toggle="dropdown"]:before {color: white;}
      #liCtrlDir            > [data-toggle="dropdown"]:before {color: white;}
      #liDownloadFromServer > [data-toggle="dropdown"]:before {color: white;}
      #liFixLoadTimer       > [data-toggle="dropdown"]:before {color: yellow;}
      #liPageStarter        > [data-toggle="dropdown"]:before {color: green;}
      #liUploadToServer     > [data-toggle="dropdown"]:before {color: red;}
   </style>
   
   <?php
   
   // Готовим форму с меню вызова тестов классов следующего вида
   /*
   ?>
      <form id="fImg" action="http://localhost:99/">
      <div class="container">
      <ul>
      
      <li id="liBaseMaker" class="dropdown">
         <input type="checkbox" name="test" value="BaseMaker">
         <a href="http://localhost:99/?test=BaseMaker" data-toggle="dropdown">TBaseMaker</a>
      </li>

      <li id="liDownloadFromServer" class="dropdown">
         <input type="checkbox" name="test" value="DownloadFromServer">
         <a href="http://localhost:99/?test=DownloadFromServer" data-toggle="dropdown">TDownloadFromServer</a> 
      </li>

      </ul>
      </div>
      </form>
   <?php
   */
   
   $arr = array(
      'BaseMaker'          => 'notest',
      'CtrlDir'            => 'notest',
      'DownloadFromServer' => 'notest',
      'FixLoadTimer'       => 'check',
      'PageStarter'        => 'yes',
      'UploadToServer'     => 'no');

   //echo '<br>';
   //foreach ($arr as $k => $v) echo "$k => $v<br>";
   //echo '<br>';
   
   // Выводим меню запуска тестов классов
   echo 
      '<div id="ControlDiv">'.
      '<form id="fImg"  action="'.$SpecSite.'">'.
      '<div class="container">'.
      '<ul>';
   foreach ($arr as $k => $v) 
   {
      $classTT=$k;
      echo 
      '<li id=li'.$classTT.' class="dropdown">'.
      '<input type="checkbox" name="test" value="'.$classTT.'">'.
      '<a href="'.$SpecSite.'/?test='.$classTT.'" data-toggle="dropdown">T'.$classTT.'</a>'.
      '</li>';
   }
   echo 
      '</ul>'.
      '</div>'.
      '</form>'.
            
      '<div id="InfoLeft">'.
      $SiteDevice." ".$c_PersName." ".$_SESSION['Counter'].".".$c_PersEntry."[".$c_BrowEntry."]". 
      '</div>'.

      '</div>';
      
   $json=json_encode($arr);
   /*
   if ($json)
   {
      echo '<br>'.$json.'<br>';
   }
   else 
   {
      echo '<br>'.'Ошибка преобразования json'.'<br>';
   }
   */
   
   $arrj=json_decode($json,true);
   /*
   if ($arrj===NULL) {echo 'Неопределенное обратное преобразование json'.'<br>';}
   else if ($arrj===false) {echo 'Ошибка обратного преобразования json'.'<br>';}
   else 
   {
      //var_dump($arrj);
      //echo '<br><br>';
      foreach ($arrj as $k => $v) echo "$k => $v<br>";
   }
   */
// <!-- --> ************************************************ ControlDiv.php ***
