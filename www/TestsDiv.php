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
   
   // Выводим меню запуска тестов классов
   echo 
      '<div id="TestsDiv">'.
      '<form id="fImg"  action="'.$SpecSite.'">'.
      '<div class="container">'.
      '<ul>';
   foreach ($arr as $k => $v) 
   {
      $classTT=$k;
      echo 
      '<li id=li'.$classTT.' class="dropdown">'.
      '<input type="checkbox" name="test" value="'.$classTT.'">'.
      '<a href="'.$SpecSite.'/?control='.$classTT.'" data-toggle="dropdown">T'.$classTT.'</a>'.
      '</li>';
   }
   echo 
      '</ul>'.
      '</div>'.
      '</form>'.
      
      '<div id="InfoRight">'.
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
   if ($arrj===NULL) {echo 'Неопределенное обратное преобразование json'.'<br>';}
   else if ($arrj===false) {echo 'Ошибка обратного преобразования json'.'<br>';}
   else 
   {
      //var_dump($arrj);
      //echo '<br><br>';
      //foreach ($arrj as $k => $v) echo "$k => $v<br>";
   }

// <!-- --> ************************************************** TestsDiv.php ***
