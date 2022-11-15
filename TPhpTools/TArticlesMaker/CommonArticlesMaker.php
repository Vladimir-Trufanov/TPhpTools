<?php namespace ttools; 
                                         
// PHP7/HTML5, EDGE/CHROME                      *** CommonArticlesMaker.php ***

// ****************************************************************************
// * TPhpTools               Блок общих функций класса TArticleMaker для базы *
// *                                                 данных материалов сайта. *
// *                                                                          *
// * v1.0, 14.11.2022                              Автор:       Труфанов В.Е. *
// * Copyright © 2022 tve                          Дата создания:  13.11.2022 *
// ****************************************************************************

// ****************************************************************************
// *                     Открыть соединение с базой данных                    *
// ****************************************************************************
function _BaseConnect($basename,$username,$password)
{
   // Получаем спецификацию файла базы данных материалов
   $filename=$basename.'.db3';
   // Создается объект PDO и файл базы данных
   $pathBase='sqlite:'.$filename; 
   // Подключаем PDO к базе
   $pdo = new \PDO(
      $pathBase, 
      $username,
      $password,
      array(\PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION)
   );
   return $pdo;
}
// ****************************************************************************
// *          Проверить существование и удалить файл из файловой системы      *
// *         (используется в случаях, когда необходимо перезаполнить файл)    *
// ****************************************************************************
function _UnlinkFile($filename)
{
   if (file_exists($filename)) 
   {
      if (!unlink($filename))
      {
         // Для файла базы данных выводится сообщение о неудачном удалении 
         // в случаях:
         //    а) база данных подключена к стороннему приложению;
         //    б) база данных еще привязана к другому объекту класса;
         //    в) прочее
         throw new Exception("Не удалось удалить файл $filename!");
      } 
   } 
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
// ************************************************ CommonArticlesMaker.php ***
