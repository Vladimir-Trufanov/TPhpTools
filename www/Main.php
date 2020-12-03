<?php
// PHP7/HTML5, EDGE/CHROME                                     *** Main.php ***

// ****************************************************************************
// * TPhpTools-test                   Кто прожил жизнь, тот больше не спешит! *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  03.12.2020
// Copyright © 2020 tve                              Посл.изменение: 03.12.2020

// Определяем сайтовые константы
define ("ChooseAll",  "Выбрать все элементы"); // Первая кнопка Submit  
define ("ToTest",     "Протестировать");       // Вторая кнопка Submit 
define ("ChoiceList", "Укажите список прикладных классов библиотеки TPhpTools"); 
// Подключаем файлы библиотеки прикладных модулей и рабочего пространства
$TPhpPrown=$SiteHost.'/TPhpPrown';
require_once $TPhpPrown."/TPhpPrown/CommonPrown.php";
require_once $TPhpPrown."/TPhpPrown/Findes.php";
/*
require_once $TPhpPrown."/TPhpPrown/getTranslit.php";
require_once $TPhpPrown."/TPhpPrown/iniConstMem.php";
require_once $TPhpPrown."/TPhpPrown/isCalcInBrowser.php";
require_once $TPhpPrown."/TPhpPrown/MakeCookie.php";
require_once $TPhpPrown."/TPhpPrown/MakeRegExp.php";
require_once $TPhpPrown."/TPhpPrown/MakeSession.php";
require_once $TPhpPrown."/TPhpPrown/MakeType.php";
require_once $TPhpPrown."/TPhpPrown/ViewGlobal.php";
require_once $TPhpPrown."/TPhpPrown/ViewSimpleArray.php";
*/
// Подключаем модуль обеспечения тестов
require_once $TPhpPrown."/TPhpPrownTests/FunctionsBlock.php";
?>
<!DOCTYPE html>
<html>
<head>
<title>TPhpTools - библиотека PHP-прикладных классов</title>
<meta charset="utf-8">
<link href="https://fonts.googleapis.com/css?family=Anonymous+Pro:400,400i,700,700i&amp;subset=cyrillic" rel="stylesheet">

<link rel="stylesheet" type="text/css" 
   href="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.10.3/themes/ui-lightness/jquery-ui.css">
<script
   src="https://ajax.aspnetcdn.com/ajax/jQuery/jquery-1.11.2.min.js">
</script>
<script 
   src="https://ajax.aspnetcdn.com/ajax/jquery.ui/1.11.2/jquery-ui.min.js">
</script>
<script src="/TPhpToolsTests.js"> </script>

<script>
   function isCheckClick() 
   {
      // Выбираем все элементы с именем "formDoor[]"
      var up_names = document.getElementsByName("formDoor[]");
      // Определяем, сколько из них чекбоксов c выбранным чекбоксом
      var count=0;
      for (let i = 0; i < up_names.length; i++) 
      {
         if (up_names[i].tagName=='INPUT')
         {
            if (up_names[i].type=='checkbox') 
            {
               if (up_names[i].checked) count++;
            }
         }  
      }
      var Result=false;
      return Result;
   }
</script>

</head>
<body>
<div id="res"></div>
<a target="_blank" href="#"><img src="89.gif" ></a>

<?php
// Инициализируем список прикладных функций библиотеки TPhpPrown 
// и рабочего пространства сайта
$aPhpTools=array
(            
   'TCtrlDir'        =>'контроллер каталогов и файлов',   
   'TFixLoadTimer'   =>'регистратор времени загрузки страницы',   
   'TPageStarter'    =>'cтартер сессии страницы и регистратор пользовательских данных',   
   'TUploadToServer' =>'загрузчик файлов на сервер',
);

// ---
//echo  prown\getTranslit('блок общих функций').'<br>';
//phpinfo();
//echo 'PHP_VERSION_ID='.PHP_VERSION_ID.'<br>';
//echo 'PHP_VERSION='.PHP_VERSION.'<br>';
//echo $SiteRoot.'<br>';
//echo $SiteHost.'<br>';
//echo $TPhpPrown.'<br>';
//echo $UserAgent.'<br>';
// ---

// Выводим список прикладных функций библиотеки TPhpPrown и обрабатываем
// выбранные тесты

// Форма для тестирования предоставляет несколько вариантов выбора: 

// все флажки имеют одно имя (formDoor[]). Одно имя говорит о том, 
// что все флажки связаны между собой. Квадратные скобки указывают на то, 
// что все значения будут доступны из одного массива. 
// То есть $_POST['formDoor'] возврашает массив, содержащий значения флажков, 
// которые были выбраны пользователем.
// 
// С помощью кнопки "Выбрать всё" и запроса соответствующего типа
// http://localhost:84/index.php?
//    formSubmit=%D0%92%D1%8B%D0%B1%D1%80%D0%B0%D1%82%D1%8C+%D0%B2%D1%81%D1%91
//    &
//    formDoor%5B%5D=Findes можно выбрать все флажки

// Представленные далее три ветки выбора являются заключительными для 
// создаваемой страницы: вторая ветка инициирует тестовую оболочку TSimpleTest, 
// которая всегда запускает тесты в завершении текущей страницы и заканчивает
// страницу тегами </body></html>.

// Поэтому теги </body></html> принудительно вставляются в первую и третью ветки,
// а также выводятся вместе с сообщением "Элементы для тестирования Вами не 
// выбраны!"

// Выполнить первую ветку (когда был клик "Выбрать все")
if (prown\isComRequest(ChooseAll,'formSubmit'))
{
   // Вырисовываем чекбоксы для тестирования
   FunctionsCheckbox($aPhpTools,ChooseAll,ChoiceList);
   // Завершаем разметку, так как здесь теста не будет
   echo "\n</body>\n</html>\n";   
}
// Выполнить вторую ветку (когда был клик "Протестировать")
elseif (prown\isComRequest(ToTest,'formSubmit'))
{
   // Вырисовываем чекбоксы для тестирования
   FunctionsCheckbox($aPhpTools,ToTest,ChoiceList);
   // Запускаем тестирование (тестом будет и завершена разметка)
   MakeTest($SiteRoot,$aPhpTools,'PHP','/TPhpTools/TPhpToolsTests/');
}
// Выполнить третью ветку (при начальном запуске страницы)
else
{
   // Вырисовываем чекбоксы 
   FunctionsCheckbox($aPhpTools,ToTest,ChoiceList);
   // Завершаем разметку, так как здесь теста не будет
   echo "\n</body>\n</html>\n";   
}
// *************************************************************** Main.php ***
