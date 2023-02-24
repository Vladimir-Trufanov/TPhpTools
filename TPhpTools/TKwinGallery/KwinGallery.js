// PHP7/HTML5, EDGE/CHROME                               *** KwinGallery.js ***

// ****************************************************************************
// * KwinGalleryClass         Блок функций сопровождения класса на JavaScript *
// *                                                                          *
// * v1.0, 17.02.2023                               Автор:      Труфанов В.Е. *
// * Copyright © 2023 tve                           Дата создания: 09.01.2023 *
// ****************************************************************************

/*
$(function() {
	
    $('#dialog').dialog({
        buttons: [{text: "OK", click: addDataToTable}],
        modal: true,
        autoOpen: false,
        width: 340
    })
    
    $('#show').button().click(function() {
        $('#dialog').dialog("open");
    })
    
    function addDataToTable() {
        $('#placeholder').hide();
		
        $('<tr><td>' + $('#product').val() + '</td><td>' + $('#color').val() +
            '</td><td>' + $('#count').val() + '</td></tr>').appendTo('#prods tbody');
			
        $('#dialog').dialog("close");
    }
			
});
*/

// ****************************************************************************
// *  Задать обработчик запроса по удалению выбранного изображения c данными  *
// *           из базы данных по указанному идентификатору и транслиту        *
// ****************************************************************************
function DeleteImg(uid,TranslitPic,Comment,pathPhpTools,pathPhpPrown)
{
   $('#DialogWind').dialog
   ({
      buttons:[{text:"OK",click:function()
      {
         xDeleteImg(uid,TranslitPic,Comment,pathPhpTools,pathPhpPrown)
      }}]
   });
   htmlText='Удалить изображение "'+Comment+'"?';
   Notice_Info(htmlText,"Удалить изображение");
} 
function xDeleteImg(Uid,TranslitPic,Comment,pathPhpTools,pathPhpPrown)
{
   // Задаем константу диагностирования ошибке в ответе на запрос
   Err="Произошла ошибка";
   // Выполняем запрос на удаление выбранного изображения c данными 
   $.ajax({
      url: "deleteImg.php",
      type: 'POST',
      data: {uid:Uid, translitpic:TranslitPic, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
      // Выводим ошибки при выполнении запроса в PHP-сценарии
      error: function (jqXHR,exception) 
      {
         aif=Err;
         messi=SmarttodoError(jqXHR,exception);       
         DialogWindMessage(Err,messi,' ');
      },
      // Обрабатываем ответное сообщение
      success: function(message)
      {
         console.log(message);
         // Вырезаем из запроса чистое сообщение
         messa=FreshLabel(message);
         //console.log(messa);
         // Получаем параметры ответа
         parm=JSON.parse(messa);
         //console.log(parm.NameArt);
         //console.log(parm.Piati);
         //console.log(parm.iif);
         DialogWindMessage(parm.iif,parm.NameArt,'Удаляется изображение "'+Comment+'"!');
      }
   });
}
// ****************************************************************************
// *             Вывести диалоговое окно с сообщением (или с ошибкой)         *
// ****************************************************************************
function DialogWindMessage(aif,messi,htmlText)
{
   // Если передана ошибка, выводим сообщение
   if (aif==Err)
   {
      Error_Info(messi);
   } 
   // Говорим, что сообщение удаляется
   else 
   {
      $('#DialogWind').html(htmlText);
      // Удаляем кнопку из диалога и увеличиваем задержку до закрытия
      delayClose=1500;
      $('#DialogWind').dialog
      ({
         buttons:[],
         hide:{effect:"explode",delay:delayClose,duration:1000,easing:'swing'},
         title: "Удаление изображения",
      });
      // Закрываем окно
      $("#DialogWind").dialog("close");
   }
   // Перезагружаем страницу через 2.5 секунды
   setTimeout(function() {location.reload();},2500);
}

// ********************************************************* KwinGallery.js *** 
