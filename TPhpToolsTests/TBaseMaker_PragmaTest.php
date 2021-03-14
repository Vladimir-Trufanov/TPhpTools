<?php
// PHP7/HTML5, EDGE/CHROME                    *** TBaseMaker_PragmaTest.php ***

// ****************************************************************************
// * TPhpTools             Проверить настройки по запросу PRAGMA:foreign_keys *
// *                                                                          *
// * v1.0, 14.03.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2021 tve                          Дата создания:  13.03.2021 *
// ****************************************************************************

// ----------------------------------------------------------------------------
// Создать и заполнить тестовые таблицы
// ----------------------------------------------------------------------------
function MakeTables($pdo)
{
   // Строим и заполняем таблицу артистов
   $sql='
      CREATE TABLE artist(
      artistid    INTEGER PRIMARY KEY, 
      artistname  TEXT);
   ';
   $st = $pdo->query($sql);

   $sql="
      INSERT INTO artist VALUES(1, 'Dean Martin');
   ";
   $st = $pdo->query($sql);

   $sql="
      INSERT INTO artist VALUES(2, 'Frank Sinatra');
   ";
   $st = $pdo->query($sql);
   
   // Строим и заполняем таблицу дорожек
   $sql='
      CREATE TABLE track(
      trackid     INTEGER,
      trackname   TEXT, 
      trackartist INTEGER,
      FOREIGN KEY(trackartist) REFERENCES artist(artistid) ON UPDATE CASCADE    
      );
   ';
   $st = $pdo->query($sql);
     
   $sql="
      INSERT INTO track VALUES(11, 'Thats Amore', 1);
   ";
   $st = $pdo->query($sql);
    
   $sql="
      INSERT INTO track VALUES(12, 'Christmas Blues', 1);
   ";
   $st = $pdo->query($sql);
    
   $sql="
      INSERT INTO track VALUES(13, 'My Way ', 2);
   ";
   $st = $pdo->query($sql);
}
// ----------------------------------------------------------------------------
// Добавить запись с отсутствующим внешним ключем
// ----------------------------------------------------------------------------
function MakeRecExcept($pdo)
{
   // должно быть исключение
   $sql="
      INSERT INTO track VALUES(14, 'Mr. Bojangles', 3);
   ";
   $st = $pdo->query($sql);
}
// ----------------------------------------------------------------------------
// Выполнить тесты с PRAGMA foreign_keys 
// ----------------------------------------------------------------------------
function PragmaBaseTest($pdo)
{
   $Result=true;
   $Except=false;
   // Начинаем новую строку сообщений
   //SimpleMessage();
   //PointMessage('Строится внешняя ссылочная таблица');
   $sql='
      PRAGMA foreign_keys=off;
   ';
   $st = $pdo->query($sql);
   try 
   {
      $pdo->beginTransaction();
      MakeTables($pdo);
      MakeRecExcept($pdo);
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
      //$Except=true;

   }
   OkMessage();

   /*
   // Включаем действие внешних ключей
   $sql='
   PRAGMA foreign_keys=on;
   ';
   $st = $pdo->query($sql);
   */
   /*
   // Строим таблицу дорожек с внешним ключем
   try 
   {
      $pdo->beginTransaction();
      
    
      
      
      
      //$sql="
      //SELECT * FROM track;
      //";
      //$st = $pdo->query($sql);
  
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
      //throw $e;
      SimpleMessage();
      PointMessage('Foreinkey база данных');
      //echo $e->getMessage();
      //trigger_error('Привет!');
   }
   */
}
// ********************************************** TBaseMaker_PragmaTest.php ***

