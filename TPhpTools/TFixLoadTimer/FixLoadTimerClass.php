<?php 
                                          
// PHP7/HTML5, EDGE/CHROME                        *** FixLoadTimerClass.php ***

// ****************************************************************************
// * TPhpTools                          Регистратор времени загрузки страницы *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.06.2019
// Copyright © 2019 tve                              Посл.изменение: 14.06.2019

/**
 * Класс FixLoadTimer обеспечивает расчет и регистрацию текущего, среднего,
 * наибольшего и наименьшего времени загрузки страницы сайта. По умолчанию 
 * определенные данные записываются в памяти браузера LocalStorage. Если 
 * браузером LocalStorage не поддерживается, то расчитанные значения 
 * записываются в кукисы.
 *"Жёсткая" система обработки ошибок/исключений представляется
 * 
 * следующим образом:
 *    а) ошибка является контроллируемой в случае, когда известно в каком месте 
 * сайта она может возникнуть и, таким образом, сообщение об ошибке можно 
 * вывести на экран по разметке сайта;
 *    б) в остальных случаях ошибка является неконтроллируемой и вывод сообщения
 * об ошибке выполняется на отдельной странице;
 *    в) по умолчанию функция генерирует неконтроллируемую ошибку/исключение:
 * trigger_error($Message,E_USER_ERROR), предполагая на верхнем уровне обработку
 * ошибки через сайт doortry.ru, где неконтроллируемая ошибка возникает не 
 * чистом экране с трассировкой всплывания исключения;
 *    г) режим функции "по умолчанию" может быть изменен значением глобальной 
 *    переменной $ModeError;
 *    
 * в режиме $ModeError==rvsCurrentPos просто выводится сообщение в текущей 
 * позиции сайта. Данный режим используется при тестировании модулей;
 * 
 * в режиме по умолчанию $ModeError==rvsTriggerError вызывается исключение с 
 * пользовательской ошибкой через trigger_error($Message,$errtype), 
 * где $errtype может быть одним из значений E_USER_ERROR, E_USER_WARNING, 
 * E_USER_NOTICE, E_USER_DEPRECATED. По умолчанию E_USER_ERROR.
 * 
 * в режиме $ModeError==rvsMakeDiv предполагается, что ошибка произошла в 
 * php-коде до разворачивания html-страницы и, в этом случае, формируется 
 * дополнительный div сообщения с id="Ers";
 * 
 * в режиме $Mode==rvsDialogWindow разворачивается сообщение в диалоговом окне 
 * с помощью JQueryUI. И в этом случае на вызывающем сайте должны быть 
 * подключены модули jquery, jquery-ui, jquery-ui.css, например от Microsoft:
 * 
 * <link rel="stylesheet" type="text/css"
 *    href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/ui-lightness/jquery-ui.css">
 *    <script
 *       src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js">
 *    </script>
 *    <script
 *       src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.11.2/jquery-ui.min.js">
 *    </script>
**/

// Определяем константы управления временами загрузки
define ("fltNotKeepSafeAtfirst",    "atf");    // Перевести переменные в начальные условия  
define ("ChangeSize", "chs");    // "Изменить размер базового шрифта"  
define ("Computer", "Computer"); // устройство, запросившее сайт - компьютер  
define ("Mobile", "Mobile");     // устройство, запросившее сайт - смартфон  

class FixLoadTimer
{
   public function __construct($mask=E_ALL,$ignoreOther=false)
   {
      //echo '***'.$mask.'***<br>';
      ?>
      <script>

      // ----------------------------------------------------------------------
      //      Добавить обработку события по завершению загрузки страницы
      // ----------------------------------------------------------------------
      function addLoadEvent(func) 
      {
         var oldonload = window.onload;
         if (typeof window.onload != 'function') 
         {
            window.onload = func;
         } 
         else
         {
            window.onload = function() 
            {
               if (oldonload) 
               {
                  oldonload();
               }
               func();
            }
         }
      }
      // ----------------------------------------------------------------------
      //          Пересчитать среднее время загрузки страницы сайта
      // ----------------------------------------------------------------------
      function getMiddLoadTime(msecs) 
      {
         var MiddLoadTime;
         // Выбираем прежнее значение среднего времени загрузки
         MiddLoadTime = 1000; 
         // Пересчитываем среднее значение
         MiddLoadTime = (MiddLoadTime+msecs)/2;
         return MiddLoadTime;
      }
      // ----------------------------------------------------------------------
      //        Пересчитать максимальное время загрузки страницы сайта
      // ----------------------------------------------------------------------
      function getMaxiLoadTime(msecs) 
      {
         var MaxiLoadTime;
         // Выбираем прежнее значение максимального времени загрузки
         MaxiLoadTime = 1000; 
         // Пересчитываем максимальное значение
         if (msecs>MaxiLoadTime) MaxiLoadTime=msecs; 
         return MaxiLoadTime;
      }
      // ----------------------------------------------------------------------
      //        Пересчитать минимальное время загрузки страницы сайта
      // ----------------------------------------------------------------------
      function getMiniLoadTime($msecs) 
      {
         var $MiniLoadTime;
         // Выбираем прежнее значение минимального времени загрузки
         $MiniLoadTime = 100; 
         // Пересчитываем минимальное значение
         if ($msecs<$MiniLoadTime) $MiniLoadTime=$msecs; 
         return $MiniLoadTime;
      }
      // ----------------------------------------------------------------------
      //        Выполнить пересчет данных по завершению загрузки страницы
      // ----------------------------------------------------------------------
      function window_onload()
      {
         var CurrLoadTime,MiddLoadTime,MaxiLoadTime,MiniLoadTime; 
         // Пересчитываем текущее время загрузки страницы сайта
         CurrLoadTime=window.performance.now();
         // Пересчитываем среднее время загрузки страницы сайта
         MiddLoadTime=getMiddLoadTime(CurrLoadTime);
         // Удаляем кукис
         //var date = new Date(0);
         //document.cookie = "MiddleLoadTime=; path=/; expires=" + date.toUTCString();
         // Пересчитываем максимальное время загрузки страницы сайта
         MaxiLoadTime=getMaxiLoadTime(CurrLoadTime);
         // Пересчитываем минимальное время загрузки страницы сайта
         MiniLoadTime=getMiniLoadTime(CurrLoadTime);
         // Выводим данные в консоль
         console.log('Время загрузки страницы сайта:');
         console.log('Текущее = '+CurrLoadTime);
         console.log('Среднее = '+MiddLoadTime);
         console.log('Большее = '+MaxiLoadTime);
         console.log('Меньшее = '+MiniLoadTime);
         // Отправляем кукисы с временами загрузки страницы сайта
         // по текущему пути с бесконечной датой истечения
         document.cookie = "CurrLoadTime="+CurrLoadTime+"; path=/; expires=0x6FFFFFFF";
         document.cookie = "MiddLoadTime="+MiddLoadTime+"; path=/; expires=0x6FFFFFFF";
         document.cookie = "MaxiLoadTime="+MaxiLoadTime+"; path=/; expires=0x6FFFFFFF";
         document.cookie = "MiniLoadTime="+MiniLoadTime+"; path=/; expires=0x6FFFFFFF";
      }
      // Выполняем пересчет по завершении загрузки страницы
      addLoadEvent(window_onload);
      </script>
      <?php
   }
   public function __destruct()
   {
   }
   public function res($mes)
   {
      echo $mes;
   }
} 

// ************************************************** FixLoadTimerClass.php ***
