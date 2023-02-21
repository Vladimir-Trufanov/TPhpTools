<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                                 *** WorkTiny.php ***

// ****************************************************************************
// * TinyGalleryClass             Блок функций расширения класса TTinyGallery *
// *                                                                          *
// * v1.0, 21.02.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  13.11.2022 *
// ****************************************************************************

// ****************************************************************************
// *      Настроить размеры шрифтов и полосок меню (рождественская версия)    *
// ****************************************************************************
function IniFontChristmas()
{
   echo '
   <style>
   .accordion li > a, 
   .accordion li > i 
   {
      font:bold .9rem/1.8rem Arial, sans-serif;
      padding:0 1rem 0 1rem;
      height:2rem;
   }
   .accordion li > a span, 
   .accordion li > i span 
   {
      font:normal bold .8rem/1.2rem Arial, sans-serif;
      top:.4rem;
      right:0;
      padding:0 .6rem;
      margin-right:.6rem;
   }
   </style>
   ';
}
// *************************************************************************
// *                         Вывести заголовок статьи                      *
// *************************************************************************
function MakeTitle($NameGru,$NameArt,$DateArt='')
{
   if ($NameArt==ttMessage) echo '<div id="Message">'.$NameGru.'</div>'; 
   else if ($NameArt==ttError) echo '<div id="NameError">'.$NameGru.'</div>'; 
   else
   {
      echo '<div id="TopLine">'; 
      if ($NameArt=='')
      {
         echo '<div id="NameGru">'.$NameGru.'</div>'; 
         echo '<div id="NameArt">'.'</div>'; 
      }
      else
      {
         echo '<div id="NameGru">'.$NameGru.':'.'</div>'; 
         echo '<div id="NameArt">'.$NameArt.' ['.$DateArt.']'.'</div>'; 
      } 
         echo '</div>'; 
   }
}

// ----------------------------------------------------- mmlNaznachitStatyu ---

function mmlNaznachitStatyu_HEAD()
{
    // Отключаем разворачивание аккордеона
    // в случае, когда создаем заголовок новой статьи. 
    echo '
    <style>
      .accordion li .sub-menu 
      {
         height:100%;
      }
    </style>
    ';
    echo '
    <script>
    </script>
    ';
    // Включаем рождественскую версию шрифтов и полосок меню
    IniFontChristmas();
}

function mmlNaznachitStatyu_BODY_KwinGallery()
{
   echo 'KwinGallery_uuummlNaznachitStatyu<br>'; 
   echo '<br>
      <div id="nazst"> 
         <p class="nazstName"> Раздел материалов</p><br>
         <p class="nazstValue" id=wgCue>не выбрано</p><br>
         <p class="nazstName"> Новая статья</p><br>
         <p class="nazstValue" id=wgArt>не назначено</p><br>
         <p class="nazstName"> Дата создания</p><br>
         <p class="nazstValue" id=wgDat>не выбрано</p><br>
         <button id="show">Записать реквизиты статьи</button>
      </div>
   ';
}
// ****************************************************************************
// *            Выполнить действия в области редактирования "WorkTiny"        *
// *                        при назначении новой статьи                       *
// ****************************************************************************
function mmlNaznachitStatyu_BODY_WorkTiny($messa,$pdo,$Arti)
{
   if (\prown\getComRequest('nsnGru')==NoDefine)
   {
      ?> <script> 
         $(document).ready(function() {Error_Info('Группа материалов не назначена!');})
      </script> <?php
   }
   // Проверяем и учитываем уже выбранные данные
   if (\prown\getComRequest('nsnName')==NULL) $nsnName='';
   else $nsnName='value="'.\prown\getComRequest('nsnName').'"';
   if (\prown\getComRequest('nsnDate')==NULL) $nsnDate='';
   else $nsnDate='value="'.\prown\getComRequest('nsnDate').'"';
   // Выводим заголовочное сообщение
   MakeTitle($messa,ttMessage);
   // Выбираем название и дату новой статьи
   $SaveAction=$_SERVER["SCRIPT_NAME"];
   echo '
      <div id="nsGroup">
      <form id="frmNaznachitStatyu" method="post" action="'.$SaveAction.'">
   ';
   echo '
      <input id="nsName" type="text" name="nsnName" '.$nsnName.' placeholder="Название нового материала" required>
      <input id="nsDate" type="date" name="nsnDate" '.$nsnDate.' required>
      <input id="nsCue"  type="hidden" name="nsnCue" value="'.NoDefine.'">
      <input id="nsGru"  type="hidden" name="nsnGru" value="'.NoDefine.'">
   ';
   echo '
      </form>
      </div>
   ';
   // Выбираем группу материалов для которой создается новая статья
   echo '<div id="AddArticle">';
      $Arti->MakeUniMenu($pdo,'getNameCue');
   echo '</div>';
}

// *********************************************************** WorkTiny.php ***
