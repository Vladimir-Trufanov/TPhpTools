<?php
// PHP7/HTML5, EDGE/CHROME                                     *** Main.php ***

// ****************************************************************************
// * TPhpTools-test                   Кто прожил жизнь, тот больше не спешит! *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.12.2020
// Copyright © 2020 tve                              Посл.изменение: 23.12.2020

/*
?>
<div>

<style>

@keyframes click-wave {
  0% {
    height: 40px;
    width: 40px;
    opacity: 0.35;
    position: relative;
  }
  100% {
    height: 200px;
    width: 200px;
    margin-left: -80px;
    margin-top: -80px;
    opacity: 0;
  }
}

.option-input {
  -webkit-appearance: none;
  -moz-appearance: none;
  -ms-appearance: none;
  -o-appearance: none;
  appearance: none;
  position: relative;
  top: 13.33333px;
  right: 0;
  bottom: 0;
  left: 0;
  height: 40px;
  width: 40px;
  transition: all 0.15s ease-out 0s;
  background: #cbd1d8;
  border: none;
  color: #fff;
  cursor: pointer;
  display: inline-block;
  margin-right: 0.5rem;
  outline: none;
  position: relative;
  z-index: 1000;
}
.option-input:hover {
  background: #9faab7;
}
.option-input:checked {
  background: #40e0d0;
}
.option-input:checked::before {
  height: 40px;
  width: 40px;
  position: absolute;
  content: '✔';
  display: inline-block;
  font-size: 26.66667px;
  text-align: center;
  line-height: 40px;
}
.option-input:checked::after {
  -webkit-animation: click-wave 0.65s;
  -moz-animation: click-wave 0.65s;
  animation: click-wave 0.65s;
  background: #40e0d0;
  content: '';
  display: block;
  position: relative;
  z-index: 100;
}
.option-input.radio {
  border-radius: 50%;
}
.option-input.radio::after {
  border-radius: 50%;
}

body {
  display: -webkit-box;
  display: -moz-box;
  display: -ms-flexbox;
  display: box;
  background: #e8ebee;
  color: #9faab7;
  font-family: "Helvetica Neue", "Helvetica", "Roboto", "Arial", sans-serif;
  text-align: center;
}
body div 
{
  padding: .5rem;
}
body label 
{
  display: block;
  line-height: 40px;
}

.dd
{
   backgroung: red;
   width: 20rem;
}
index
{
   width: 10rem;
}

</style>

  <label>
    <input type="checkbox" class="option-input checkbox" checked />
    
  </label>
  
  <div class="dd">
  <label class="ld">
    <input type="checkbox" class="option-input checkbox" />
  </label>
  </div>
  
  <label>
    <input type="checkbox" class="option-input checkbox" />
    <!-- Checkbox -->
    </label>
</div>
<!--  
<div>
  <label>
    <input type="radio" class="option-input radio" name="example" checked />
    Radio option
  </label>
  <label>
    <input type="radio" class="option-input radio" name="example" />
    Radio option
  </label>
  <label>
    <input type="radio" class="option-input radio" name="example" />
    Radio option
  </label>
-->
</div>
<?php
*/


/*
?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <title>Переключатель</title>
  <style>
   label {
    width: 32px;
    height: 26px;
    display: block;
    position: relative;
   }
   input[type="checkbox"] + span {
    position: absolute;
    left: 0; top: 0;
    width: 100%;
    height: 100%;
    background: url(check.png) no-repeat;
    cursor: pointer;
    border-radius: 10px;

   }
   input[type="checkbox"]:checked + span {
    background-position: 0 -26px; 
   }
  </style>
 </head>
 <body>
  <form>
   <label><input type="checkbox" value="1" name="k"><span></span></label>
   <p><input type="submit"></p>
  </form>
 </body>
</html>
<?php
*/


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
   <div class="container">
   <ul>
   <li id=AllTests class="dropdown">
      <!-- <input type="checkbox" /> -->
      <a href="#" data-toggle="dropdown">Все тесты</a>
   </li>
   <li class="dropdown">
      <input type="checkbox">
      <a href="#TBaseMaker" data-toggle="dropdown">TBaseMaker<span>10.07.2010</span></a> 
      <ul class="dropdown-menu">
         <li><a href="#TBaseMaker1">TBaseMaker1<span>10.07.2010</span></a></li>
         <li><a href="#TBaseMaker2">TBaseMaker2<span>10.07.2010</span></a></li>
         <li><a href="#TBaseMaker3">TBaseMaker3<span>10.07.2010</span></a></li>
         <li><a href="#TBaseMaker4">TBaseMaker4<span>10.07.2010</span></a></li>
      </ul>
   </li>
   <li class="dropdown">
      <input type="checkbox" />
      <a href="#" data-toggle="dropdown">TCtrlDir</a>
      <ul class="dropdown-menu">
         <li><a href="#">TCtrlDir 1</a></li>
         <li><a href="#">TCtrlDir 2</a></li>
         <li><a href="#">TCtrlDir 3</a></li>
         <li><a href="#">TCtrlDir 4</a></li>
      </ul>
   </li>
   <li class="dropdown">
      <input type="checkbox" />
      <a href="#" data-toggle="dropdown">TDownloadFromServer<span id="s1" class='sp'>&#9788;</span></a>
      <ul class="dropdown-menu">
         <li><a href="#">TDownloadFromServer 1</a></li>
         <li><a href="#">TDownloadFromServer 2</a></li>
         <li><a href="#">TDownloadFromServer 3</a></li>
         <li><a href="#">TDownloadFromServer 4</a></li>
      </ul>
   </li>
   <li class="dropdown">
      <input type="checkbox" />
      <a href="#" data-toggle="dropdown">TFixLoadTimer<span id="s2" class='sp'>&#9788;</span></a>
      <ul class="dropdown-menu">
         <li><a href="#">TFixLoadTimer 1</a></li>
         <li><a href="#">TFixLoadTimer 2</a></li>
         <li><a href="#">TFixLoadTimer 3</a></li>
         <li><a href="#">TFixLoadTimer 4</a></li>
      </ul>
   </li>
   <li class="dropdown">
      <input type="checkbox" />  
       <!-- 
      <a href="#" data-toggle="dropdown">TPageStarter<span id="s3" class='sp'>&#9788;</span></a>
      -->
      <a href="#" data-toggle="dropdown">TPageStarter<span id="s3" 
         class='sp'><i class="fa fa-snowflake-o" aria-hidden="true"></i></span></a>
      <ul class="dropdown-menu">
         <li><a href="#">TPageStarter 1</a></li>
         <li><a href="#">TPageStarter 2</a></li>
         <li><a href="#">TPageStarter 3</a></li>
         <li><a href="#">TPageStarter 4</a></li>
      </ul>
   </li>
   <li class="dropdown">
      <input type="checkbox" />
      <a href="#" data-toggle="dropdown">TUploadToServer<span id="s4" class='sp'>&#9788;</span></a>
      <ul class="dropdown-menu">
         <li><a href="#">TUploadToServer 1</a></li>
         <li><a href="#">TUploadToServer 2</a></li>
         <li><a href="#">TUploadToServer 3</a></li>
         <li><a href="#">TUploadToServer 4</a></li>
      </ul>
   </li>
   </ul>
   
<!-- -->
<ul>
   
<li id=AllTests1 class="dropdown1">
   <label>
      <input type="checkbox" class="option-input checkbox1" /> 
   </label>
   <a href="#" data-toggle="dropdown1">Проба1 Проба1 Проба1 Все тесты</a>
</li>
<li id=AllTests2 class="dropdown1">
   <label>
      <input type="checkbox" class="option-input checkbox1" /> 
   </label>
   <a href="#" data-toggle="dropdown1">Проба2 Проба2 Проба2 Все тесты</a>
</li>
</ul>
<!-- -->
   
   </div>

<style>
.dropdown1 {position:relative;}
.dropdown1 a{text-decoration:none;}

.dropdown1 [data-toggle="dropdown1"] 
{
   display: block;
   color: white;
   background: #C0392B;
   padding: 10px;
   /*
   -moz-box-shadow: 0 1px 0 #d65548 inset, 0 -1px 0 #962d22 inset;
   -webkit-box-shadow: 0 1px 0 #d65548 inset, 0 -1px 0 #962d22 inset;
   box-shadow: 0 1px 0 #d65548 inset, 0 -1px 0 #962d22 inset;
   text-shadow: 0 -1px 0 rgba(0, 0, 0, 0.3);
   */
}


.dropdown1 > label > input[type="checkbox"] 
{
   opacity: 1;
   display: block;
   position: absolute;
   top: 0;
   /*
   width: 100%;
   height: 100%;
   */
   cursor: pointer;
}

</style>   

</body>
</html>


<?php

// <!-- --> ************************************************** MobiSite.php ***
