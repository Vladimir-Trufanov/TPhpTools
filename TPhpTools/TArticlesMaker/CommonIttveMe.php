<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                            *** CommonIttveMe.php ***

// ****************************************************************************
// * TPhpTools                     Блок функций класса TArticleMaker для базы *
// *                                      данных материалов сайта "ittve.me". *
// *                                                                          *
// * v1.0, 13.11.2022                              Автор:       Труфанов В.Е. *
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
         'DateArt  DATETIME,'.                                    // дата\время статьи сайта
         'Art      TEXT)';                                        // материал = статья сайта
      $st = $pdo->query($sql);
     
      // Заполняем таблицу материалов в начальном состоянии (на 2022-11-09)
      $aCharters=[                                                          
         [ 1, 0,-1, 'ittve.me',                                            '/',                                              acsAll,0,''],
         [ 2, 1,-1, 'Моя жизнь',                                           '/',                                              acsAll,0,''],
         [ 3, 2, 0,    'Особенности устройства винтиков в моей голове',    'osobennosti-ustrojstva-vintikov-v-moej-golove',  acsAll,0,''],
         [ 4, 1,-1, 'Микропутешествия',                                    '/',                                              acsAll,0,''],
         [ 5, 4, 0,    'Киндасово - земля карельского юмора',              'kindasovo-zemlya-karelskogo-yumora',             acsAll,0,''],
         [ 6, 4, 0,    'Гора Сампо. Озеро, светлый лес, тропинка в небо',  'gora-sampo-ozero-svetlyj-les-tropinka-v-nebo',   acsAll,0,''],
         [ 7, 4, 0,    'Падозеро, кладбище заключенных лагеря №517',       'padozero-kladbishche-zaklyuchennyh-lagerya-517', acsAll,0,''],
         [ 8, 4, 0,    'Таёжный зоопарк на озере Сямозеро',                'tayozhnyj-zoopark-na-ozere-syamozero',           acsAll,0,''],
         [ 9, 4, 0,    'Шелтозеро. Так жили вепсы',                        'sheltozero-tak-zhili-vepsy',                     acsAll,0,''],
         [10, 4, 0,    'Полоса 2300 - военный аэродром в Гирвасе',         'polosa-2300-voennyj-aehrodrom-v-girvase',        acsAll,0,''],
         [11, 4, 0,    'Чертов стул, кусочек ботанического сада',          'chertov-stul-kusochek-botanicheskogo-sada',      acsAll,0,''],
         [12, 4, 0,    'Благовещенский Ионо-Яшезерский мужской монастырь', 'iono-yashezerskij-muzhskoj-monastyr',            acsAll,0,''],
         [13, 1,-1, 'Всякое-разное',                                       '/',                                              acsAll,0,''],
         [14, 1,-1, 'В контакте',                                          '/',                                              acsAll,0,''],
         [15, 1,-1, 'Мой мир',                                             '/',                                              acsAll,0,''],
         [16, 1,-1, 'Прогулки',                                            '/',                                              acsAll,0,''],
         [17,16, 0,    'Охота на медведя',                                 'ohota-na-medvedya',                              acsAll,0,''],
         [18, 1,-1, 'Дополнения к микропутешествиям',                      '/',                                              acsAll,0,''],
         [19, 1,-1, 'Перепечатка',                                         '/',                                              acsAll,0,''],
         [20, 0,-1, 'ittve.end',                                           '/',                                              acsAll,0,'']
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
// ****************************************************************************
// *          Построить html-код меню по базе данных материалов сайта         *
// ****************************************************************************
function _MakeMenu($basename,$username,$password,$FirstUl) 
{
   // Подсоединяемся к базе данных
   $pdo=_BaseConnect($basename,$username,$password);
   // Готовим параметры и вырисовываем меню
   $lvl=-1; $cLast='+++';
   $nLine=0; $cli=""; 
   // Параметр $otlada при необходимости используется для просмотра в коде
   // страницы вложенности тегов и вызова рекурсий 
   $otlada=false;
   ShowTree16($pdo,1,1,$cLast,$nLine,$cli,$FirstUl,$lvl,$otlada);
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
function ShowTree16($pdo,$ParentID,$PidIn,&$cLast,&$nLine,&$cli,$FirstUl,&$lvl,$otlada)
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
         ShowTree16($pdo,$Uid,$Pid,$cLast,$nLine,$cli,'',$lvl,$otlada); 
         $lvl--; 
      }
      // Перед </ul> ставим предыдущее </li>
      $cli=SpacesOnLevel($lvl,$cLast,0,0,$otlada)."</li>\n";
      echo($cli); $cLast='-li';
      echo(SpacesOnLevel($lvl,$cLast,0,0,$otlada)."</ul>\n");  $cLast='-ul';
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
// ****************************************************** CommonIttveMe.php ***
