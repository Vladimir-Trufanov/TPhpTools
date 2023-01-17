<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                          *** MenuLeaderClass.php ***

// ****************************************************************************
// * TPhpTools                Фрэйм управляющего меню для обобщенной работы в *
// *               "ittve.me" и "kwintiny" работающего через TinyGalleryClass *
// *                                                                          *
// * v2.0, 09.01.2023                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  18.12.2019 *
// ****************************************************************************

/**
 * Класс --------- KwinGallery строит интерфейс для выбора некоторых символа Юникода.
 * Выборка символов осуществляется из одного из подмассивов общего массива 
 * массива $aUniCues. Подмассивы (наборы) созданы из авторских соображений и 
 * имеют свои номера и названия, так 0 - 'Знаки всякие-разные', 1 - 'Символы 
 * валют', 2 - 'Ожидаемые символы' и так далее.
 * 
 * Для взаимодействия с объектами класса должны быть определены константы:
 *
 * articleSite  - тип базы данных (по сайту)
 * pathPhpTools - путь к каталогу с файлами библиотеки прикладных классов;
 * pathPhpPrown - путь к каталогу с файлами библиотеки прикладных функции
 *    
 * Пример создания объекта класса:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$SiteHost.'/TPhpPrown/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$SiteHost.'/TPhpTools/TPhpTools');
 * // Указываем тип базы данных (по сайту) для управления классом ArticlesMaker
 * define ("articleSite",'IttveMe'); 
 * // Cоздаем объект для управления изображениями в галерее, связанной с 
 * // материалами сайта из базы данных
 * $Galli=new ttools\KwinGallery(gallidir,$nym,$pid,$uid);
**/

// Свойства:
//
// --- $FltLead - команда управления передачей данных. По умолчанию fltNotTransmit,
//            то есть данные о загрузке не передаются для контроля ни в кукисы, 
// ни в консоль, а только записываются в LocalStorage. Если LocalStorage,
// браузером не поддерживается, то данные будут записываться в кукисы при 
// установке свойства $FltLead в значение fltSendCookies или fltAll 
// $Page - название страницы сайта;
// $Uagent - браузер пользователя;

// Подгружаем нужные модули библиотеки прикладных функций
// require_once pathPhpPrown."/CommonPrown.php";
// Подгружаем нужные модули библиотеки прикладных классов
// require_once pathPhpTools."/TArticlesMaker/ArticlesMakerClass.php";

// Возможные типы меню
define ("ittveme", '-i');
define ("kwintiny",'-k');

class MenuLeader
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $typemenu;  // Тип меню (для ittve.me или kwintiny)
   protected $urlHome;   // Начальная страница сайта 
   // ------------------------------------------ ПРЕФИКСЫ ПАРАМЕТРОВ В МЕНЮ ---
   protected $cPreMe;    // Общие для сайта 'ittve.me' 
   protected $ComTiny;   // Общие для фрэйма 'kwintiny' 
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($typemenu,$urlHome) 
   {
      // Инициализируем свойства класса
      $this->typemenu=$typemenu; 
      $this->urlHome=$urlHome; 
      
      // Формируем префиксы вызова страниц для сайта 'ittve.me' и localhost
      //if ($this->is_ittveme()) $this->cPreMe=''; 
      //else 
      $this->cPreMe='?Com=';
      //if ($this->is_ittveme()) $this->ComTiny=''; 
      //else 
      $this->ComTiny='?Com=';

      // Трассируем установленные свойства
      \prown\ConsoleLog('$this->typemenu='.$this->typemenu); 
      \prown\ConsoleLog('$this->urlHome='.$this->urlHome); 
      \prown\ConsoleLog('$this->cPreMe='.$this->cPreMe); 
   }
   public function __destruct() 
   {
   }
   // *************************************************************************
   // *                Отработать меню управления (общая часть)               *
   // *************************************************************************
   public function Menu()
   {
      if ($this->typemenu==ittveme)
      {
         $this->Menu_ittveme();   
      }
      else
      {
         $this->Menu_kwintiny();   
      }
   }
   // *************************************************************************
   // *             Отработать меню управления на фрэйме "KwinTiny"           *
   // *************************************************************************
   private function Menu_kwintiny()
   {
      // Формируем префикс вызова страниц из меню на сайте и localhost
      $cPref=$this->ComTiny;
      // Выводим управляющие меню по страницам
      if (\prown\isComRequest(mmlVybratStatyuRedakti))
      {
         echo '
            <ul class="uli">
            <li class="ili"><a class="ali" href="'.$cPref.mmlVernutsyaNaGlavnuyu.'">На главную</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlVybratStatyuRedakti.'">Выбрать материал</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlNaznachitStatyu.    '">Назначить статью</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlUdalitMaterial     .'">Удалить материал</a></li>
            </ul>   
         ';
      }
      // Если было назначение статьи без указания выбранного раздела, 
      // то перезапускаем страницу "Назначить статью"
      if ((\prown\getComRequest('nsnCue')==-1)&&
      (\prown\getComRequest('nsnName')<>NULL)&&
      (\prown\getComRequest('nsnDate')<>NULL))
      {
         echo '
            <ul class="uli">
            <li class="ili"><a class="ali" href="'.$cPref.mmlVernutsyaNaGlavnuyu.'">На главную</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlNaznachitStatyu.    '">Назначить статью</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlVybratStatyuRedakti.'">Выбрать материал</a></li>
            <li class="ili">'.'<input id="nsSub" type="submit" value="Записать реквизиты статьи" form="frmNaznachitStatyu">'.'</li>
            </ul>   
         ';
      }
      // Если просто была выбрана страница "Назначить статью" 
      else if (\prown\isComRequest(mmlNaznachitStatyu))
      {
         echo '
            <ul class="uli">
            <li class="ili"><a class="ali" href="'.$cPref.mmlVernutsyaNaGlavnuyu.'">На главную</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlNaznachitStatyu.    '">Назначить статью</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlVybratStatyuRedakti.'">Выбрать материал</a></li>
            <li class="ili">'.'<input id="nsSub" type="submit" value="Записать реквизиты статьи" form="frmNaznachitStatyu">'.'</li>
            </ul>   
         ';
      }
      else if (\prown\isComRequest(mmlUdalitMaterial))
      {
         echo '
            <ul class="uli">
            <li class="ili"><a class="ali" href="'.$cPref.mmlVernutsyaNaGlavnuyu.'">На главную</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlVybratStatyuRedakti.'">Выбрать материал</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlNaznachitStatyu.    '">Назначить статью</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlUdalitMaterial     .'">Удалить материал</a></li>
            </ul>   
         ';
      }
      /*
      else if (\prown\isComRequest(mmlNaznachitStatyu)||
      (\prown\getComRequest('titl')<>NULL))
      {
         $this->WorkTiny_mmlNaznachitStatyu();
      }
      */
      // В обычном режиме
      else
      {
         echo '
            <ul class="uli">
            <li class="ili"><a class="ali" href="'.$cPref.mmlVernutsyaNaGlavnuyu.'">На главную</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlNaznachitStatyu.    '">Назначить статью</a></li>
            <li class="ili"><a class="ali" href="'.$cPref.mmlVybratStatyuRedakti.'">Выбрать материал</a></li>
            <li class="ili">'.'<input type="submit" value="Сохранить материал" form="frmTinyText">'.'</li>
            </ul>   
         ';
      }
   }
   // *************************************************************************
   // *             Отработать меню управления на сайте "ittve.me"            *
   // *************************************************************************
   private function Menu_ittveme()
   {
      // Начинаем меню с отсылкой к автору идеи
      echo '
         <!--
         Copyright (c) 2017 by Oliver Knoblich (https://codepen.io/oknoblich/pen/hpltK)
         -->
         <ul class="navset">
      ';
      // Выводим пункты меню управления для страниц из главного меню
      if (prown\isComRequest(mmlZhiznIputeshestviya)||
      prown\isComRequest(mmlDobavitNovyjRazdel)||
      prown\isComRequest(mmlIzmenitNazvanieIkonku)||
      prown\isComRequest(mmlUdalitRazdelMaterialov))
      {
         $this->Punkt($this->urlHome,'&#xf015;','Вернуться','на главную страницу');
         $this->Punkt($this->cPreMe.mmlDobavitNovyjRazdel,'&#xf0f2;','Добавить новый','раздел материалов');
         $this->Punkt($this->cPreMe.mmlIzmenitNazvanieIkonku,'&#xf086;','Изменить название','раздела или иконку');
         $this->Punkt($this->cPreMe.mmlUdalitRazdelMaterialov,'&#xf1f8;','Удалить раздел','материалов');
      }
      // Выводим пункты меню для страницы изменения настроек сайта
      else if (prown\isComRequest(mmlIzmenitNastrojkiSajta))
      {
         $this->Punkt($this->urlHome,'&#xf015;','Вернуться','на главную страницу');
         $this->Punkt($this->cPreMe.mmlOtpravitAvtoruSoobshchenie,'&#xf01c;','Отправить автору','сообщение');
         $this->Punkt($this->cPreMe.mmlVojtiZaregistrirovatsya,'&#xf007;','Войти или','зарегистрироваться');
         $this->Punkt($this->cPreMe.mmlSozdatRedaktirovat,'&#xf044;','Создать материал','или редактировать');
      }
      // Выводим пункты меню страницы для входа и регистрации
      else if (prown\isComRequest(mmlVojtiZaregistrirovatsya))
      {
         $this->Punkt($this->urlHome,'&#xf015;','Вернуться','на главную страницу');
         $this->Punkt($this->cPreMe.mmlOtpravitAvtoruSoobshchenie,'&#xf01c;','Отправить автору','сообщение');
         $this->Punkt($this->cPreMe.mmlIzmenitNastrojkiSajta,'&#xf013;','Изменить настройки','сайта в браузере');
         $this->Punkt($this->cPreMe.mmlSozdatRedaktirovat,'&#xf044;','Создать материал','или редактировать');
      }
      // Выводим пункты для страницы сообщения автору 
      else if (prown\isComRequest(mmlOtpravitAvtoruSoobshchenie))
      {
         $this->Punkt($this->urlHome,'&#xf015;','Вернуться','на главную страницу');
         $this->Punkt($this->cPreMe.mmlIzmenitNastrojkiSajta,'&#xf013;','Изменить настройки','сайта в браузере');
         $this->Punkt($this->cPreMe.mmlVojtiZaregistrirovatsya,'&#xf007;','Войти или','зарегистрироваться');
         $this->Punkt($this->cPreMe.mmlSozdatRedaktirovat,'&#xf044;','Создать материал','или редактировать');
      }
      // Выводим пункты меню при работе с материалом 
      else if (prown\isComRequest(mmlSozdatRedaktirovat))
      {
         $this->Punkt($this->urlHome,'&#xf015;','Вернуться','на главную страницу');
         $this->Punkt($this->cPreMe.mmlOtpravitAvtoruSoobshchenie,'&#xf01c;','Отправить автору','сообщение');
         $this->Punkt($this->cPreMe.mmlIzmenitNastrojkiSajta,'&#xf013;','Изменить настройки','сайта в браузере');
         $this->Punkt($this->cPreMe.mmlVojtiZaregistrirovatsya,'&#xf007;','Войти или','зарегистрироваться');
      }
      // Выводим пункты меню главной страницы
      else
      {
         $this->Punkt($this->cPreMe.mmlOtpravitAvtoruSoobshchenie,'&#xf01c;','Отправить автору','сообщение');
         $this->Punkt($this->cPreMe.mmlIzmenitNastrojkiSajta,'&#xf013;','Изменить настройки','сайта в браузере');
         $this->Punkt($this->cPreMe.mmlVojtiZaregistrirovatsya,'&#xf007;','Войти или','зарегистрироваться');
         $this->Punkt($this->cPreMe.mmlSozdatRedaktirovat,'&#xf044;','Создать материал','или редактировать');
      }
      // Закрываем меню
      echo '</ul>';
   }
   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---

   // *************************************************************************
   // *   Определить работаем ли на хостинге сайта 'ittve.me' или localhost   *
   // *************************************************************************
   function is_ittveme()
   { 
      $Result=false;
      if (
        ($_SERVER['HTTP_HOST']=='ittve.me')||
        ($_SERVER['HTTP_HOST']=='www.ittve.me')||
        ($_SERVER['HTTP_HOST']=='kwinflatht.nichost.ru'))
      {  
         $Result=true;
      }
      return $Result;
   }
   // *************************************************************************
   // *                  Вывести кнопку меню управления страницей             *
   // *************************************************************************
   private function Punkt($Punkt,$cUniCod,$fString,$sString)
   {
      echo '
         <li class="link">
         <span class="prev">'.$cUniCod.'</span>
         <span class="small">'.$cUniCod.'</span>
         <span class="full">
            <span class="k1"><a href="'.$Punkt.'">'.$fString.'</a></span>
            <span class="k2"><a href="'.$Punkt.'">'.$sString.'</a></span>
         </span>
         </li>
      ';
   }
} 

// **************************************************** MenuLeaderClass.php ***
