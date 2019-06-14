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
      echo '***'.$mask.'***<br>';
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
         MaxiLoadTime = 800; 
         // Пересчитываем среднее значение
         if (msecs>MaxiLoadTime) MaxiLoadTime=msecs; 
         return MaxiLoadTime;
      }
      // ----------------------------------------------------------------------
      //        Выполнить пересчет данных по завершению загрузки страницы
      // ----------------------------------------------------------------------
      function window_onload()
      {
         var CurrLoadTime,MiddLoadTime,MaxiLoadTime; 
         // Пересчитываем текущее время загрузки страницы сайта
         CurrLoadTime=window.performance.now();
         // Cтавим cookie текущего времени загрузки страницы сайта
         // по текущему пути с бесконечной датой истечения
         document.cookie = "CurrLoadTime="+CurrLoadTime+"; path=/; expires=0x6FFFFFFF";

         // Пересчитываем среднее время загрузки страницы сайта
         MiddLoadTime=getMiddLoadTime(CurrLoadTime);
         // Cтавим cookie среднего времени загрузки страницы сайта
         // по текущему пути с бесконечной датой истечения
         document.cookie = "MiddLoadTime="+MiddLoadTime+"; path=/; expires=0x6FFFFFFF";
         // Удаляем кукис
         var date = new Date(0);
         document.cookie = "MiddleLoadTime=; path=/; expires=" + date.toUTCString();

         // Пересчитываем максимальное время загрузки страницы сайта
         MaxiLoadTime=getMaxiLoadTime(CurrLoadTime);
         // Cтавим cookie максимального времени загрузки страницы сайта
         // по текущему пути с бесконечной датой истечения
         document.cookie = "MaxiLoadTime="+MaxiLoadTime+"; path=/; expires=0x6FFFFFFF";
         
         // Выводим данные в консоль
         console.log('Текущее время загрузки страницы сайта: '+CurrLoadTime);
         console.log('Среднее время загрузки страницы сайта: '+MiddLoadTime);
         console.log('Большее время загрузки страницы сайта: '+MaxiLoadTime);
         
      }
      
      // Выполняем пересчет по завершении загрузки страницы
      addLoadEvent(window_onload);



      /*
      window.onload = function()
      {
         console.log('say onload');
         window_onload();
         // Cтавим cookie name=value по текущему пути с бесконечной датой истечения
         document.cookie = "name=value; path=/; expires=0x6FFFFFFF";
         // Удаляем кукис
         //var date = new Date(0);
         //document.cookie = "name=; path=/; expires=" + date.toUTCString();
      }
      */
      
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
