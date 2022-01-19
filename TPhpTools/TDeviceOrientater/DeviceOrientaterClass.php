<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                    *** DeviceOrientaterClass.php ***

// ****************************************************************************
// * TPhpTools        Регистратор ориентации и изменения положения устройства *
// *                                                                          *
// * v2.1, 18.01.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  16.01.2022 *
// ****************************************************************************

/**
 * Класс DeviceOrientater обеспечивает контроль положения устройства:
 * 
 * а) вызывает перезапуск страницы при изменении положения смартфона или 
 * планшета с передачей в url-запросе страницы параметра "orient", указывающего 
 * положение устройства, в которое оно переводится ("landscape" или "portrait"):
 *    $_SERVER['SCRIPT_NAME'].'?orient=landscape' или
 *    $_SERVER['SCRIPT_NAME'].'?orient=portrait'; 
 * 
 * б) для настольных компьютеров класс считает, что устройство всегда находится
 * в положении "landscape" и смену ориентации не выполняет;
 * 
 * в) для смартфонов класс предполагает, что первый запуск страницы выполняется
 * из положения "landscape". Если обнаруживается, что первый запуск производится 
 * из положения "portrait", то класс принудительно перезапускает страницу с
 * явным указанием через параметр "orient" этого положения:
 *     $_SERVER['SCRIPT_NAME'].'?orient=portrait';
 * 
 * Для взаимодействия с объектами класса должны быть определены две константы:
 * 
 * pathPhpTools - указывающая путь к каталогу с файлами библиотеки прикладных классов;
 * pathPhpPrown - указывающая путь к каталогу с файлами библиотеки прикладных функции,
 *    которые требуются для работы методов класса 
 *    
 * Пример создания объекта класса и выборки значения кукиса Orient:
 * 
 * // Указываем место размещения библиотеки прикладных функций TPhpPrown
 * define ("pathPhpPrown",$_SERVER['DOCUMENT_ROOT'].'/TPhpPrown');
 * // Указываем место размещения библиотеки прикладных классов TPhpTools
 * define ("pathPhpTools",$_SERVER['DOCUMENT_ROOT'].'/TPhpTools');
 * // Создаем объект класса DeviceOrientater
 * $SiteDevice=oriComputer;
 * $orient = new ttools\DeviceOrientater($SiteDevice);
 * 
**/

// Определения констант для PHP
define ("oriLandscape", 'landscape');  // Ландшафтное расположение устройства
define ("oriPortrait",  'portrait');   // Портретное расположение устройства
define ("oriComputer",  'Computer');   // Настольный компьютер, ноутбук, моноблок
define ("oriMobile",    'Mobile');     // Смартфон
define ("oriTablet",    'Tablet');     // Планшет

class DeviceOrientater
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $_SignaUrl;         // uri вызова страницы в ландшафтной ориентации
   protected $_SignaPortraitUrl; // uri вызова страницы в портретной ориентации
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct($SiteDevice=oriComputer) 
   {
      // Подключаем межязыковые (PHP-JScript) определения внутри HTML
      echo 
      '<script>'.
      'oriLandscape="'  .oriLandscape. '";'.
      'oriPortrait="'   .oriPortrait.  '";'.
      'oriComputer="'   .oriComputer.  '";'.
      'oriMobile="'     .oriMobile.    '";'.
      'oriTablet="'     .oriTablet.    '";'.
      '</script>';
      // Определяем uri вызова страниц с различной ориентацией
      $this->_SignaUrl=$_SERVER['SCRIPT_NAME'].'?orient='.oriLandscape;
      $this->_SignaPortraitUrl=$_SERVER['SCRIPT_NAME'].'?orient='.oriPortrait;
      // При первом запуске перегружаем портретную страницу
      $this->ReloadPortrait($SiteDevice);  
      // Подключаем обработчик изменения положения смартфона
      $this->OnOrientationChange();  
      // Определяем ориентацию устройства
      $this->MakeOrient();  
   }

   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---

   // *************************************************************************
   // *            Перегрузить при первом запуске портретную страницу         *
   // *************************************************************************
   protected function ReloadPortrait($SiteDevice) 
   {
      // Если устройство смартфон и портретная ориентация, 
      // то перегружаем страницу
      if ($SiteDevice==oriMobile)
      {
         ?> <script>
         // По сессионной переменной определяем первый запуск в сессии
         orientStatus=sessionStorage.getItem('orientStatus'); 
         if (orientStatus==null)
         {
            // Снимаем признак первого запуска в сессии
            sessionStorage.setItem('orientStatus',1);
            // Если портретная ориентация, то перегружаем страницу
            SignaPortraitUrl="<?php echo $this->_SignaPortraitUrl;?>";
            if ((window.orientation==0)||(window.orientation==180))
            {
               window.location = SignaPortraitUrl;
            } 
         }
         </script> <?php
      }
   }
   // *************************************************************************
   // *                       Определить ориентацию устройства                *
   // *************************************************************************
   // Текущую ориентацию устройства (смартфона,компьютера) можно узнать 
   // проверкой свойства window.orientation, принимающего одно
   // из следующих значений: "0" — нормальная портретная ориентация, "-90" —
   // альбомная при повороте по часовой стрелке, "90" — альбомная при повороте 
   // против часовой стрелки, "180" — перевёрнутая портретная ориентация (пока 
   // только для iPad).
   protected function MakeOrient() 
   {
      ?> <script>
      // Определяем текущую ориентацию устройства 
      if ((window.orientation==0)||(window.orientation==180)) 
      {
         DeviceOrientater_Orient=oriPortrait;
      } 
      else
      {
         DeviceOrientater_Orient=oriLandscape 
      } 
      </script> <?php
   }
   // *************************************************************************
   // *            Подключить обработчик изменения положения смартфона        *
   // *************************************************************************
   // http://greymag.ru/?p=175, 07.09.2011. При повороте устройства браузер 
   // отсылает событие orientationchange. Это актуально для обеих операционных 
   // систем. Но подписка на это событие может осуществляться по разному. 
   // При проверке на разных устройствах iPhone, iPad и Samsung GT (Android),
   // выяснилось что в iOS срабатывает следующий вариант установки обработчика: 
   // window.onorientationchange = handler; А для Android подписка осуществляется 
   // иначе: window.addEventListener( 'orientationchange', handler, false ); 
   //
   // Примечание: В обоих примерах handler - функция-обработчик. Текущую ориентацию
   // экрана можно узнать проверкой свойства window.orientation, принимающего одно
   // из следующих значений: 0 — нормальная портретная ориентация, -90 —
   // альбомная при повороте по часовой стрелке, 90 — альбомная при повороте 
   // против часовой стрелки, 180 — перевёрнутая портретная ориентация (пока 
   // только для iPad).
   //         
   // Отследить переворот экрана:
   // https://www.cyberforum.ru/javascript/thread2242547.html, 08.05.2018
   protected function OnOrientationChange() 
   {
      ?> <script>
      // Назначаем uri вызова страниц с различной ориентацией
      SignaUrl="<?php echo $this->_SignaUrl;?>";
      SignaPortraitUrl="<?php echo $this->_SignaPortraitUrl;?>";
      // Готовим обработку события при изменении положения устройства
      window.addEventListener('orientationchange',doOnOrientationChange);
      function doOnOrientationChange() 
      {
      if ((window.orientation==0)||(window.orientation==180))
      {
         window.location = SignaPortraitUrl;
      } 
      if ((window.orientation==90)||(window.orientation==-90)) 
         window.location = SignaUrl;
      }
      </script> <?php
   }
} 

// ********************************************** DeviceOrientaterClass.php ***
