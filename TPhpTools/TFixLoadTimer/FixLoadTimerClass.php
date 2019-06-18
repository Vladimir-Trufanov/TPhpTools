<?php 
                                          
// PHP7/HTML5, EDGE/CHROME                        *** FixLoadTimerClass.php ***

// ****************************************************************************
// * TPhpTools                          Регистратор времени загрузки страницы *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.06.2019
// Copyright © 2019 tve                              Посл.изменение: 17.06.2019

/**
 * Класс FixLoadTimer обеспечивает расчет и регистрацию текущего, среднего,
 * наибольшего и наименьшего времени загрузки страницы сайта. По умолчанию 
 * определенные данные записываются в памяти браузера LocalStorage. 
 * Если браузером LocalStorage не поддерживается, то расчитанные значения 
 * записываются в кукисы. 
**/

// ----------------------- Константы управления передачей данных о загрузке ---
define ("fltNotTransmit",  0); // данные не передаются  
define ("fltWriteConsole", 1); // записываются в консоль
define ("fltSendCookies",  2); // отправляются в кукисы
define ("fltAll",          3); // записываются в консоль, отправляются в кукисы  

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
      //                  Записать время загрузки страницы сайта
      // ----------------------------------------------------------------------
      function putLoadTime(varName,varValue,stringDate)                               
      {
         // Объект localStorage поддерживается, записываем данное в память
         if (window.localStorage)
         {
            localStorage.setItem(varName,varValue);
         }
         // Если разрешено, то данное дублируем кукисом по текущему пути 
         // с временем хранения на 400 дней
         setCookie(varName,String(varValue),stringDate,"/");
      }
      // ----------------------------------------------------------------------
      //                        Выбрать время загрузки
      // ----------------------------------------------------------------------
      function getLoadTime(varName)                               
      {
         varValue=0;
         if (window.localStorage)
         {
            varValue=localStorage.getItem(varName);
            if (typeof varValue == "object") varValue=0;
            if (typeof varValue == "undefined") varValue=0;
         }
         else
         {
            varValue=getCookie(varName);
            if (typeof varValue == "object") varValue=0;
            if (typeof varValue == "undefined") varValue=0;
         }
         return varValue;
      }
      // ----------------------------------------------------------------------
      //          Пересчитать среднее время загрузки страницы сайта
      // ----------------------------------------------------------------------
      function getMiddLoadTime(msecs,stringDate) 
      {
         var MiddLoadTime;
         // Выбираем прежнее значение среднего времени загрузки
         MiddLoadTime = getLoadTime('MiddLoadTime');                               
         // Пересчитываем среднее значение
         MiddLoadTime = (Number(MiddLoadTime)+Number(msecs))/2;
         // Записываем новое значение
         putLoadTime('MiddLoadTime',MiddLoadTime,stringDate); 
         return MiddLoadTime;
      }
      // ----------------------------------------------------------------------
      //        Пересчитать максимальное время загрузки страницы сайта
      // ----------------------------------------------------------------------
      function getMaxiLoadTime(msecs,stringDate) 
      {
         var MaxiLoadTime;
         // Выбираем прежнее значение максимального времени загрузки
         MaxiLoadTime = getLoadTime('MaxiLoadTime');                               
         // Пересчитываем максимальное значение
         if (Number(msecs)>Number(MaxiLoadTime)) MaxiLoadTime=msecs; 
         // Записываем новое значение
         putLoadTime('MaxiLoadTime',MaxiLoadTime,stringDate); 
         return MaxiLoadTime;
      }
      // ----------------------------------------------------------------------
      //        Пересчитать минимальное время загрузки страницы сайта
      // ----------------------------------------------------------------------
      function getMiniLoadTime(msecs,stringDate) 
      {
         var MiniLoadTime;
         // Выбираем прежнее значение минимального времени загрузки
         MiniLoadTime = getLoadTime('MiniLoadTime'); 
         // Отключаем нулевое значение
         if (MiniLoadTime<0.001) MiniLoadTime=100000;                             
         // Пересчитываем минимальное значение
         if (Number(msecs)<Number(MiniLoadTime)) MiniLoadTime=msecs; 
         // Записываем новое значение
         putLoadTime('MiniLoadTime',MiniLoadTime,stringDate); 
         return MiniLoadTime;
      }
      // ----------------------------------------------------------------------
      //        Выполнить пересчет данных по завершению загрузки страницы
      // ----------------------------------------------------------------------
      function window_onload()
      {
         // Задаем период хранения кукисов 400 дней
         var dateCookie,stringDate;
         dateCookie=new Date;
         dateCookie.setDate(dateCookie.getDate() + 400);
         stringDate=dateCookie.toUTCString();
         console.log(stringDate);
         
         var CurrLoadTime,MiddLoadTime,MaxiLoadTime,MiniLoadTime; 
         // Пересчитываем и записываем текущее время загрузки страницы сайта
         CurrLoadTime=window.performance.now();
         putLoadTime('CurrLoadTime',CurrLoadTime,stringDate); 
         
         var proba=getCookie('name');
         console.log('***'+proba+'***'+(typeof proba)+'***');
         var proba=getCookie('CurrLoadTime');
         console.log('***'+proba+'***'+(typeof proba)+'***');
         
         // Пересчитываем среднее время загрузки страницы сайта
         MiddLoadTime=getMiddLoadTime(CurrLoadTime,stringDate);
         // Удаляем кукис
         // DeleteCookie('Currlo');
         // Пересчитываем максимальное время загрузки страницы сайта
         MaxiLoadTime=getMaxiLoadTime(CurrLoadTime,stringDate);
         // Пересчитываем минимальное время загрузки страницы сайта
         MiniLoadTime=getMiniLoadTime(CurrLoadTime,stringDate);
         // Выводим данные в консоль
         console.log('Время загрузки страницы сайта:');
         console.log('Текущее = '+CurrLoadTime);
         console.log('Среднее = '+MiddLoadTime);
         console.log('Большее = '+MaxiLoadTime);
         console.log('Меньшее = '+MiniLoadTime);
      }
      // Выполняем пересчет по завершении загрузки страницы
      addLoadEvent(window_onload);
      
      // Перебираем все элементы, находящиеся в контейнере localStorage
      </script>
      <div id="elements"></div>
      <script>
      var str="";
      for (var i=0; i<localStorage.length; i++)
      {
         str+="Ключ: "+localStorage.key(i)+"; Значение: "+localStorage.getItem(localStorage.key(i))+".<br>";
      }
      document.getElementById("elements").innerHTML = str;
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
