<?php 
                                          
// PHP7/HTML5, EDGE/CHROME                        *** FixLoadTimerClass.php ***

// ****************************************************************************
// * TPhpTools                          Регистратор времени загрузки страницы *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.06.2019
// Copyright © 2019 tve                              Посл.изменение: 14.06.2019

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
