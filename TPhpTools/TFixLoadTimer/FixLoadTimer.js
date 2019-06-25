// PHP7/HTML5, EDGE/CHROME                              *** FixLoadTimer.js ***

// ****************************************************************************
// * TPhpTools                          Регистратор времени загрузки страницы *
// *                                     (функциональные модули класса на js) *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.06.2019
// Copyright © 2019 tve                              Посл.изменение: 25.06.2019

// ----------------------------------------------------------------------------
//                   Записать время загрузки страницы сайта
// ----------------------------------------------------------------------------
function putLoadTime(varName,varValue,stringDate,FltLead)                               
{
   // Объект localStorage поддерживается, записываем данное в память
   if (window.localStorage)
   {
      localStorage.setItem(varName,varValue);
   }
   // Если разрешено, то данное дублируем кукисом по текущему пути 
   // с временем хранения на 400 дней
   if ((FltLead==SENDCOOKIES)||(FltLead==FLTALL)) 
   {
      setCookie(varName,String(varValue),stringDate,"/");
   }
   // Запрещено - удаляем кукис 
   else 
   {
      DeleteCookie(varName);
   }
}
// ----------------------------------------------------------------------------
//                            Выбрать время загрузки
// ----------------------------------------------------------------------------
function getCookieTime(varName)
{
   varValue=getCookie(varName);
   if (typeof varValue == "object") varValue=0;
   if (typeof varValue == "undefined") varValue=0;
   return varValue;
}
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
      varValue=getCookieTime(varName)
   }
   return varValue;
}
// ----------------------------------------------------------------------------
//           Пересчитать среднее плавающее время загрузки страницы сайта
// ----------------------------------------------------------------------------
function getMiddLoadTime(msecs,stringDate,FltLead) 
{
   var MiddLoadTime;
   // Выбираем прежнее значение среднего времени загрузки
   MiddLoadTime = getLoadTime('MiddLoadTime');                               
   // Пересчитываем среднее плавающее значение
   MiddLoadTime = (Number(MiddLoadTime)+Number(msecs))/2;
   // Записываем новое значение
   putLoadTime('MiddLoadTime',MiddLoadTime,stringDate,FltLead); 
   return MiddLoadTime;
}
// ----------------------------------------------------------------------------
//           Пересчитать максимальное время загрузки страницы сайта
// ----------------------------------------------------------------------------
function getMaxiLoadTime(msecs,stringDate,FltLead) 
{
   var MaxiLoadTime;
   // Выбираем прежнее значение максимального времени загрузки
   MaxiLoadTime = getLoadTime('MaxiLoadTime');                               
   // Пересчитываем максимальное значение
   if (Number(msecs)>Number(MaxiLoadTime)) MaxiLoadTime=msecs; 
   // Записываем новое значение
   putLoadTime('MaxiLoadTime',MaxiLoadTime,stringDate,FltLead); 
   return MaxiLoadTime;
}
// ----------------------------------------------------------------------------
//           Пересчитать минимальное время загрузки страницы сайта
// ----------------------------------------------------------------------------
function getMiniLoadTime(msecs,stringDate,FltLead) 
{
   var MiniLoadTime;
   // Выбираем прежнее значение минимального времени загрузки
   MiniLoadTime = getLoadTime('MiniLoadTime'); 
   // Отключаем нулевое значение
   if (MiniLoadTime<0.001) MiniLoadTime=100000;                             
   // Пересчитываем минимальное значение
   if (Number(msecs)<Number(MiniLoadTime)) MiniLoadTime=msecs; 
   // Записываем новое значение
   putLoadTime('MiniLoadTime',MiniLoadTime,stringDate,FltLead); 
   return MiniLoadTime;
}
// ----------------------------------------------------------------------------
//          Выполнить пересчет данных по завершению загрузки страницы
// ----------------------------------------------------------------------------
function window_onload(FltLead)
{
   var CurrLoadTime,MiddLoadTime,MaxiLoadTime,MiniLoadTime; 
   // Задаем период хранения кукисов 400 дней
   var dateCookie,stringDate;
   dateCookie=new Date;
   dateCookie.setDate(dateCookie.getDate() + 400);
   stringDate=dateCookie.toUTCString();
   // Показываем дату завершения хранения кукисов
   // console.log(stringDate);
         
   // Пересчитываем и записываем текущее время загрузки страницы сайта
   CurrLoadTime=window.performance.now();
   putLoadTime('CurrLoadTime',CurrLoadTime,stringDate,FltLead); 
   // Пересчитываем среднее плавающее время загрузки страницы сайта
   MiddLoadTime=getMiddLoadTime(CurrLoadTime,stringDate,FltLead);
   // Удаляем кукис
   // DeleteCookie('Currlo');
   
   // Пересчитываем максимальное время загрузки страницы сайта
   MaxiLoadTime=getMaxiLoadTime(CurrLoadTime,stringDate,FltLead);
   // Пересчитываем минимальное время загрузки страницы сайта
   MiniLoadTime=getMiniLoadTime(CurrLoadTime,stringDate,FltLead);
   
   // Если разрешено, то выводим данные в консоль
   if ((FltLead==WRITECONSOLE)||(FltLead==FLTALL)) 
   {
      if (window.localStorage)
      {
         console.log('Время загрузки страницы:');
         console.log('Текущее = '+CurrLoadTime);
         console.log('Среднее = '+MiddLoadTime);
         console.log('Большее = '+MaxiLoadTime);
         console.log('Меньшее = '+MiniLoadTime);
         console.log('--- localStorage ---');
      }
      console.log('Время предыдущей загрузки страницы:');
      console.log('Текущее = '+CurrLoadTime);
      console.log('Среднее = '+MiddLoadTime);
      console.log('Большее = '+MaxiLoadTime);
      console.log('Меньшее = '+MiniLoadTime);
      console.log('--- $_COOKIE ---');
   }
}
// ******************************************************** FixLoadTimer.js ***
