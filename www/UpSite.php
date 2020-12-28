<?php
// PHP7/HTML5, EDGE/CHROME                                   *** UpSite.php ***

// ****************************************************************************
// * * TPhpTools-test    Разобрать параметры запроса и открыть страницу сайта *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 28.12.2020

// ****************************************************************************
// *              Формируем общие начальные теги разметки страницы,           *
// *           разбираем параметры запроса и открываем страницу сайта         *
// ****************************************************************************
echo '<!DOCTYPE html>';
echo '<html lang="ru">';
echo '<head>';
echo '<meta http-equiv="content-type" content="text/html; charset=utf-8"/>';
echo '<meta name="description" content="Труфанов Владимир Евгеньевич, его жизнь и жизнь его близких">';
echo '<meta name="keywords" content="Труфанов Владимир Евгеньевич, жизнь и увлечения">';
// Выводим данные о favicon
echo '
<link rel="manifest" href="manifest.json">
<link rel="apple-touch-icon" sizes="180x180" href="/favicon260x260/apple-touch-icon.png">
<link rel="icon" type="image/png" sizes="32x32" href="/favicon260x260/favicon-32x32.png">
<link rel="icon" type="image/png" sizes="16x16" href="/favicon260x260/favicon-16x16.png">
<link rel="mask-icon" href="/favicon260x260/safari-pinned-tab.svg" color="#5bbad5">
<link rel="shortcut icon" href="/favicon260x260/favicon.ico">
<meta name="msapplication-TileColor" content="#da532c">
<meta name="msapplication-config" content="/favicon260x260/browserconfig.xml">
<meta name="theme-color" content="#ffffff">
';
// Подключаем font-awesome/4.7.0
echo '<link rel="stylesheet"'.
   'href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">';

echo '<title>TPhpTools-test</title>'.
   '<link rel="stylesheet" href="css/Styles.css">';
/*
echo '<link rel="stylesheet" type="text/css"
   href="https://code.jquery.com/ui/1.12.1/themes/ui-lightness/jquery-ui.css">
   <script
      src="https://code.jquery.com/jquery-3.3.1.min.js"
      integrity="sha256-FgpCb/KJQlLNfOu91ta32o/NMZxltwRo8QtmkMRdAu8="
      crossorigin="anonymous">
   </script>
   <script
      src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"
      integrity="sha256-VazP97ZCwtekAsvgPBSUwPFKdrwD3unUfSGVYrahUqU="
      crossorigin="anonymous">
   </script>';
*/

// Начинаем html-страницу
echo '</head>'; 
echo '<body>'; 

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

   echo '<br>';
   foreach ($arr as $k => $v) echo "$k => $v<br>";
   echo '<br>';
   
   // Выводим меню запуска тестов классов
   echo 
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
      '</form>';
      
   $json=json_encode($arr);
   if ($json)
   {
      echo '<br>'.$json.'<br>';
   }
   else 
   {
      echo '<br>'.'Ошибка преобразования json'.'<br>';
   }
   
   $arrj=json_decode($json,true);
   if ($arrj===NULL) {echo 'Неопределенное обратное преобразование json'.'<br>';}
   else if ($arrj===false) {echo 'Ошибка обратного преобразования json'.'<br>';}
   else 
   {
      var_dump($arrj);
      echo '<br><br>';
      foreach ($arrj as $k => $v) echo "$k => $v<br>";
   }

// Выводим завершающие теги страницы
echo '</body>'; 
echo '</html>';

// <!-- --> **************************************************** UpSite.php ***
