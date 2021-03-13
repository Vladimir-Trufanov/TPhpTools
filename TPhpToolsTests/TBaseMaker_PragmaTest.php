<?php
// PHP7/HTML5, EDGE/CHROME                *** TBaseMaker_CreateBaseTest.php ***

// ****************************************************************************
// * TPhpTools                                   Создать тестовую базу данных *
// *                                                                          *
// * v1.0, 14.02.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2021 tve                          Дата создания:  13.01.2021 *
// ****************************************************************************
function PragmaBaseTest($pathBase,$username,$password)
{
   $Result=true;
   // Подключаем PDO к базе
   //$pdo = new PDO($pathBase);
   $pdo = new PDO(
      $pathBase, 
      $username,
      $password,
      array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
   );
   // Включаем действие внешних ключей
   $sql='
   PRAGMA foreign_keys=on;
   ';
   $st = $pdo->query($sql);
   // Строим таблицу артистов
   try 
   {
      $pdo->beginTransaction();
      
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
      //echo $e->getMessage();
   }

   // Строим таблицу дорожек с внешним ключем
   try 
   {
      $pdo->beginTransaction();
      
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
    
      
      // д.быть исключение
      $sql="
      INSERT INTO track VALUES(14, 'Mr. Bojangles', 3);
      ";
      $st = $pdo->query($sql);
      
      
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
      throw $e;
      //echo $e->getMessage();
   }
  
}
// ****************************************** TBaseMaker_CreateBaseTest.php ***

