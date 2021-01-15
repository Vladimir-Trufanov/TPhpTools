 <?php
// PHP7/HTML5, EDGE/CHROME                           *** FunctionsTools.php ***

// ****************************************************************************
// * TPhpTools-test             Блок вспомогательных функций проверки классов *
// ****************************************************************************

//                                                   Автор:       Труфанов В.Е.
//                                                   Дата создания:  13.01.2019
// Copyright © 2019 tve                              Посл.изменение: 30.03.2020

// ****************************************************************************
// *                    Вывести список элементов библиотеки                   *
// ****************************************************************************
// Выводим форму для следующего тестирования, которая предоставляет пользователю
// несколько вариантов выбора: 
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
function EchoCheck($k,$v,$intype,$checkin='')
{
   echo '<input type="'.$intype.'" id="'.$k.'" value="'.$k.'" '.$checkin.' '.
   'name="formDoor[]" onclick="isCheckClick()">'.$k.' - '.$v.
   '<span style="color: red" id="sp'.$k.'">'.''.'</span>'.
   '<br>';
}
function FunctionsCheckbox($aElements,$isCheck=ToTest,
   $cMess='Укажите прототипы объектов в TJsTools, которые следует протестировать',
   $intype='checkbox')
{
   $Result = true;
   echo '<form action="'.htmlentities($_SERVER['PHP_SELF']).'" method="post" id="myform">';
   echo '<p>'.$cMess.'?<br><br>';
   if ($intype=='checkbox')
   {
      echo '<input type="submit" name="formSubmit" value="'.ChooseAll.'"/><br><br>';
   }
   foreach($aElements as $k=>$v)
   {
      if ($isCheck==ChooseAll)
      {
         if (!($k=='MakeCookie')) 
         {
            EchoCheck($k,$v,$intype,'checked');
         }
         else
         {
            EchoCheck($k,$v,$intype);
         }
      }
      else
      {
         EchoCheck($k,$v,$intype);
      }
   }
   echo '</p>';
   echo '<input type="submit" name="formSubmit" value="'.ToTest.'"/><br><br>';
   echo '</form>';
   return $Result;
}
// ****************************************************************************
// *             Проверить, выбран ли указанный элемент библиотеки            *
// ****************************************************************************
function isChecked($chkname,$value)
{
   if(!empty($_REQUEST[$chkname]))
   {
      foreach($_REQUEST[$chkname] as $chkval)
      {
         if($chkval == $value)
         {
            return true;
         }
      }
   }
   return false;
}
// ****************************************************************************
// *       Сформировать оболочку для тестирования JavaScript сценариев        *
// ****************************************************************************
function LeadTest()
{
?>
<!-- 
<h1 id="qunit-header">Заголовок страницы</h1>
<h2 id="qunit-banner"></h2>
<div id="qunit-testrunner-toolbar">Панель инструментов</div>
<h2 id="qunit-userAgent">UserAgent</h2>
<div id="qunit-fixture">Привет!</div>
<div id="qunit"></div>
<ol id="qunit-tests"></ol>

Делаем общий вывод прохождения тестов
в следующей последовательности: 
   а) заголовок страницы;
   б) разделитель (если он не был вызван ранее, в остальных случаях 
      без <div id="qunit"></div> тоже выводится один раз);    
   в) панель инструментов (если она не была вызвана отдельно);    
   г) UserAgent (если он не был вызван отдельно); 
   д) По клику на числе проверок в тесте, разворачивается список проверок 
   е) <div id="qunit-fixture"></div> - образец кода и разметки до тестов
<div id="qunit"></div>
-->
<h2 id="qunit-userAgent"></h2>
<h2 id="qunit-banner"></h2>
<ol id="qunit-tests"></ol>
<h2 id="qunit-userAgent"></h2>
<div id="qunit-fixture">Привет!</div>
<?php
}
// ****************************************************************************
// *      Проверить выбор флажков, указывающих на элементы списка, которые    *
// *                            следует протестировать                        *
// ****************************************************************************
// http://form.guide/php-form/php-form-checkbox.html
// http://dnzl.ru/view_post.php?id=182
function MakeTest($SiteRoot,$aPhpPrown,$lang='PHP',$TestsSubDir='/TPhpPrown/TPhpPrownTests/')
{
   $SiteAbove=prown\getAbove($SiteRoot);       // Надсайтовый каталог
   $SiteHost=prown\getAbove($SiteAbove);       // Каталог хостинга
   if(isset($_REQUEST['formSubmit'])) 
   {
      if(empty($_REQUEST['formDoor']))
      {
         echo("<p>Элементы для тестирования Вами не выбраны!</p>\n</body>\n</html>\n");
      }
      else
      {
         $aDoor=$_REQUEST['formDoor'];
         $N=count($aDoor);
         // Запускаем тестирование и трассировку выбранных элементов
         if ($lang=='PHP') require_once($SiteHost.'/TSimpleTest/autorun.php');
         foreach($aPhpPrown as $k=>$v)
         {
            //echo '<input type="checkbox" checked name="formDoor[]" value="'.$k.'"/>'.$k.' - '.$v.'<br>';
            if(isChecked('formDoor',$k))
            {
               //echo $k.' тестируется.<br>';
               if ($lang=='PHP') 
               {
                  //echo $SiteHost."/TPhpPrown/TPhpPrownTests/".$k."_test.php";
                  require_once $SiteHost.$TestsSubDir.$k."_test.php";
               }
               else if ($lang=='JS') 
               {
                  $scri='TJsTools/'.$k.'.js';
                  $scritest='TJsToolsTests/'.$k.'Test.js';
                  //echo '<br>'.$scri.'<br>'; echo $scritest.'<br>'; 
                  echo '<script src="'.$scri.'"></script>';
                  echo '<script src="'.$scritest.'"></script>'; 
                  //'<script src="TJsTools/Trim.js"></script>';
                  //echo '<script src="TJsToolsTests/TTrimTest.js"></script>'; 
                  //echo '<script src="TJsTools/Trim.js"></script>';
                  //echo '<script src="TJsToolsTests/TTrimTest.js"></script>'; 
                  //echo $SiteHost."/TPhpPrown/TPhpPrownTests/".$k."_test.php";
                  //require_once $SiteHost."/TPhpPrown/TPhpPrownTests/".$k."_test.php";
               }
            }
         }
         if ($lang=='PHP') 
         {
            require_once $SiteHost."/TPhpPrown/TPhpPrownTests/"."FinalMessage_test.php";
         }
      } 
   }
}
// ****************************************************************************
// *   Проверить выбор радиокнопки, указывающей на элемент списка, который    *
// *        следует протестировать и подключить модули тестирования           *
// ****************************************************************************
function MakeTToolsTest($SiteRoot,$aPhpPrown,$lang='PHP',$TestsSubDir='/TPhpPrown/TPhpPrownTests/')
{
   $SiteAbove=prown\getAbove($SiteRoot);       // Надсайтовый каталог
   $SiteHost=prown\getAbove($SiteAbove);       // Каталог хостинга
   if(isset($_REQUEST['formSubmit'])) 
   {
      if(empty($_REQUEST['formDoor']))
      {
         echo("<p>Элемент для тестирования Вами не выбран!</p>\n</body>\n</html>\n");
      }
      else
      {
         $aDoor=$_REQUEST['formDoor'];
         $N=count($aDoor);
         // Запускаем тестирование и трассировку выбранного элемента
         foreach($aPhpPrown as $k=>$v)
         {
            //echo '<input type="checkbox" checked name="formDoor[]" value="'.$k.'"/>'.$k.' - '.$v.'<br>';
            if(isChecked('formDoor',$k))
            {

               //echo $k.' тестируется.<br>';
               //echo $SiteHost."/TPhpPrown/TPhpPrownTests/".$k."_test.php";
               
               require_once $SiteHost.$TestsSubDir.$k."_proba.php";
               proba_TUploadToServer();                  
               //require_once($SiteHost.'/TSimpleTest/autorun.php');
               //require_once $SiteHost.$TestsSubDir.$k."_test.php";
            }
         }
         //require_once $SiteHost."/TPhpPrown/TPhpPrownTests/"."FinalMessage_test.php";
      } 
   }
}
// ****************************************************************************
// *            Вывести сообщение по тесту их двух подстрок, где              *
// *      первая подстрока дополнена справа заполнителем до нужной длины      *
// ****************************************************************************
function MakeTestMessage($Namei,$Name2='',$len=80,$Colori='#993300')
{
   $Name=$Namei; $Color=$Colori;
   PrepareColor($Name,$Color);
   echo 
      "<span style=\"color:".$Color."; font-weight:bold; ".
      "font-family:'Anonymous Pro', monospace; font-size:0.9em\">".' '.
      mb_str_padi($Name,$len,'.').' '.
      "</span>";
   SimpleMessage($Name2,$Colori);
}
// ****************************************************************************
// *         Вывести заголовок очередной тестируемой функции TPhpPrown        *
// ****************************************************************************
function MakeTitle($Name,$br="<br>")
{
   echo 
      "<span style=\"color:blue; font-weight:bold; font-size:1.1em\">".
      $br.$Name.
      "</span>"."<br>";
}
// ****************************************************************************
// *    Дополнить строку справа заполнителем до указанного размера строки     *
// ****************************************************************************
function mb_str_padi($input, $pad_length, $pad_string = '-', $pad_type = STR_PAD_RIGHT)
{
	$diff = strlen($input) - mb_strlen($input);
	return str_pad($input, $pad_length + $diff, $pad_string, $pad_type);
}
// ****************************************************************************
// *           Установить и вырезать из подстроки указание о её цвете         *
// *                   (выделить цвет и чистую подстроку)                     *
// ****************************************************************************
/**
 * Указание о цвете подстроки включается в её начало с ключевым словом 
 * "Color" и завершается ";", например:
 * $Name="Color=#993300;"."*** Цвет подстроки должен быть коричневым ***";
**/ 
function PrepareColor(&$Name,&$Color)
{
   $pattern="/Color=([0-9a-zA-Zа-яёА-ЯЁ\s\.\$\n\r\(\)-:,=&;]+);/u";
   $replacement="";
   $NameItog=preg_replace($pattern,$replacement,$Name);
   $value=preg_match($pattern,$Name,$matches,PREG_OFFSET_CAPTURE);
   if ($value>0) 
   {
      $ColorItog=$matches[0][0];
      $ColorItog=substr($ColorItog,0,strlen($ColorItog)-1);
      $ColorItog=substr($ColorItog,6);
   }
   else $ColorItog='black'; 
   $Color=$ColorItog;
   $Name=$NameItog;
}
// ****************************************************************************
// *               Вывести сообщение с переходом на новую строку,             *
// *               обычно по завершении очередного теста/подтеста             *
// ****************************************************************************
function SimpleMessage($Name2=' ',$Color='#993300')
{
   echo 
      "<span style=\"color:".$Color."; font-weight:bold; ".
      "font-family:'Anonymous Pro', monospace; font-size:0.9em\">".
      $Name2."</span>".' <br>';
}
// ***************************************************** FunctionsTools.php ***
