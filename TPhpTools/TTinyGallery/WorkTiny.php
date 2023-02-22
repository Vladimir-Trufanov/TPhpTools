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
// ****************************************************************************
// *   Построить панель выбранных значений при назначении новой статьи        *
// ****************************************************************************
function mmlNaznachitStatyu_BODY_KwinGallery()
{
   echo '<br><br>
      <div class="nazst"> 
         <p class="nazstName"  id="wnCue">Раздел материалов</p>
         <p class="nazstValue" id="wvCue">не выбрано</p>
      </div>
      <div class="nazst"> 
         <p class="nazstName"  id="wnArt">Новая статья</p>
         <p class="nazstValue" id="wvArt">не назначено</p>
      </div>
      <div class="nazst"> 
         <p class="nazstName"  id="wnDat">Дата создания</p>
         <p class="nazstValue" id="wvDat">не выбрано</p>
         <div id="nazstSub">
         </div>
      </div>
   ';
}
//          <input type="submit" value="Записать реквизиты статьи" form="frmNaznachitStatyu">

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
      <form id="frmNaznachitStatyu" method="get" action="'.$SaveAction.'">
   ';
   echo '
      <input id="nsName" type="text" name="nsnName" '.$nsnName.
         ' placeholder="Название нового материала"'.
         ' required onchange="changeNsName(this.value)">
      <input id="nsDate" type="date" name="nsnDate" '.$nsnDate.
         ' required onchange="changeNsDate(this.value)">
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
