<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                    *** DeviceOrientaterClass.php ***

// ****************************************************************************
// * TPhpTools        Регистратор ориентации и изменения положения устройства *
// *                                                                          *
// * v2.1, 16.01.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  16.01.2022 *
// ****************************************************************************

/**
 * Класс DeviceOrientater обеспечивает контроль положения устройства:
 * 
 * а) предоставляет информацию серверу о ландшафтном или портретном 
 *    расположении устройства через кукис Orient по ajax-запросу;
 *    
 * б) вызывает перезапуск страницы при изменении положения.
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
 * $orient = new ttools\DeviceOrientater();
 * 
**/

// Определения констант для PHP
define ("oriLandscape",    0);  // Ландшафтное расположение устройства
define ("oriPortrait",     1);  // Портретное расположение устройства

class DeviceOrientater
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $_iidestination;         // каталог для размещения изображения
   protected $_iimax=57200;           // максимальный размер файла объекта класса
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct() 
   {
      // Подключаем межязыковые (PHP-JScript) определения внутри HTML
      echo 
      '<script>'.
      'oriLandscape="'  .oriLandscape. '";'.
      'oriPortrait="'   .oriPortrait.  '";'.
      '</script>';
      // Инициализируем свойства класса
      $this->_iidestination = '$path';
      $this->_iimax = (int) \prown\getComRequest($Com='MAX_FILE_SIZE');
      // Трассируем установленные свойства
      \prown\ConsoleLog('$this->_destination='.$this->_iidestination); 
      \prown\ConsoleLog('$this->_max='.$this->_iimax);
      // Подключаем обработчик изменения положения смартфона
      $this->OnOrientationChange();  
      // Определяем ориентацию устройства
      $this->MakeOrient();  
   }

   // --------------------------------------------------- ВНУТРЕННИЕ МЕТОДЫ ---

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
         Orient=oriPortrait 
      else Orient=oriLandscape 
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

      SignaPortraitUrl="/_Signaphoto/SignaPhoto.php?orient=portrait";
      SignaUrl="/_Signaphoto/SignaPhoto.php?orient=landscape";

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
