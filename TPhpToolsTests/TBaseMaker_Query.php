<?php
// PHP7/HTML5, EDGE/CHROME                         *** TBaseMaker_Query.php ***

// ****************************************************************************
// * TPhpTools.TBaseMaker                          Протестировать метод Query *
// *                                                                          *
// * v1.0, 30.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  14.02.2021 *
// ****************************************************************************

function test_Query($db,$thiss)
{
   PointMessage('Тестируется метод Query');

   // Начинаем строить тестовую базу и заполнять заново
   try 
   {
      $db->beginTransaction();
            
      $sql='CREATE TABLE vids ([id-vid] INTEGER PRIMARY KEY AUTOINCREMENT, vid TEXT)';
      $st = $db->query($sql);
      $sql='CREATE TABLE colours (
         [id-colour] INTEGER PRIMARY KEY AUTOINCREMENT,
         colour      TEXT
      )';
      $st = $db->query($sql);
      $sql='CREATE TABLE produkts (
         name        TEXT PRIMARY KEY,
         [id-colour] INTEGER,
         calories    NUMERIC( 5, 1 ),
         [id-vid]    INTEGER
      )';
      $st = $db->query($sql);
      
      // https://oracleplsql.ru/system-tables-sqlite.html
      //$sql='SELECT name FROM sqlite_master';      
      //$st = $db->query($sql);
      
      $sql='SELECT name FROM sqlite_master';      
      $prod1=$db->queryRows($sql);
      print_r($prod1);
      
      //Array 
      //( [0] => Array ( [name] => vids ) 
      //  [1] => Array ( [name] => sqlite_sequence ) )
      
      $sign=array( 
      0=>array('name'=>'vids'), 
      1=>array('name'=>'sqlite_sequence'), 
      );

      //$thiss->assertEqual($prod1,$sign);
      echo '==='.$prod1[0]['name'].'===';
      
      /*
      $tablename='vids';
      //gettype($db->exec("SELECT count(*) FROM $tablename"));
      $db->isTable($tablename); 
      
      $tablename='colours';
      //gettype($db->exec("SELECT count(*) FROM $tablename"));
      $db->isTable($tablename); 
      
      //$tablename='colours';
      //gettype($db->exec("SELECT count(*) FROM $tablename"));
      //$tableExists = gettype($db->exec("SELECT count(*) FROM vids")) == 'integer';
      //$tableExists = gettype($db->exec("SELECT count(*) FROM colours")) == 'integer';
      */
      
      
      
      /*
      $sql='CREATE TABLE colours (
         [id-colour] INTEGER PRIMARY KEY AUTOINCREMENT,
         colour      TEXT
      )';
      $st = $db->query($sql);
      $sql='CREATE TABLE produkts (
         name        TEXT PRIMARY KEY,
         [id-colour] INTEGER,
         calories    NUMERIC( 5, 1 ),
         [id-vid]    INTEGER
      )';
      $st = $db->query($sql);
      */
      
      $db->commit();
   } 
   catch (Exception $e) 
   {
      // Если в транзакции, то делаем откат изменений
      if ($db->inTransaction()) 
      {
         $db->rollback();
      }
      // Продолжаем исключение
      throw $e;
      //echo $e->getMessage();
   }
   
   /*
   $sql='SELECT COUNT(*) FROM vids';
   $sign=2;
   $count=$db->queryValue($sql);
   $thiss->assertEqual($count,2);
   */
   
   OkMessage();
}
// *************************************************** TBaseMaker_Query.php ***
