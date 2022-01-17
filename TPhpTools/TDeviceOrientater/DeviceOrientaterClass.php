<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                    *** DeviceOrientaterClass.php ***

// ****************************************************************************
// * TPhpTools        Регистратор ориентации и изменения положения устройства *
// *                                                                          *
// * v2.1, 17.01.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  16.01.2022 *
// ****************************************************************************

/**
 * Класс DeviceOrientater обеспечивает контроль положения устройства:
 * 
 * а) предоставляет информацию серверу о ландшафтном или портретном 
 *    расположении устройства через кукис Orient по ajax-запросу;
 *    
 * б) вызывает перезапуск страницы при изменении положения;
 * 
 * в) файл DeviceOrientaterClass.php по аякс-запросу вызывается на сервере, как 
 *    PHP-сценарий. В этом случае он формирует кукис для сайта и возвращает 
 *    значение для контроля обратно в браузер.
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
define ("oriLandscape", 'landscape');  // Ландшафтное расположение устройства
define ("oriPortrait",  'portrait');   // Портретное расположение устройства
define ("urlPhpTools",'https://kwinflat.ru/Extve/TPhpTools/TPhpTools');

// По аякс-запросу формируем кукис для сайта и возвращаем значение для 
// контроля обратно в браузер
if (IsSet($_POST['orient']))
{
   $orient=$_POST['orient'];
   setcookie('Orient',$orient);
   echo $orient;
}

class DeviceOrientater
{
   // ----------------------------------------------------- СВОЙСТВА КЛАССА ---
   protected $_SignaUrl;         // uri вызова страницы в ландшафтной ориентации
   protected $_SignaPortraitUrl; // uri вызова страницы в портретной ориентации
   // ------------------------------------------------------- МЕТОДЫ КЛАССА ---
   public function __construct() 
   {
      // Подключаем межязыковые (PHP-JScript) определения внутри HTML
      echo 
      '<script>'.
      'oriLandscape="'  .oriLandscape. '";'.
      'oriPortrait="'   .oriPortrait.  '";'.
      'urlPhpTools="'   .urlPhpTools.  '";'.
      '</script>';
      // Определяем uri вызова страниц с различной ориентацией
      $this->_SignaUrl=$_SERVER['SCRIPT_NAME'].'?orient='.oriLandscape;
      $this->_SignaPortraitUrl=$_SERVER['SCRIPT_NAME'].'?orient='.oriPortrait;
      // Трассируем установленные свойства
      //\prown\ConsoleLog('$this->_SignaUrl='.$this->_SignaUrl); 
      //\prown\ConsoleLog('$this->_SignaPortraitUrl='.$this->_SignaPortraitUrl);
      // Подключаем обработчик изменения положения смартфона
      $this->OnOrientationChange();  
      // Определяем ориентацию устройства
      $this->MakeOrient();  
   }
   // *************************************************************************
   // *               Передать признак ориентации смартфона в кукис           *
   // *************************************************************************
   public function MakeCookieOrient() 
   {
      // Передаем признак положения смартфона в кукис
      //\prown\ConsoleLog(pathPhpTools."/TDeviceOrientater/DeviceOrientaterClass.php");
      //\prown\ConsoleLog(urlPhpTools."/TDeviceOrientater/DeviceOrientaterClass.php");
      // http://localhost:82/_Signaphoto/DeviceOrientaterClass.php
      ?> <script>
      $.ajax({
      type:'POST',           
      //url:urlPhpTools+'/TDeviceOrientater/DeviceOrientaterClass.php',  
      url: 'DeviceOrientaterClass.php',  
      async: false,          
      data: {'orient' : DeviceOrientater_Orient},  // передаваемый обработчику признак ориентации
      cache: false,           
      // Отмечаем результат выполнения скрипта по аякс-запросу (успешный или нет)
      success:function(data)
      {
         console.log("DeviceOrientaterClass: success");
         console.log("data: "+data);
      },
      // Отмечаем  неуспешное выполнение аякс-запроса по причине:
      error:function(data)
      {
         alert("DeviceOrientaterClass: error");
      }
      });
      </script> <?php
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
         DeviceOrientater_Orient=oriPortrait 
      else DeviceOrientater_Orient=oriLandscape 
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
      //console.log('SignaUrl='+SignaUrl);
      //console.log('SignaPortraitUrl='+SignaPortraitUrl);
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
