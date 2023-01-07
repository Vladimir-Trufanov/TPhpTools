<?php namespace ttools;
                                         
// PHP7/HTML5, EDGE/CHROME                           *** MenuArticlesMe.php ***

// ****************************************************************************
// * TPhpTools                      Определить стили меню статей (материалов) *
// *      Discovered from article on Ryan Collins': http://www.ryancollins.me *
// *                                                                          *
// * v2.0, 07.01.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  13.12.2020 *
// ****************************************************************************

// --- постоянная часть -------------------------- БАЗОВАЯ УСТАНОВКА СТИЛЕЙ ---
?>
<style>
.accordion,
.accordion ul,
.accordion li,
.accordion a,
.accordion span
{
   margin:0;
   padding:0;
   border:none;
   outline:none;
   background:#ffffff;
}
.accordion li 
{
   list-style:none;
}
.accordion li > a 
{
   display:block;
   position:relative;
   min-width:1.1rem;

   color:#777;
   text-decoration:none;
   text-shadow:0 .1rem 0 rgba(0,0,0,.35);

   background:#ffffff;
   background:-moz-linear-gradient(bottom,#ffffff 0%, #eaeaea 100%);
   background:-webkit-gradient(linear,left bottom,left top,color-stop(0%,#ffffff),color-stop(100%,#eaeaea));
   background:-webkit-linear-gradient(bottom,#ffffff 0%,#eaeaea 100%);
   background:-o-linear-gradient(bottom,#ffffff 0%,#eaeaea 100%);
   background:-ms-linear-gradient(bottom,#ffffff 0%,#eaeaea 100%);
   background:linear-gradient(to bottom,#ffffff 0%,#eaeaea 100%);

   -webkit-box-shadow:inset 0 .1rem 0 0 rgba(255,255,255,.1), 0 .1rem 0 0 rgba(0,0,0,.1);
   -moz-box-shadow:inset 0 .1rem 0 0 rgba(255,255,255,.1), 0 .1rem 0 0 rgba(0,0,0,.1);
   box-shadow:inset 0 .1rem 0 0 rgba(255,255,255,.1), 0 .1rem 0 0 rgba(0,0,0,.1);
}
.accordion > li:hover > a,
.accordion > li:target > a, 
{
   color:#fdfdfd;
   text-shadow:.1rem .1rem .1rem rgba(255,255,255,.2);
   background:#6c6e74;
   background:-moz-linear-gradient(bottom,#6c6e74 0%,#4b4d51 100%);
   background:-webkit-gradient(linear,left bottom,left top,color-stop(0%,#6c6e74),color-stop(100%,#4b4d51));
   background:-webkit-linear-gradient(bottom,#6c6e74 0%,#4b4d51 100%);
   background:-o-linear-gradient(bottom,#6c6e74 0%,#4b4d51 100%);
   background:-ms-linear-gradient(bottom,#6c6e74 0%,#4b4d51 100%);
   background:linear-gradient(to bottom,#6c6e74 0%,#4b4d51 100%);
}
.accordion li > a span
{
   display:block;
   position:absolute;
   background:#e0e3ec url(../Images/Menu/bgnoise_lg.jpg) repeat top left;
	
   -webkit-border-radius:1.5rem;
   -moz-border-radius:1.5rem;
   border-radius:1.5rem;

   -webkit-box-shadow:inset .1rem .1rem .1rem rgba(0,0,0,.2), .1rem .1rem .1rem rgba(255,255,255,.1);
   -moz-box-shadow:inset .1rem .1rem .1rem rgba(0,0,0,.2), .1rem .1rem .1rem rgba(255,255,255,.1);
   box-shadow:inset .1rem .1rem .1rem rgba(0,0,0,.2), .1rem .1rem .1rem rgba(255,255,255,.1);
}
.accordion > li:hover > a span,
.accordion > li:target > a span
{
   color:#fdfdfd;
   text-shadow:0px 1px 0px rgba(0,0,0, .35);
   background:#B9B5B2;
}
.accordion li .sub-menu 
{
   overflow:hidden;
   -webkit-transition: height .2s ease-in-out;
   -moz-transition: height .2s ease-in-out;
   -o-transition: height .2s ease-in-out;
   -ms-transition: height .2s ease-in-out;
   transition: height .2s ease-in-out;
}
/**
 * Выводим иконки в названиях разделов
**/
/*
.accordion > li > a:before 
{
   position:absolute;

   top:0;
   left:0;
   content:'';

   width:24px;
   height:24px;
   margin:4px 8px;

   background-repeat:no-repeat;
   background-image:url(../Images/Menu/icons.png);
   background-position:0 -24px;
}
.accordion li:hover>a:before,
.accordion li:target>a:before{background-position:0 0;}

.accordion li.moya-zhizn>a:before{background-position:0 -24px;}
.accordion li.moya-zhizn:hover>a:before,
.accordion li.moya-zhizn:target>a:before{background-position:0 0;}

.accordion li.mikroputeshestviya>a:before{background-position:-24px -24px;}
.accordion li.mikroputeshestviya:hover>a:before,
.accordion li.mikroputeshestviya:target>a:before{background-position:-24px 0;}

.accordion li.vsyakoe-raznoe>a:before{background-position:-48px -24px;}
.accordion li.vsyakoe-raznoe:hover>a:before,
.accordion li.vsyakoe-raznoe:target>a:before{background-position:-48px 0;}

.accordion li.v-kontakte > a:before{background-position:-72px -24px;}
.accordion li.v-kontakte:hover > a:before,
.accordion li.v-kontakte:target > a:before{background-position:-72px 0;}
*/

/**
 * Устанавливаем цвета и бордюры пунктов меню со статьями
**/
/*
.sub-menu li a 
{
   color:#797979;
   text-shadow:1px 1px 0 rgba(255,255,255,.2);
   background:#e5e5e5;
   border-bottom:1px solid #c9c9c9;
   -webkit-box-shadow:inset 0 1px 0 0 rgba(255,255,255,.1),0 1px 0 0 rgba(0,0,0,.1);
   -moz-box-shadow:inset 0 1px 0 0 rgba(255,255,255,.1),0 1px 0 0 rgba(0,0,0,.1);
   box-shadow:inset 0 1px 0 0 rgba(255,255,255,.1),0 1px 0 0 rgba(0,0,0,.1);
}
.sub-menu li:last-child a 
{ 
   border:none;
}
*/
/**
 * Выделяем статьи, на которые наезжаем курсором
**/
/*
.sub-menu li:hover a
{
   background:#efefef;
}
*/
/**
 * Определяем цвет дат статей и бордюр их
**/
/*
.sub-menu li > a span 
{
   color:#797979;
   border:1px solid #c9c9c9;
}
*/
/**
 * Выделяем порядковые номера статей в разделах
**/
/*
.sub-menu em 
{
   position: absolute;
   top: 0;
   left: 0;
   margin-left: 14px;
   color: #a6a6a6;
   font: normal 10px/32px Arial, sans-serif;
}
*/
</style>
<?php
// --- переменная часть -------------------------- БАЗОВАЯ УСТАНОВКА СТИЛЕЙ ---
?>
<style>
/* Настраиваем размеры шрифтов и полосок меню (базовая версия) */
.accordion li > a 
{
   font:bold 1.3rem/3rem Arial, sans-serif;
   padding:0 1rem 0 4rem;
   height:3.2rem;
}
.accordion li > a span
{
   font:normal bold 1.2rem/1.8rem Arial, sans-serif;
   top:.7rem;
   right:0;
   padding:0 1rem;
   margin-right:1rem;
}
/* Разворачиваем аккордеон в случае, когда выбираем материал */
/* Этого нет, когда создаем заголовок новой статьи */ 
.accordion li .sub-menu 
{
   height:0;
}
.accordion li:target .sub-menu 
{
   height: 100%;
}
</style>
<?php
// --- постоянная часть ------------------- РОЖДЕСТВЕНСКАЯ УСТАНОВКА СТИЛЕЙ ---
// Эта версия подготовлена для случая, когда href - только в конце li, а li 
// построена на i
?>
<style>
.accordion i
{
   margin:0;
   padding:0;
   border:none;
   outline:none;
   background:#ffffff;
}
.accordion li > i 
{
   display:block;
   position:relative;
   min-width:1.1rem;

   color:#777;
   text-decoration:none;
   text-shadow:0 .1rem 0 rgba(0,0,0,.35);

   background:#ffffff;
   background:-moz-linear-gradient(bottom,#ffffff 0%, #eaeaea 100%);
   background:-webkit-gradient(linear,left bottom,left top,color-stop(0%,#ffffff),color-stop(100%,#eaeaea));
   background:-webkit-linear-gradient(bottom,#ffffff 0%,#eaeaea 100%);
   background:-o-linear-gradient(bottom,#ffffff 0%,#eaeaea 100%);
   background:-ms-linear-gradient(bottom,#ffffff 0%,#eaeaea 100%);
   background:linear-gradient(to bottom,#ffffff 0%,#eaeaea 100%);

   -webkit-box-shadow:inset 0 .1rem 0 0 rgba(255,255,255,.1), 0 .1rem 0 0 rgba(0,0,0,.1);
   -moz-box-shadow:inset 0 .1rem 0 0 rgba(255,255,255,.1), 0 .1rem 0 0 rgba(0,0,0,.1);
   box-shadow:inset 0 .1rem 0 0 rgba(255,255,255,.1), 0 .1rem 0 0 rgba(0,0,0,.1);
}
.accordion > li:hover > i > a,
.accordion > li:target > i > a 
{
   color:#fdfdfd;
   text-shadow:.1rem .1rem .1rem rgba(255,255,255,.2);
   background:#6c6e74;
   background:-moz-linear-gradient(bottom,#6c6e74 0%,#4b4d51 100%);
   background:-webkit-gradient(linear,left bottom,left top,color-stop(0%,#6c6e74),color-stop(100%,#4b4d51));
   background:-webkit-linear-gradient(bottom,#6c6e74 0%,#4b4d51 100%);
   background:-o-linear-gradient(bottom,#6c6e74 0%,#4b4d51 100%);
   background:-ms-linear-gradient(bottom,#6c6e74 0%,#4b4d51 100%);
   background:linear-gradient(to bottom,#6c6e74 0%,#4b4d51 100%);
}
.accordion li > i span 
{
   display:block;
   position:absolute;
   background:#e0e3ec url(../Images/Menu/bgnoise_lg.jpg) repeat top left;
	
   -webkit-border-radius:1.5rem;
   -moz-border-radius:1.5rem;
   border-radius:1.5rem;

   -webkit-box-shadow:inset .1rem .1rem .1rem rgba(0,0,0,.2), .1rem .1rem .1rem rgba(255,255,255,.1);
   -moz-box-shadow:inset .1rem .1rem .1rem rgba(0,0,0,.2), .1rem .1rem .1rem rgba(255,255,255,.1);
   box-shadow:inset .1rem .1rem .1rem rgba(0,0,0,.2), .1rem .1rem .1rem rgba(255,255,255,.1);
}
.accordion > li:hover > i span,
.accordion > li:target > i span 
{
   color:#fdfdfd;
   text-shadow:0px 1px 0px rgba(0,0,0, .35);
   background:#B9B5B2;
}
</style>
<?php
// --- переменная часть ------------------- РОЖДЕСТВЕНСКАЯ УСТАНОВКА СТИЛЕЙ ---
?>
<style>
/* Настраиваем размеры шрифтов и полосок меню (базовая версия) */
.accordion li > i 
{
   font:bold 1.3rem/3rem Arial, sans-serif;
   padding:0 1rem 0 4rem;
   height:3.2rem;
}
.accordion li > i span 
{
   font:normal bold 1.2rem/1.8rem Arial, sans-serif;
   top:.7rem;
   right:0;
   padding:0 1rem;
   margin-right:1rem;
}
</style>
<?php

// ***************************************************** MenuArticlesMe.php ***
	

