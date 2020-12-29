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
   
   // Начинаем div контроля тестов классов
   echo 
      '<div id="ControlDiv">'.
      '<!-- <form id="fControl"  action="'.$SpecSite.'"> -->';
   // Выводим меню запуска тестов классов (начальный вариант)
   echo 
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
      '</div>';
   
   echo '<br>';

   // Выводим меню запуска тестов классов 
   echo 
      '<div class="container">'.
      '<ul>';
   foreach ($arr as $k => $v) 
   {
      $classTT=$k;
      echo 
      '<li id=li'.$classTT.' class="dropdown">'.
      '<a href="'.$SpecSite.'/?test='.$classTT.'" data-toggle="dropdownA">T'.$classTT.
      '<span><i class="fa fa-snowflake-o" aria-hidden="true"></i></span>'.
      '</a>'.
      '</li>';
   }
   
   /*
   <div class="container">
   <ul>
      <li id=liBaseMaker class="dropdown">
         <a href="http://localhost:99/?test=BaseMaker" data-toggle="dropdown">TBaseMaker</a>
      </li>
      <li id=liCtrlDir class="dropdown">
         <a href="http://localhost:99/?test=CtrlDir" data-toggle="dropdown">TCtrlDir 
         <i class="fa fa-snowflake-o" aria-hidden="true"></i>
         </a>
      </li>
         
         <li id=liDownloadFromServer class="dropdown"><a href="http://localhost:99/?test=DownloadFromServer" data-toggle="dropdown">TDownloadFromServer</a></li><li id=liFixLoadTimer class="dropdown"><a href="http://localhost:99/?test=FixLoadTimer" data-toggle="dropdown">TFixLoadTimer</a></li><li id=liPageStarter class="dropdown"><a href="http://localhost:99/?test=PageStarter" data-toggle="dropdown">TPageStarter</a></li><li id=liUploadToServer class="dropdown"><a href="http://localhost:99/?test=UploadToServer" data-toggle="dropdown">TUploadToServer</a></li></ul></div><!-- </form> --><div id="InfoLeft">Computer Гость 12.3494[3494]</div></div>
   */
   
   
   
   echo 
      '</ul>'.
      '</div>';

   //
   echo
      '<!-- </form> -->'.
            
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
