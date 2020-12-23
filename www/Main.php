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
<html >
<head>
  <meta charset="UTF-8">
  <title>Only CSS3 Dropdown Menu</title>
  
 <!-- 
      <link rel="stylesheet" href="css/Styles.css">
  --> 
  
</head>

<body>
  <div class="container">
  <h1 class="title">Dropdown Menu</h1>
  <ul>
    <li class="dropdown">
      <input type="checkbox" />
      <a href="#" data-toggle="dropdown">First Menu</a>
      <ul class="dropdown-menu">
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </li>
    <li class="dropdown">
      <input type="checkbox" />
      <a href="#" data-toggle="dropdown">Second Menu</a>
      <ul class="dropdown-menu">
        <li>
            <input type="checkbox" />
            <a href="#">Home</a>
        </li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </li>
    <li class="dropdown">
      <input type="checkbox" />
      <a href="#" data-toggle="dropdown">Third Menu</a>
      <ul class="dropdown-menu">
        <li><a href="#">Home</a></li>
        <li><a href="#">About Us</a></li>
        <li><a href="#">Services</a></li>
        <li><a href="#">Contact</a></li>
      </ul>
    </li>
  </ul>
</div>
  
  
</body>
</html>
<?php

// <!-- --> ************************************************** MobiSite.php ***
