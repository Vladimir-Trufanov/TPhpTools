<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                            *** CommonIttveMe.php ***

// ****************************************************************************
// * TPhpTools                     Блок функций класса TArticleMaker для базы *
// *                                      данных материалов сайта "ittve.me". *
// *                                                                          *
// * v1.0, 23.12.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  13.11.2022 *
// ****************************************************************************

// _BaseFirstCreate($basename,$username,$password,$aCharters)                 - Создать резервную копию базы данных и заново построить новую базу данных
// _MakeMenu($basename,$username,$password)                                   - Построить html-код меню по базе данных материалов сайта 
// _MakeTblMenu($basename,$username,$password,$ListFields,$SignAsc,$SignDesc) - Построить html-код в строке ТАБЛИЦЫ меню по базе данных материалов сайта 
// _ShowSampleMenu()                                                          - Показать пример меню (с использованием smartmenu или без)
// aRecursLevel(&$array,$data,$pid=0,$level=0)                                - Сформировать массив для представления таблицы до уровня
// aRecursPath(&$array,&$array_idx_lvl,$data,$pid=0,$level=0,$path="")        - Сформировать массив представления таблицы c указанием путей    
// aViewLevel($array)                                                         - Вывести содержимое массива в первом виде - до уровня 
// aViewPath($array)                                                          - Вывести содержимое массива с путями и транслитом  
// cUidPid($Uid,$Pid,$cLast)                                                  - Обеспечить смещение строк меню при отладке 
// ShowCaseMe($pdo,$ParentID,$PidIn,&$cLast,&$nLine,&$cli,&$lvl,$FirstUl=' class="accordion"')
// ShowTreeMe($pdo,$ParentID,$PidIn,&$cLast,&$nLine,&$cli,&$lvl,$otlada,$FirstUl=' class="accordion"')
// sort_link_th($title,$a,$b,$SignAsc,$SignDesc)                              - Включить ссылку в текущую строку таблицы меню с сортировкой по полям
// SpacesOnLevel($lvl,$cLast,$Uid,$Pid,$otlada)                               - Обеспечить иммитацию пробелов смещения строк меню при отладке 
// CreateTables($pdo,$aCharters)                                              - Создать таблицы базы данных и выполнить начальное заполнение  

// -------------------------------------------------------- ЗАПРОСЫ ПО БАЗЕ ---
// CountPoint($pdo,$ParentID)  - Выбрать число записей по родителю                  
// SelRecord($pdo,$UnID)       - Выбрать запись по идентификатору 

// ****************************************************************************
// * Создать резервную копию базы данных и заново построить новую базу данных *
// ****************************************************************************
function _BaseFirstCreate($basename,$username,$password,$aCharters) 
{
   // Получаем спецификацию файла базы данных материалов
   $filename=$basename.'.db3';
   // Проверяем существование и удаляем файл копии базы данных 
   $filenameOld=$basename.'-copy.db3';
   _UnlinkFile($filenameOld);
   \prown\ConsoleLog('Проверено существование и удалена копия базы данных: '.$filenameOld);  
   // Создаем копию базы данных
   if (file_exists($filename)) 
   {
      if (rename($filename,$filenameOld))
      {
         \prown\ConsoleLog('Получена копия базы данных: '.$filenameOld);  
      }
      else
      {
         \prown\ConsoleLog('Не удалось переименовать базу данных: '.$filename);
      }
   } 
   else 
   {
      \prown\ConsoleLog('Прежней версии базы данных '.$filename.' не существует');
   }
   // Проверяем существование и удаляем файл базы данных 
   _UnlinkFile($filename);
   \prown\ConsoleLog('Проверено существование и удалён старый файл базы данных: '.$filename);  
   // Создается объект PDO и файл базы данных
   $pdo=_BaseConnect($basename,$username,$password);
   \prown\ConsoleLog('Создан объект PDO и файл базы данных');
   // Создаём таблицы базы данных
   CreateTables($pdo,$aCharters);
   \prown\ConsoleLog('Созданы таблицы и выполнено начальное заполнение'); 
   
   // Отрабатываем действия при отладке создания базы данных
   if ($aCharters=='-')
   {
      // Выбираем контрольную таблицу для меню из базы данных и удаляем объект
      $stmt = $pdo->query("SELECT * FROM stockpw");
      $table = $stmt->fetchAll();
      unset($pdo);          
      \prown\ConsoleLog('Выбрана таблица материалов из базы данных'); 
      // Формируем массив для представления таблицы
      $arrayl = array(); 
      aRecursLevel($arrayl,$table);
      echo 'Таблица материалов из базы данных: '.$filename; 
      aViewLevel($arrayl);
      \prown\ConsoleLog('Сформирован массив для представления таблицы'); 
      // Формируем массив c указанием путей  
      $array = array();                         // выходной массив
      $array_idx_lvl = array();                 // индекс по полю level
      echo '<br>';  
      echo 'Таблица материалов c указанием путей и транслита из базы данных: '.$filename;
      aRecursPath($array,$array_idx_lvl,$table); 
      aViewPath($array);
      \prown\ConsoleLog('Сформирован массив c указанием путей и транслита');
   } 
}
// ****************************************************************************
// *          Построить html-код меню по базе данных материалов сайта         *
// ****************************************************************************
function _MakeMenu($basename,$username,$password) 
{
   // Подсоединяемся к базе данных
   $pdo=_BaseConnect($basename,$username,$password);
   // Готовим параметры и вырисовываем меню
   $lvl=-1; $cLast='+++';
   $nLine=0; $cli=""; 
   // Параметр $otlada при необходимости используется для просмотра в коде
   // страницы вложенности тегов и вызова рекурсий 
   $otlada=false;
   ShowTreeMe($pdo,1,1,$cLast,$nLine,$cli,$lvl,$otlada);
   unset($pdo);          
}
// ****************************************************************************
// * Построить html-код в строке ТАБЛИЦЫ меню по базе данных материалов сайта *
// ****************************************************************************
function _MakeTblMenu($basename,$username,$password,$ListFields,$SignAsc,$SignDesc) 
{
   // Подсоединяемся к базе данных
   $pdo=_BaseConnect($basename,$username,$password);
   // Формируем массив для сортировок по выбранным полям в возрастающем и 
   // убывающем порядке по образцу:
   // $sort_list = array(
   //   'uid_asc'      => '"uid"',
   //   'uid_desc'     => '"uid" DESC',
   //   'pid_asc'      => '"pid"',
   //   'pid_desc'     => '"pid" DESC',
   //   'NameArt_asc'  => '"NameArt"',
   //   'NameArt_desc' => '"NameArt" DESC',
   //   'IdCue_asc'    => '"IdCue"',
   //   'IdCue_desc'   => '"IdCue" DESC',
   // );
   $sort_list=array();
   foreach ($ListFields as $key => $value) 
   {
      $sort_list = $sort_list+
      array($key.'_asc' => '"'.$key.'"');
      $sort_list = $sort_list+
      array($key.'_desc' => '"'.$key.'" DESC');
   }
   // Выбираем из параметра запроса значение GET-переменной и задаем
   // способ сортировки таблицы
   $sort = @$_GET['Sort'];
   if (array_key_exists($sort, $sort_list)) 
   {
	   $sort_sql = $sort_list[$sort];
   } 
   else 
   {
	  $sort_sql = reset($sort_list);
   }
   // Формируем список выбранных полей для запроса их значений из базы данных
   // убывающем порядке по образцу: 'uid,pid,NameArt,IdCue'
   $fields='';
   foreach ($ListFields as $key => $value) $fields=$fields.$key.',';
   $fields=rtrim($fields,',');
   // Выбираем значения указанных полей из базы данных по образцу:	
   // $cSQL="SELECT uid,pid,NameArt,IdCue FROM stockpw ORDER BY uid";
   $cSQL="SELECT ".$fields." FROM stockpw ORDER BY {$sort_sql}";
   $stmt=$pdo->query($cSQL);
   $list=$stmt->fetchAll();

   // Формируем таблицу
   echo '<table id="ipvTable"> <thead> <tr>';
   foreach ($ListFields as $key => $value) 
   {
      echo '<th class="ipvHead">'; 
      echo sort_link_th($value,$key.'_asc',$key.'_desc',$SignAsc,$SignDesc); 
      echo '</th>'; 
   }
   echo '</tr> </thead> <tbody>';
   
   foreach ($list as $row):
      echo '<tr class="ipvRow">';
      foreach ($ListFields as $key => $value) 
      {
         echo '<td class="ipvColumn">'; echo $row[$key]; echo '</td>'; 
      }
      echo '</tr>';
   endforeach; 
   echo '</tbody> </table>';
   unset($pdo);          
}
// *************************************************************************
// *       Показать пример меню (с использованием smartmenu или без)       *
// *************************************************************************
function _ShowSampleMenu() 
{
   $Menu='
   <ul class="accordion">
   <li id="moya-zhizn" class="moya-zhizn"><a href="#moya-zhizn">Моя жизнь<span>495</span></a>
      <ul class="sub-menu">
         <li><a href="#osobennosti-ustrojstva-vintikov-v-moej-golove"><em>1</em>Особенности устройства винтиков в моей голове<span>01.02.2013</span></a></li>			
      </ul>
   </li>
   <li id="mikroputeshestviya" class="mikroputeshestviya"><a href="#mikroputeshestviya">Микропутешествия<span>26</span></a>
   <ul class="sub-menu">
      <li><a href="#kindasovo-zemlya-karelskogo-yumora"><em>1</em>Киндасово - земля карельского юмора<span>20.06.2010</span></a></li>	
      <li><a href="#gora-sampo-ozero-svetlyj-les-tropinka-v-nebo"><em>2</em>Гора Сампо. Озеро, светлый лес, тропинка в небо<span>23.06.2010</span></a></li>
      <li><a href="#part2"><em>3</em>Падозеро, кладбище заключенных лагеря №517<span>03.07.2010</span></a></li>
      <li><a href="#part2"><em>4</em>Таёжный зоопарк на озере Сямозеро<span>04.07.2010</span></a></li>
      <li><a href="#part2"><em>5</em>Шелтозер. Так жили вепсы<span>10.07.2010</span></a></li>
      <li><a href="#part2"><em>6</em>Полоса 2300 - военный аэродром в Гирвасе<span>17.07.2010</span></a></li>
      <li><a href="#part2"><em>8</em>Чертов стул, кусочек ботанического сада<span>11.09.2010</span></a></li>
      <li><a href="#part2"><em>10</em>Благовещенский Ионо-Яшезерский мужской монастырь<span>10.10.2010</span></a></li>
   </ul>                                                                                     
   </li>
   <li id="part3" class="vsyakoe-raznoe"><a href="#part3">Всякое-разное<span>58</span></a>
      <ul class="sub-menu">
         <li><a href="#part3"><em>1</em>Всякое-разное<span>05.02.1958</span></a></li>
      </ul>
   </li>
   <li id="part4" class="v-kontakte"><a href="#part4">В контакте<span>58</span></a>
      <ul class="sub-menu">
         <li><a href="#part4"><em>1</em>В контакте<span>05.02.1958</span></a></li>
      </ul>
   </li>
   <li id="part5" class="moj-mir"><a href="#part5">Мой мир<span>58</span></a>
      <ul class="sub-menu">
         <li><a href="#part5"><em>1</em>Мой мир<span>05.02.1958</span></a></li>
      </ul>
   </li>
   <li id="part6" class="progulki"><a href="#part6">Прогулки<span>58</span></a>
      <ul class="sub-menu">
         <li><a href="#part6"><em>1</em>Прогулки<span>05.02.1958</span></a></li>
      </ul>
   </li>
   <li id="part22" class="dopolneniya-k-mikroputeshestviyam"><a href="#part22">Дополнения к микропутешествиям<span>58</span></a>
      <ul class="sub-menu">
         <li><a href="#part22"><em>1</em>Дополнения к микропутешествиям<span>05.02.1958</span></a></li>
      </ul>
   </li>
   <li id="part99" class="perepechatka"><a href="#part99">Перепечатка<span>58</span></a>
      <ul class="sub-menu">
         <li><a href="#part99"><em>1</em>Перепечатка<span>05.02.1958</span></a></li>
      </ul>
   </li>
   </ul>
   ';
   echo $Menu;
}
// *************************************************************************
// *                   Показать пример меню (где i вместо a)               *
// *************************************************************************
function _ShowProbaMenu() 
{
   $Menu='
   <ul class="accordion">
   <li id="moya-zhizn" class="moya-zhizn"><i>Моя жизнь<a href="#495"><span>495</span></a></i>
      <ul class="sub-menu">
         <li><i><em>1</em>Особенности устройства винтиков в моей голове<span>01.02.2013</span></i></li>			
      </ul>
   </li>
   <li id="mikroputeshestviya" class="mikroputeshestviya"><i>Микропутешествия<a href="#26"><span>26</span></a></i>
      <ul class="sub-menu">
         <li><i><em>12</em>Киндасово - земля карельского юмора<span>20.06.2010</span></i></li>			
         <li><i><em>13</em>Таёжный зоопарк на озере Сямозеро<span>04.07.2010</span></i></li>			
      </ul>                                                                                     
   </li>
   <li id="vsyakoe-raznoe" class="vsyakoe-raznoe"><i>Всякое-разное<a href="#1958"><span>1958</span></a></i>
   </li>
   <li id="progulki" class="progulki"><i>Прогулки<a href="#201"><span>201</span></a></i>
      <ul class="sub-menu">
         <li><i><em>21</em>Охота на медведя<span>24.07.2010</span></i></li>			
      </ul>
   </li>
   <li id="dopolneniya" class="dopolneniya"><i>Дополнения к микропутешествиям<a href="#15"><span>15</span></a></i>
   </li>
   </ul>
   ';
   echo $Menu;
   // *************************************************************************
   // *                 Вывести сообщение по вставленному в тег клику         *
   // *               .'<i onclick="iShowProbaMenu('."'progulki'".')">'.      *
   // *************************************************************************
   ?> 
   <script>
   function iShowProbaMenu(stri='заглушка')
   {
      console.log(stri);
   }
   </script>
   <?php
}
// ****************************************************************************
// *          Сформировать массив для представления таблицы до уровня         *
// *              (по мотивам - https://m.habr.com/ru/post/280944/)           *
// ****************************************************************************
function aRecursLevel(&$array,$data,$pid = 0,$level = 0)
{
   foreach ($data as $row)   
   {
      // Смотрим строки, pid которых передан в функцию,
      // начинаем с нуля, т.е. с корня сайта
      if ($row['pid'] == $pid)   
      { 
         // Собираем строку в ассоциативный массив
         $_row['uid']=$row['uid'];
         $_row['pid']=$row['pid'];
         // Функцией str_pad добавляем точки
         $_row['NameArt']=$_row['NameArt']=str_pad('', $level*3, '.').$row['NameArt']; 
         // Добавляем уровень
         $_row['level']=$level;      
         $_row['IdCue']=$row['IdCue'];
         $_row['access']=$row['access'];
         $_row['Translit']=$row['Translit'];       
         $_row['Name']=$row['NameArt'];       
         // Прибавляем каждую строку к выходному массиву
         $array[]=$_row; 
         // Строка обработана, теперь запустим эту же функцию для текущего uid, то есть
         // пойдёт обратотка дочерней строки (у которой этот uid является pid-ом)
         aRecursLevel($array,$data,$row['uid'],$level + 1);
      }
   }
}
// ****************************************************************************
// *         Сформировать массив представления таблицы c указанием путей      *
// ****************************************************************************
function aRecursPath(&$array,&$array_idx_lvl,$data,$pid=0,$level=0,$path="")
{
   foreach ($data as $row)   
   {
      // Смотрим строки, pid которых передан в функцию,
      // начинаем с нуля, т.е. с корня сайта
      if ($row['pid'] == $pid)   
      { 
         // Собираем строку в ассоциативный массив
         $_row['uid']=$row['uid'];
         $_row['pid']=$row['pid'];
         // Функцией str_pad добавляем точки
         $_row['NameArt']=$_row['NameArt']=str_pad('', $level*3, '.').$row['NameArt']; 
         // Добавляем уровень
         $_row['level']=$level;      
         $_row['IdCue']=$row['IdCue'];
         $_row['path']=$path."/".$row['NameArt'];   // добавляем имя к пути
         $_row['Translit']=$row['Translit'];        // добавляем транслит
         $_row['access']=$row['access'];
         $array[$row['uid']] = $_row;   // Результирующий массив индексируемый по uid
         // Для быстрой выборки по level, формируем индекс
         $array_idx_lvl[$level][$row['uid']] = $row['uid'];
         // Строка обработана, теперь запустим эту же функцию для текущего uid, то есть
         // пойдёт обработка дочерней строки (у которой этот uid является pid-ом)
         aRecursPath($array,$array_idx_lvl,$data,$row['uid'],$level+1,$_row['path']);
      } 
   }
}
// ****************************************************************************
// *           Вывести содержимое массива в первом виде - до уровня           *
// ****************************************************************************
function aViewLevel($array)
{
   echo '<pre>';
   // Выводим шапку
   echo '<table border=2>';
   echo '<tr>';
   echo '<td>UID</td>'; 
   echo '<td>'.str_pad('PID',4," ",STR_PAD_LEFT).'</td>'; 
   echo '<td> NAMEART</td>'; 
   echo '<td>LEVEL</td>'; 
   echo '<td>'.str_pad('IDCUE',6," ",STR_PAD_LEFT).'</td>'; 
   echo '<td>'.' access'.'</td>'; 
   echo '</tr>';        
   // Выводим данные
   foreach ($array as $value)
   {
      echo '<tr>';
      echo '<td>'; 
      echo str_pad($value['uid'],3," ",STR_PAD_LEFT);
      echo '</td>'; 
      echo '<td>'; 
      echo str_pad($value['pid'],4," ",STR_PAD_LEFT);
      echo '</td>'; 
      echo '<td>'; 
      echo ' '.$value['NameArt']; 
      echo '</td>'; 
      echo '<td>'; 
      echo str_pad($value['level'],5," ",STR_PAD_LEFT);
      echo '</td>'; 
      echo '<td>'; 
      echo str_pad($value['IdCue'],6," ",STR_PAD_LEFT);
      echo '</td>'; 
      echo '<td>'; 
      if ($value['access']==acsAutor) echo ' АВТОР';
      else if ($value['access']==acsClose) echo ' Закрыт';
      else echo ' Все';
      echo '</td>'; 
      echo '</tr>';
   }
   echo '</table>';
   echo '</pre>';
}
// ****************************************************************************
// *             Вывести содержимое массива с путями и транслитом             *
// ****************************************************************************
function aViewPath($array)
{
   echo '<pre>';
   // Выводим шапку
   echo '<table border=2>';
   echo '<tr>';
   echo '<td>UID</td>'; 
   echo '<td>'.str_pad('PID',4," ",STR_PAD_LEFT).'</td>'; 
   echo '<td> NAMEART</td>'; 
   echo '<td>LEVEL</td>'; 
   echo '<td> PATH</td>'; 
   echo '<td> TRANSLIT</td>'; 
   echo '<td>'.str_pad('IDCUE',6," ",STR_PAD_LEFT).'</td>'; 
   echo '<td>'.' access'.'</td>'; 
   echo '</tr>';        
   // Выводим данные
   foreach ($array as $value)
   {
      echo '<tr>';
      echo '<td>'; 
      echo str_pad($value['uid'],3," ",STR_PAD_LEFT);
      echo '</td>'; 
      echo '<td>'; 
      echo str_pad($value['pid'],4," ",STR_PAD_LEFT);
      echo '</td>'; 
      echo '<td>'; 
      echo ' '.$value['NameArt']; 
      echo '</td>'; 
      echo '<td>'; 
      echo str_pad($value['level'],5," ",STR_PAD_LEFT);
      echo '</td>'; 
      echo '<td>'; 
      echo ' '.$value['path'];
      echo '</td>'; 
      echo '<td>'; 
      echo ' '.$value['Translit'];
      echo '</td>'; 
      echo '<td>'; 
      echo str_pad($value['IdCue'],6," ",STR_PAD_LEFT);
      echo '</td>'; 
      echo '<td>'; 
      if ($value['access']==acsAutor) echo ' АВТОР';
      else if ($value['access']==acsClose) echo ' Закрыт';
      else echo ' Все';
      echo '</td>'; 
      echo '</tr>';
   }
   echo '</table>';
   echo '</pre>';
}
// ****************************************************************************
// *              Обеспечить смещение строк меню при отладке                  *
// ****************************************************************************
function cUidPid($Uid,$Pid,$cLast)
{
   $c='<!-- '.$cLast.' '.(1000+$Uid).'-'.(1000+$Pid).' --> ';
   return $c;
}
// ****************************************************************************
// *       Сформировать строки меню для выборки записи для редактирования:    *
// *                    $cli - вставка конца пункта меню                      *            
// ****************************************************************************
function ShowCaseMe($pdo,$ParentID,$PidIn,&$cLast,&$nLine,&$cli,&$lvl,$FirstUl=' class="accordion"')
{
   // Определяем текущий уровень меню
   $lvl++; 
   // Выбираем все записи одного родителя
   $cSQL="SELECT uid,NameArt,Translit,pid,IdCue,DateArt FROM stockpw WHERE pid=".$ParentID." ORDER BY uid";
   $stmt = $pdo->query($cSQL);
   $table = $stmt->fetchAll();

   if (count($table)>0) 
   {
      echo('<ul'.$FirstUl.'>'."\n"); $cLast='+ul';
      // Перебираем все записи родителя, подсчитываем количество, формируем пункты меню
      $nPoint=0;
      foreach ($table as $row)
      {
         $nLine++; $cLine=''; 
         $Uid=$row["uid"]; $Pid=$row["pid"]; $Translit=$row["Translit"];
         $IdCue=$row["IdCue"]; $DateArt=$row["DateArt"]; 
         if ($cLast<>'+ul') 
         {
             $cli="</li>\n";
             echo($cli); $cLast='-li';
         }
         if ($IdCue==-1)
         {
            echo('<li id="'.$Translit.'" class="'.$Translit.'"> '); 
            echo('<a href="#'.$Translit.'">'.$Uid.' '.$row['NameArt'].'</a>'."\n"); 
         } 
         else
         {
            $nPoint++;
            echo("<li> ");
            echo('<a href="?arti='.$Translit.'">'.'<em>'.$Uid.'</em>'.$row['NameArt'].$cLine.'</a>'."\n"); 
         }
         $cLast='+li';
         ShowCaseMe($pdo,$Uid,$Pid,$cLast,$nLine,$cli,$lvl,' class="sub-menu"'); 
         $lvl--; 
      }
      $cli="</li>\n";
      echo($cli); $cLast='-li'; 
      echo("</ul>\n");  $cLast='-ul';
   }
}
// ****************************************************************************
// *           Сформировать строки меню для записей одного родителя           *
// ****************************************************************************
function ShowTreeMe($pdo,$ParentID,$PidIn,&$cLast,&$nLine,&$cli,&$lvl,$otlada,$FirstUl=' class="accordion"')
{
   // Определяем текущий уровень меню
   $lvl++; 
   // Выбираем все записи одного родителя
   $cSQL="SELECT uid,NameArt,Translit,pid,IdCue,DateArt FROM stockpw WHERE pid=".$ParentID." ORDER BY uid";
   $stmt = $pdo->query($cSQL);
   $table = $stmt->fetchAll();

   if (count($table)>0) 
   {
      // Выводим <ul>. Перед ним </li> не выводим.
      echo(SpacesOnLevel($lvl,$cLast,0,0,$otlada).'<ul'.$FirstUl.'>'."\n"); $cLast='+ul';
      // Перебираем все записи родителя, подсчитываем количество, формируем пункты меню
      $nPoint=0;
      foreach ($table as $row)
      {
         $nLine++; $cLine=''; 
         if ($otlada) $cLine=$cLine.' ='.$nLine.'=';
         $Uid=$row["uid"]; $Pid=$row["pid"]; $Translit=$row["Translit"];
         $IdCue=$row["IdCue"]; $DateArt=$row["DateArt"]; 
         if ($cLast<>'+ul') 
         {
             $cli=SpacesOnLevel($lvl,$cLast,$Uid,$Pid,$otlada)."</li>\n";
             echo($cli); $cLast='-li';
         }
         // Выводим li и href для раздела (IdCue=-1)
         // <li id="moya-zhizn" class="moya-zhizn"><a href="#moya-zhizn">Моя жизнь<span>495</span></a>
         if ($IdCue==-1)
         {
            echo(SpacesOnLevel($lvl,$cLast,$Uid,$Pid,$otlada).'<li id="'.$Translit.'" class="'.$Translit.'"> '); 
            echo('<a href="#'.$Translit.'">'.$Uid.' &#129392; '.$row['NameArt'].$cLine.CountPoint($pdo,$Uid).'</a>'."\n"); 
         } 
         // Выводим li и href для статьи
         // <li><a href="#osobennosti-ustrojstva-vintikov-v-moej-golove"><em>1</em>Особенности устройства винтиков в моей голове<span>01.02.2013</span></a></li>			
         else
         {
            $nPoint++;
            echo(SpacesOnLevel($lvl,$cLast,$Uid,$Pid,$otlada)."<li> ");
            echo('<a href="#'.$Translit.'">'.'<em>'.$Uid.'</em>'.$row['NameArt'].$cLine.'<span>'.$DateArt.'</span>'.'</a>'."\n"); 
         }
         $cLast='+li';
         ShowTreeMe($pdo,$Uid,$Pid,$cLast,$nLine,$cli,$lvl,$otlada,' class="sub-menu"'); 
         $lvl--; 
      }
      $cli=SpacesOnLevel($lvl,$cLast,0,0,$otlada)."</li>\n";
      echo($cli); $cLast='-li'; 
      echo(SpacesOnLevel($lvl,$cLast,0,0,$otlada)."</ul>\n");  $cLast='-ul';
   }
}
// ****************************************************************************
// *   Включить ссылку в текущую строку таблицы меню с сортировкой по полям   *
// ****************************************************************************
function sort_link_th($title,$a,$b,$SignAsc,$SignDesc) 
{
   $sort = @$_GET['Sort'];
   if ($sort == $a) 
   {
      return '<a class="ipvSort" href="?Sort=' . $b . '">' . $title . ' <i>'.$SignAsc.'</i></a>';
   } 
   elseif ($sort == $b) 
   {
      return '<a class="ipvSort" href="?Sort=' . $a . '">' . $title . ' <i>'.$SignDesc.'</i></a>'; 
   } 
   else 
   {
      return '<a class="ipvSort" href="?Sort=' . $a . '">' . $title . '</a>'; 
   }
}
// ****************************************************************************
// *       Обеспечить иммитацию пробелов смещения строк меню при отладке      *
// ****************************************************************************
function SpacesOnLevel($lvl,$cLast,$Uid,$Pid,$otlada)
{
   $i=1; $c=''; $c=cUidPid($Uid,$Pid,$cLast); 
   while ($i<=$lvl)
   {
      $c=$c.'...';
      $i++;
   }
   if ($otlada==false) $c='';
   return $c;
}
// ****************************************************************************
// *      Создать таблицы базы данных и выполнить начальное заполнение        *
// ****************************************************************************
function CreateTables($pdo,$aCharters)
{
   try 
   {
      $pdo->beginTransaction();
      // Включаем действие внешних ключей
      $sql='PRAGMA foreign_keys=on;';
      $st = $pdo->query($sql);
      // Создаём таблицу указателей типов статей   
      $sql='CREATE TABLE cue ('.
         'IdCue          INTEGER PRIMARY KEY NOT NULL UNIQUE,'.
         'NameCue        VARCHAR )';
      $st = $pdo->query($sql);
      // Заполняем таблицу указателей типов статей
      // (для правильного формирования тегов, введено понятие раздела без материалов. 
      // Добавление нового раздела в базу данных сопровождается пометкой
      // 'раздел без материалов', при появлении статей в нем метка меняется на 
      // 'раздел')
      $aСues=[
         [ -1, 'Раздел'],
         [  0, 'Статья для сайта = материал'],
      ];
      $statement = $pdo->prepare("INSERT INTO [cue] ".
         "([IdCue], [NameCue]) VALUES ".
         "(:IdCue,  :NameCue);");
      $i=0;
      foreach ($aСues as [$IdCue,$NameCue])
      $statement->execute([
         "IdCue"      => $IdCue, 
         "NameCue"    => $NameCue
      ]);
      // Создаём таблицу материалов (основу для построения меню)  
      $sql='CREATE TABLE stockpw ('.
         'uid      INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,'.  // идентификатор пункта меню (раздел или статья сайта)
         'pid      INTEGER NOT NULL,'.                            // указатель элемента уровнем выше - uid родителя	
         'IdCue    INTEGER NOT NULL REFERENCES cue(IdCue),'.      // указатель типа статьи
         'NameArt  VARCHAR NOT NULL,'.                            // заголовок материала = статьи сайта
         'Translit VARCHAR NOT NULL,'.                            // транслит заголовка
         'access   INTEGER NOT NULL,'.                            // доступ к пунктам меню (All/Autor)
         'DateArt  VARCHAR NOT NULL,'.                            // дата статьи сайта/юникод иконки раздела
         'Art      TEXT)';                                        // материал = статья сайта
      $st = $pdo->query($sql);
      // Заполняем таблицу материалов в начальном состоянии (на 2022-12-20)
      if ($aCharters=='-'){
      $aCharters=[                                                          
         [ 1, 0,-1, 'ittve.me',                                            '/',                                              acsAll,'20',''],
         [ 2, 1,-1, 'Моя жизнь',                                           'moya-zhizn',                                     acsAll,'20',''],
         [ 3, 2, 0,    'Особенности устройства винтиков в моей голове',    'osobennosti-ustrojstva-vintikov-v-moej-golove',  acsAll,'2010.12.30',''],
         [ 4, 1,-1, 'Микропутешествия',                                    'mikroputeshestviya',                             acsAll,'20',''],
         [ 5, 4, 0,    'Киндасово - земля карельского юмора',              'kindasovo-zemlya-karelskogo-yumora',             acsAll,'2010.05.20',''],
         [ 6, 4, 0,    'Гора Сампо. Озеро, светлый лес, тропинка в небо',  'gora-sampo-ozero-svetlyj-les-tropinka-v-nebo',   acsAll,'2010.06.23',''],
         [ 7, 4, 0,    'Падозеро, кладбище заключенных лагеря №517',       'padozero-kladbishche-zaklyuchennyh-lagerya-517', acsAll,'2010.07.03',''],
         [ 8, 4, 0,    'Таёжный зоопарк на озере Сямозеро',                'tayozhnyj-zoopark-na-ozere-syamozero',           acsAll,'2010.07.04',''],
         [ 9, 4, 0,    'Шелтозеро. Так жили вепсы',                        'sheltozero-tak-zhili-vepsy',                     acsAll,'2010.07.10',''],
         [10, 4, 0,    'Полоса 2300 - военный аэродром в Гирвасе',         'polosa-2300-voennyj-aehrodrom-v-girvase',        acsAll,'2010.07.17',''],
         [11, 4, 0,    'Чертов стул, кусочек ботанического сада',          'chertov-stul-kusochek-botanicheskogo-sada',      acsAll,'2010.09.12',''],
         [12, 4, 0,    'Деревянное чудо на холме',                         'derevyannoe-chudo-na-holme',                     acsAll,'2010.10.07',''],
         [13, 1,-1, 'Всякое-разное',                                       'vsyakoe-raznoe',                                 acsAll,'20',''],
         [14, 1,-1, 'В контакте',                                          'v-kontakte',                                     acsAll,'20',''],
         [15, 1,-1, 'Мой мир',                                             'moj-mir',                                        acsAll,'20',''],
         [16, 1,-1, 'Прогулки',                                            'progulki',                                       acsAll,'20',''],
         [17,16, 0,    'Охота на медведя',                                 'ohota-na-medvedya',                              acsAll,'2011.05.06',''],
         [18, 1,-1, 'Дополнения к микропутешествиям',                      'dopolneniya-k-mikroputeshestviyam',              acsAll,'20',''],
         [19, 1,-1, 'Перепечатка',                                         'perepechatka',                                   acsAll,'20',''],
         [20, 4, 0,    'Благовещенский Ионо-Яшезерский мужской монастырь', 'iono-yashezerskij-muzhskoj-monastyr',            acsAll,'2010.10.10',''],
         [21, 0,-1, 'ittve.end',                                           '/',                                              acsAll,'20','']
      ];}       
      $statement = $pdo->prepare("INSERT INTO [stockpw] ".
         "([uid], [pid], [IdCue], [NameArt], [Translit], [access], [DateArt], [Art]) VALUES ".
         "(:uid,  :pid,  :IdCue,  :NameArt,  :Translit,  :access,  :DateArt,  :Art);");
      $i=0;
      foreach ($aCharters as
          [$uid,  $pid,  $IdCue,  $NameArt,  $Translit,  $access,  $DateArt,  $Art])
      $statement->execute([
         "uid"      => $uid, 
         "pid"      => $pid, 
         "IdCue"    => $IdCue, 
         "NameArt"  => $NameArt, 
         "Translit" => $Translit, 
         "access"   => $access, 
         "DateArt"  => $DateArt, 
         "Art"      => $Art
      ]);
      // Создаём таблицу для списка изображений   
      $sql='CREATE TABLE picturepw ('.
         'uid         INTEGER NOT NULL REFERENCES stockpw(uid),'.  // идентификатор пункта меню (раздел или статья сайта)
         'NamePic     VARCHAR NOT NULL,'.                          // заголовок изображения к статье сайта
         'TranslitPic VARCHAR NOT NULL,'.                          // транслит заголовка изображения
         'DatePic     DATETIME,'.                                  // дата\время изображения
         'Сomment     TEXT,'.                                      // комментарий к изображению
         'Pic         BLOB)';                                      // изображение
      $st = $pdo->query($sql);
      // Создаём контрольную таблицу базы данных   
      $sql='CREATE TABLE ctrlpw ('.
         'bid         VARCHAR NOT NULL,'.    // наименование базы данных
         'СommBase    TEXT)';                // комментарий по базе данных
      $st = $pdo->query($sql);
      // Создаем индекс по транслиту в таблице материалов      
      $sql='CREATE INDEX IF NOT EXISTS iTranslit ON stockpw (Translit)';
      $st = $pdo->query($sql);
      $pdo->commit();
   } 
   catch (Exception $e) 
   {
      // Если в транзакции, то делаем откат изменений
      if ($pdo->inTransaction()) 
      {
         $pdo->rollback();
      }
      // Продолжаем исключение
     throw $e;
   }
}

// -------------------------------------------------------- ЗАПРОСЫ ПО БАЗЕ ---

// ****************************************************************************
// *                       Выбрать число записей по родителю                  *
// ****************************************************************************
function CountPoint($pdo,$ParentID)
{
   $cSQL='SELECT uid FROM stockpw WHERE pid='.$ParentID;
   $stmt = $pdo->query($cSQL);
   $table = $stmt->fetchAll();
   $nCount=count($table);
   if ($nCount==0) $Result='';
   else $Result='<span>'.$nCount.'</span>';
   return $Result; 
}

/*
// ****************************************************************************
// *             Выбрать $pid,$uid,$NameGru,$NameArt,$DateArt,$contents по транслиту             *
// ****************************************************************************
function _SelUidPid($pdo,$getArti,&$pid,&$uid,&$NameGru,&$NameArt,&$DateArt,&$contents)
{
   // Инициируем возвращаемые данные
   $pid=0; $uid=0; 
   $NameGru='Материал для редактирования не выбран!'; $NameArt=''; $DateArt='';
   // Выбираем по транслиту $pid,$uid,$NameArt
   $cSQL='SELECT * FROM stockpw WHERE Translit="'.$getArti.'"';
   //\prown\ConsoleLog('$getArti='.$getArti);
   $stmt=$pdo->query($cSQL);
   $table=$stmt->fetchAll();
   if (count($table)==1)
   {
      $pid=$table[0]['pid']; $uid=$table[0]['uid']; 
      $NameArt=$table[0]['NameArt']; $DateArt=$table[0]['DateArt'];
      $contents=$table[0]['Art'];
      // Добираем $NameGru
      $table=SelRecord($pdo,$pid); $NameGru=$table[0]['NameArt'];
   }
   return $table;
}
*/

// ****************************************************** CommonIttveMe.php ***
