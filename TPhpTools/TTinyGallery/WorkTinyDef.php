<?php
// PHP7/HTML5, EDGE/CHROME                              *** WorkTinyDef.php ***

// ****************************************************************************
// * TinyGalleryClass             Совместные определения для модулей PHP и JS *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  18.02.2023
// Copyright © 2021 tve                              Посл.изменение: 18.02.2023

// Сообщение об ошибке при выполнении действия
define ("Err", "Произошла ошибка");  
// Определения сообщений для PHP
define ("ajSuccess",            "Функция/процедура выполнена успешно");     
define ("ajUndeletionOldFiles", "Ошибка удаления старых файлов");

// ****************************************************************************
// *         Подключить межязыковые (PHP-JScript) определения внутри HTML     *
// ****************************************************************************
/*
function DefinePHPtoJS()
{
   // Переменные JavaScript, соответствующие определениям сообщений в PHP
   $define=
   '<script>'.

   'Err="'              .Err.'";'.

   'ajSuccess="'           .ajSuccess.           '";'.
   'ajUndeletionOldFiles="'.ajUndeletionOldFiles.'";'.

   '</script>';
   echo $define;
}
*/
   
function DefinePHPtoJS()
{
?> 
   <script src="/jQuery/jquery-3.6.3.min.js"></script>
   <script src="/jQuery/jquery-ui.min.js"></script>
<?php

   // Переменные JavaScript, соответствующие определениям сообщений в PHP
   $define=
   '<script>'.

   'Err="'              .Err.'";'.

   'ajSuccess="'           .ajSuccess.           '";'.
   'ajUndeletionOldFiles="'.ajUndeletionOldFiles.'";'.

   '</script>';
   echo $define;
}   
// ******************************************************** WorkTinyDef.php ***
