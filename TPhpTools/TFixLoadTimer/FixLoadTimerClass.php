<?php 
                                          
// PHP7/HTML5, EDGE/CHROME                        *** FixLoadTimerClass.php ***

// ****************************************************************************
// * TPhpTools                          Регистратор времени загрузки страницы *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.06.2019
// Copyright © 2019 tve                              Посл.изменение: 25.06.2019

/**
 * Класс FixLoadTimer обеспечивает расчет и регистрацию текущего, среднего,
 * наибольшего и наименьшего времени загрузки страницы сайта. По умолчанию 
 * определенные данные записываются в памяти браузера LocalStorage. 
 * Если браузером LocalStorage не поддерживается, то расчитанные значения 
 * записываются в кукисы. 
**/

// Свойства:
//
// $FltLead - команда управления передачей данных. По умолчанию fltNotTransmit,
//            то есть данные о загрузке не передаются для контроля ни в кукисы, 
// ни в консоль, а только записываются в LocalStorage. Если LocalStorage,
// браузером не поддерживается, то данные будут записываться в кукисы при 
// установке свойства $FltLead в значение fltSendCookies или fltAll 

// ----------------------- Константы управления передачей данных о загрузке ---
define ("fltNotTransmit",  0); // данные не передаются  
define ("fltWriteConsole", 1); // записываются в консоль
define ("fltSendCookies",  2); // отправляются в кукисы
define ("fltAll",          3); // записываются в консоль, отправляются в кукисы  

class FixLoadTimer
{
   public function __construct($FltLead=fltNotTransmit)
   {
      // Принимаем команду управления передачей данных из PHP и 
      // выполняем пересчет данных по завершении загрузки страницы
      ?>
      <script>

      const NOTTRANSMIT  = "<?php echo fltNotTransmit;?>";
      const WRITECONSOLE = "<?php echo fltWriteConsole;?>";
      const SENDCOOKIES  = "<?php echo fltSendCookies;?>";
      const FLTALL       = "<?php echo fltAll;?>";
      
      var FltLead = "<?php echo $FltLead;?>";
      ViewLocalStorage();
      function _window_onload() {window_onload(FltLead);}
      addLoadEvent(_window_onload);
      </script>
      <?php
   }
} 

// ************************************************** FixLoadTimerClass.php ***
