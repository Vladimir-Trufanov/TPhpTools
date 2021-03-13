<?php
// PHP7/HTML5, EDGE/CHROME                         *** TBaseMaker_Query.php ***

// ****************************************************************************
// * TPhpTools.TBaseMaker                          Протестировать метод Query *
// *                                                                          *
// * v1.0, 15.02.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  30.12.2020 *
// ****************************************************************************

function test_Query($db,$thiss)
{
   PointMessage('Тестируется метод Query');
   // Проверяем отсутствие таблиц в базе
   $x=$db->isTable('vids');
   $thiss->assertFalse($x);
   $x=$db->isTable('colours');
   $thiss->assertFalse($x);
   $x=$db->isTable('produkts');
   $thiss->assertFalse($x);
   // Создаем таблицы тестовой базы методом query
   try 
   {
      $db->beginTransaction();
      $sql='CREATE TABLE vids ([id-vid] INTEGER PRIMARY KEY, [vid] TEXT)';
      $st = $db->query($sql);
      $sql='CREATE TABLE colours (
         [id-colour] INTEGER PRIMARY KEY,
         [colour]      TEXT
      )';
      $st = $db->query($sql);
      $sql='CREATE TABLE produkts (
         name        TEXT PRIMARY KEY,
         [id-colour] INTEGER NOT NULL, 
         [calories]    NUMERIC( 5, 1 ),
         [id-vid]    INTEGER
      )';
/*
         ,
         CONSTRAINT PO_COLOUR
         FOREIGN KEY ([id-colour]) REFERENCES colour


         [id-colour] INTEGER NOT NULL REFERENCES colours ([id-colour]), */
      $st = $db->query($sql);
      $db->commit();
   } 
   catch (Exception $e) 
   {
      if ($db->inTransaction()) 
      {
         $db->rollback();
      }
      throw $e;
   }
   // Проверяем присутствие таблиц в базе
   $x=$db->isTable('vids');
   $thiss->assertTrue($x);
   $x=$db->isTable('colours');
   $thiss->assertTrue($x);
   $x=$db->isTable('produkts');
   $thiss->assertTrue($x);
   // Проверяем отсутствие записей в таблице
   $sql='SELECT COUNT(*) FROM vids';
   $count=$db->queryValue($sql);
   $thiss->assertEqual($count,0);
   OkMessage();
}
// *************************************************** TBaseMaker_Query.php ***
