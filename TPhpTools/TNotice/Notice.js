// PHP7/HTML5, EDGE/CHROME                                    *** Notice.js ***

// ****************************************************************************
// * TPhpTools.TNotice                       Блок общих функций на JavaScript *
// *                                                                          *
// * v1.0, 23.02.2023                               Автор:      Труфанов В.Е. *
// * Copyright © 2023 tve                           Дата создания: 09.01.2023 *
// ****************************************************************************

/**
 * -------Программный модуль CommonPrown содержит в себе небольшие функции, которые
 * могут быть использованы при написании сайта на PHP или в backend-разработке 
 * проекта.
 *
 * -------Функции Alert и ConsoleLog выводят сообщения на странице в браузере. Alert 
 * выводит сообщение на экран и приостанавливает работу сайта. ConsoleLog может 
 * быть использована при динамической отладке сервера сайта. Она выводит 
 * сообщение в консоли браузера, не останавливая вывод на странице.
 *
**/
// ****************************************************************************
// *                     Создать и настроить виджет "Диалог"                  *
// ****************************************************************************
function CreateDialog()
{
   $('#DialogWind').dialog
   ({
      bgiframe:true,      // совместимость с IE6
      closeOnEscape:true, // закрывать при нажатии Esc
      modal:true,         // модальное окно
      resizable:true,     // разрешено изменение размера
      height:"auto",      // высота окна автоматически
      autoOpen:false,     // сразу диалог не открывать
      width:600,
      draggable:true, 
      show:{effect:"fade",delay:100,duration:1500},
      title: "Это окно",
   });
   $('#DialogWind').parent().find(".ui-dialog-title").css({
      'font-size': '1.8rem',
      'font-weight':800,
      'color':'red',
      'font-family':'"EmojNotice"'
   });
   $('#DialogWind').parent().find(".ui-dialog-content").css('color','blue');
   // При необходимости скрываем заголовок диалога
   // $('#DialogWind').parent().find(".ui-dialog-titlebar").hide();
   // Прячем крестик
   // $('#DialogWind').parent().find(".ui-dialog-titlebar-close").hide();
}
// ****************************************************************************
// *                    Создать и настроить виджет "Диалог"                   *
// ****************************************************************************
function Notice_Info(messa,ititle,isHide=true,delayClose=250,durClose=1000)
{
   $('#DialogWind').html(messa);
   $('#DialogWind').dialog
   ({
      title: ititle,
   });
   if (isHide)
   {
      $('#DialogWind').dialog
      ({
         hide:{effect:"explode",delay:delayClose,duration:durClose,easing:'swing'},
      });
   }
   $('#DialogWind').dialog("open");
}
// ****************************************************************************
// *                                  Ошибка!                                 *
// ****************************************************************************
function Error_Info(messa)
{
   $('#DialogWind').parent().find(".ui-dialog-content").css('color','red');
   Notice_Info(messa,'Ошибка',false);
}
// ****************************************************************************
// *                                 Информация!                              *
// ****************************************************************************
function Info_Info(messa)
{
   $('#DialogWind').parent().find(".ui-dialog-content").css('color','blue');
   Notice_Info(messa,'Оk',true,250);
}
// ****************************************************************************
// *             Вывести диалоговое окно с сообщением (или с ошибкой),        *
// *                    при необходимости перезагрузить страницу              *
// ****************************************************************************
function Dialog_errmess(aif,htmlText,titleText,comReload=false)
{
   // Если передана ошибка, выводим сообщение
   if (aif==Err)
   {
      Error_Info(htmlText);
      $("#DialogWind").dialog("close");
      // Перезагружаем страницу через 2.5 секунды 
      //if (comReload==true) setTimeout(function() {location.reload();},2500);
   } 
   // При необходимости сообщения не выводим (только контроллируем ошибку)
   else if (titleText==null) {}
   // Выводим информационное сообщение 
   else 
   {
      Notice_Info(htmlText,titleText)
      $("#DialogWind").dialog("close");
      // Перезагружаем страницу через 2.5 секунды 
      //if (comReload==true) setTimeout(function() {location.reload();},2500);
   }
}

// ************************************************************** Notice.js *** 
