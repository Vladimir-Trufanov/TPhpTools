<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                            *** CommonIttvePw.php ***

// ****************************************************************************
// * TPhpTools                     Блок функций класса TArticleMaker для базы *
// *                                      данных материалов сайта "ittve.pw". *
// *                                                                          *
// * v1.0, 17.11.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  13.11.2022 *
// ****************************************************************************

// ****************************************************************************
// *      Создать таблицы базы данных и выполнить начальное заполнение        *
// ****************************************************************************
function CreateTables($pdo)
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
         [ -2, 'Раздел без материалов'],
         [ -1, 'Раздел'],
         [  0, 'Статья для сайта = материал'],
         [  1, 'Пример на умалчиваемом языке (по статье)'],
         [  2, 'Пример на PHP'],
         [  4, 'Пример на JavaScript'],
         [  8, 'Пример на Лазарусе/Delphi'],
         [ 16, 'Пример на Arduino-C']
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
         'DateArt  DATETIME,'.                                    // дата\время статьи сайта
         'Art      TEXT)';                                        // материал = статья сайта
      $st = $pdo->query($sql);
     
      // Заполняем таблицу материалов в начальном состоянии (на 2022-11-09)
      $aCharters=[                                                          
         [ 1, 0,-1, 'ittve.pw',                           '/',                                  acsAll,    0,''],
         [ 2, 1,-1, 'Ардуино',                            '/',                                  acsAll,    0,''],
         [ 3, 2, 0,    'Как было',                        'kak-bylo',                           acsAll,    0,''],
         [ 4, 2, 0,    'Вид из окна',                     'vid-iz-okna',                        acsAll,    0,''],
         [ 5, 2, 0,    'Погода',                          'pogoda',                             acsAll,    0,''],
         [ 6, 1,-1, 'Лада-Нива',                          '/',                                  acsAll,    0,''],
         [ 7, 6, 0,    'С чего все началось',             's-chego-vse-nachalos',               acsAll,    0,''],
         [ 8, 6, 0,    'А что внутри?',                   'a-chto-vnutri',                      acsAll,    0,''],
         [ 9, 6, 0,    'Эксперименты со строками',        'ehksperimenty-so-strokami',          acsAll,    0,''],
         [10, 1,-1, 'Стиль',                              '/',                                  acsAll,    0,''],
         [11,10, 0,    'Элементы стиля программирования', 'ehlementy-stilya-programmirovaniya', acsAll,    0,''],
         [12,10, 0,    'Пишите программы просто',         'pishite-programmy-prosto',           acsAll,    0,''],
         [13, 1,-1, 'Моделирование',                      '/',                                  acsAll,    0,''],
         [14, 1,-1, 'Учебники',                           '/',                                  acsAll,    0,''],
         [15, 1,-1, 'Сайт',                               '/',                                  acsAll,    0,''],
         [16,15, 0,    'Авторизоваться',                  'avtorizovatsya',                     acsAll,    0,''],
         [17,15, 0,    'Зарегистрироваться',              'zaregistrirovatsya',                 acsAll,    0,''],
         [18,15, 0,    'О сайте',                         'o-sajte',                            acsAll,    0,''],
         [19,15, 0,    'Редактировать материал',          'redaktirovat-material',              acsAutor,  0,''],
         [20,15, 0,    'Изменить настройки',              'izmenit-nastrojki',                  acsAll,    0,''],
         [21,15, 0,    'Отключиться',                     'otklyuchitsya',                      acsAll,    0,''],
         [22, 2, 0,    'Статья2 по Ардуино',              'arduino2',                           acsClose,  0,''],
         [23, 2, 0,    'Статья3 по Ардуино',              'arduino3',                           acsClose,  0,''],
         [24,22,-1,    'Тема4 по Ардуино',                '/',                                  acsClose,  0,''],
         [25,22,-1,    'Тема5 по Ардуино',                '/',                                  acsClose,  0,''],
         [26,25, 0,       'Проба51',                      'proba51',                            acsClose,  0,''],
         [27,25, 0,       'Проба52',                      'proba52',                            acsClose,  0,''],
         [28, 1,-1, 'Проба11',                            '/',                                  acsClose,  0,''],
         [29, 0,-1, 'ittve.end',                          '/',                                  acsAll,    0,'']
      ];

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
         'Сomment     TEXT)';                                      // комментарий к изображению
      $st = $pdo->query($sql);

      // Создаём контрольную таблицу базы данных   
      $sql='CREATE TABLE ctrlpw ('.
         'bid         VARCHAR NOT NULL,'.    // наименование базы данных
         'СommBase    TEXT)';                // комментарий по базе данных
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

// ****************************************************************************
// *      Построить html-код ТАБЛИЦЫ меню по базе данных материалов сайта     *
// *                       с сортировкой по полям                             *
// ****************************************************************************
// Включить ссылку в текущую строку таблицы
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
// Построить html-код в строке ТАБЛИЦЫ меню по базе данных материалов сайта   
function _MakeTblMenu($basename,$username,$password,
          $ListFields,$SignAsc,$SignDesc) 
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
   ShowTreePw($pdo,1,1,$cLast,$nLine,$cli,$FirstUl,$lvl,$otlada);
   unset($pdo);          
}
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
function cUidPid($Uid,$Pid,$cLast)
{
   $c='<!-- '.$cLast.' '.(1000+$Uid).'-'.(1000+$Pid).' --> ';
   return $c;
}
function ShowTreePw($pdo,$ParentID,$PidIn,&$cLast,&$nLine,&$cli,&$lvl,$otlada,$FirstUl=' id="main-menu" class="sm sm-mint"')
{
   $lvl++; 
   $cSQL="SELECT uid,NameArt,Translit,pid FROM stockpw WHERE pid=".$ParentID." ORDER BY uid";
   $stmt = $pdo->query($cSQL);
   $table = $stmt->fetchAll();

   if (count($table)>0) 
   {
      // Выводим <ul>. Перед ним </li> не выводим.
      echo(SpacesOnLevel($lvl,$cLast,0,0,$otlada).'<ul'.$FirstUl.'>'."\n"); $cLast='+ul';
      // 
      foreach ($table as $row)
      {
         $nLine++; $cLine=''; 
         if ($otlada) $cLine=$cLine.' #'.$nLine.'#';
         $Uid = $row["uid"]; $Pid = $row["pid"]; $Translit = $row["Translit"];
         // Перед <li> выводим предыдущее </li>, если не было <ul>.
         if ($cLast<>'+ul') 
         {
             $cli=SpacesOnLevel($lvl,$cLast,$Uid,$Pid,$otlada)."</li>\n";
             echo($cli); $cLast='-li';
         }
         //  
         echo(SpacesOnLevel($lvl,$cLast,$Uid,$Pid,$otlada)."<li> "); $cLast='+li';
         
         if ($Translit=='/')
         {
            echo('<a href="'.$Translit.'">'.$row['NameArt'].$cLine.'</a>'."\n"); 
         }
         else
         {
            echo('<a href="'.'?Com='.$Translit.'">'.$row['NameArt'].$cLine.'</a>'."\n"); 
         }
         // Вместо вывода </li> формируем строку для вывода по условию перед <ul> и <li>
         ShowTreePw($pdo,$Uid,$Pid,$cLast,$nLine,$cli,$lvl,$otlada,''); 
         $lvl--; 
      }
      // Перед </ul> ставим предыдущее </li>
      $cli=SpacesOnLevel($lvl,$cLast,0,0,$otlada)."</li>\n";
      echo($cli); $cLast='-li';
      echo(SpacesOnLevel($lvl,$cLast,0,0,$otlada)."</ul>\n");  $cLast='-ul';
   }
}
// ****************************************************************************
// * Создать резервную копию базы данных и заново построить новую базу данных *
// ****************************************************************************
function _BaseFirstCreate($basename,$username,$password) 
{
   // Получаем спецификацию файла базы данных материалов
   $filename=$basename.'.db3';
   // Проверяем существование и удаляем файл копии базы данных 
   $filenameOld=$basename.'-copy.db3';
   _UnlinkFile($filename);
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
   CreateTables($pdo);
   \prown\ConsoleLog('Созданы таблицы и выполнено начальное заполнение'); 
   
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
// *************************************************************************
// *       Показать пример меню (с использованием smartmenu или без)       *
// *************************************************************************
function _ShowSampleMenu() 
{
   $Menu='
   <li><a href="/">ММС Лада-Нива</a>
      <ul>
         <li><a href="?Com=s-chego-vse-nachalos">С чего все началось</a></li>     
         <li><a href="?Com=a-chto-vnutri">А что внутри?</a></li>
         <li><a href="?Com=ehksperimenty-so-strokami">Эксперименты со строками</a></li>
      </ul>
   </li>
   <li><a href="/">Стиль</a>
      <ul>
         <li><a href="?Com=ehlementy-stilya-programmirovaniya">Элементы стиля программирования</a></li>
         <li><a href="?Com=pishite-programmy-prosto">Пишите программы просто</a></li>
      </ul>
   </li>
   <li><a href="/">Моделирование</a></li>
   <li><a href="/">Учебники</a></li>
   <li><a href="/">Сайт</a>
      <ul>
         <li><a href="?Com=avtorizovatsya">Авторизоваться</a></li>
         <li><a href="?Com=zaregistrirovatsya">Зарегистрироваться</a></li>
         <li><a href="?Com=o-sajte">О сайте</a></li>
         <li><a href="?Com=redaktirovat-material">Редактировать материал</a></li>
         <li><a href="?Com=izmenit-nastrojki">Изменить настройки</a></li>
         <li><a href="?Com=otklyuchitsya">Отключиться</a></li>
      </ul>
   </li>
   ';
   echo "\n"; 
   echo '<ul id="main-menu" class="sm sm-mint">';
   echo $Menu;
   echo '</ul>';
   echo "\n"; 
}

// ****************************************************** CommonIttvePw.php ***
