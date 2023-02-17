<?php namespace ttools;

// PHP7/HTML5, EDGE/CHROME                            *** KwinGalleryJs.php ***
// ****************************************************************************
// * KwinGalleryClass         Блок функций сопровождения класса на JavaScript *
// *                                                                          *
// * v1.0, 17.02.2023                               Автор:      Труфанов В.Е. *
// * Copyright © 2023 tve                           Дата создания: 09.01.2023 *
// ****************************************************************************

?> 
<script>
$(document).ready(function()
{
})

// ****************************************************************************
// *  Задать обработчик запроса по удалению выбранного изображения c данными  *
// *           из базы данных по указанному идентификатору и транслиту        *
// ****************************************************************************
function DeleteImg()
{
   /*
   $('#DialogWind').dialog
   ({
      buttons:[{text:"OK",click:function(){xUdalitMater(Uid)}}]
   });
   htmlText="Удалить выбранный материал по "+Uid+"?";
   Notice_Info(htmlText,"Удалить материал");
  */
   alert('DeleteImg');
} 
function xDeleteImg(Uid)
{
/*
   // Выводим в диалог предварительный результат выполнения запроса
   htmlText="Удалить статью по "+Uid+" не удалось!";
   $('#DialogWind').html(htmlText);
   // Выполняем запрос на удаление
   pathphp="deleteArt.php";
   // Делаем запрос на определение наименования раздела материалов
   $.ajax({
      url: pathphp,
      type: 'POST',
      data: {idCue:Uid, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
      // Выводим ошибки при выполнении запроса в PHP-сценарии
      error: function (jqXHR,exception) {SmarttodoError(jqXHR,exception)},
      // Обрабатываем ответное сообщение
      success: function(message)
      {
         // Вырезаем из запроса чистое сообщение
         messa=FreshLabel(message);
         // Получаем параметры ответа
         parm=JSON.parse(messa);
         // Выводим результат выполнения
         if (parm.NameArt==gncNoCue) htmlText=parm.NameArt+' Uid='+Uid;
         else htmlText=parm.NameArt;
         $('#DialogWind').html(htmlText);
      }
   });
   // Удаляем кнопку из диалога и увеличиваем задержку до закрытия
   delayClose=1500;
   $('#DialogWind').dialog
   ({
      buttons:[],
      hide:{effect:"explode",delay:delayClose,duration:1000,easing:'swing'},
      title: "Удаление материала",
   });
   // Закрываем окно
   $("#DialogWind").dialog("close");
   // Перезагружаем страницу через 4 секунды
   setTimeout(function() {location.reload();}, 4000);
*/
}



</script>
<?php

// ****************************************************** KwinGalleryJs.php *** 
