<?php
// PHP7/HTML5, EDGE/CHROME                                     *** Main.php ***

// ****************************************************************************
// * TPhpTools-test                   Кто прожил жизнь, тот больше не спешит! *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.12.2020
// Copyright © 2020 tve                              Посл.изменение: 23.12.2020

?>
<!DOCTYPE html>
<html>
<head>
   <meta charset="UTF-8">
   <title>TPhpTools-test</title>
   <link rel="stylesheet" href="css/Styles.css">
   <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
</head>

<body>

   <style>
      #liAllTests           > [data-toggle="dropdown"]:before {color: white;}
      #liBaseMaker          > [data-toggle="dropdown"]:before {color: white;}
      #liCtrlDir            > [data-toggle="dropdown"]:before {color: white;}
      #liDownloadFromServer > [data-toggle="dropdown"]:before {color: white;}
      #liFixLoadTimer       > [data-toggle="dropdown"]:before {color: yellow;}
      #liPageStarter        > [data-toggle="dropdown"]:before {color: green;}
      #liUploadToServer     > [data-toggle="dropdown"]:before {color: red;}
   </style>

   <div class="container">
   <ul>
   <li id=liAllTests class="dropdown">
      <input type="checkbox">
      <a href="#" data-toggle="dropdown">Все тесты</a>
   </li>
   <li id="liBaseMaker" class="dropdown">
      <input type="checkbox">
      <a href="#TBaseMaker" data-toggle="dropdown">TBaseMaker</a> 
   </li>
   <li id="liCtrlDir" class="dropdown">
      <input type="checkbox">
      <a href="#" data-toggle="dropdown">TCtrlDir</a>
   </li>
   <li id="liDownloadFromServer" class="dropdown">
      <input type="checkbox">
      <a href="#" data-toggle="dropdown">TDownloadFromServer</a>
   </li>
   <li id="liFixLoadTimer" class="dropdown">
      <input type="checkbox">
      <a href="#" data-toggle="dropdown">TFixLoadTimer</a>
   </li>
   <li id=liPageStarter class="dropdown">
      <input type="checkbox">  
      <a href="#" data-toggle="dropdown">TPageStarter</a>
   </li>
   <li id=liUploadToServer class="dropdown">
      <input type="checkbox">
      <a href="#" data-toggle="dropdown">TUploadToServer</a>
   </li>
   </ul>
   </div>

   <?php
   
   $arr = array(
      'AllTests'           => 'notest',
      'BaseMaker'          => 'notest',
      'CtrlDir'            => 'notest',
      'DownloadFromServer' => 'notest',
      'FixLoadTimer'       => 'check',
      'PageStarter'        => 'yes',
      'UploadToServer'     => 'no');

   echo '<br>';
   foreach ($arr as $k => $v) echo "$k => $v<br>";
      
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
   ?>
   
</body>
</html>
<?php

// <!-- --> ************************************************** MobiSite.php ***
