<?php
// PHP7/HTML5, EDGE/CHROME                *** TBaseMaker_CreateBaseTest.php ***

// ****************************************************************************
// * TPhpTools                                   Создать тестовую базу данных *
// *                                                                          *
// * v1.0, 13.01.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2021 tve                          Дата создания:  13.01.2021 *
// ****************************************************************************
function CreateBaseTest()
{
   $Result=true;
   // Проверяем, есть ли тестовая база данных.
   // Если база существует, то удаляем ее. 
   $filename=$_SERVER['DOCUMENT_ROOT'].'/basemaker.db3';
   if (file_exists($filename)) 
   {
      if (!unlink($filename))
      {
         throw new Exception("Не удалось удалить тестовую базу данных $filename!");
      } 
      //else echo "Тестовая база данных $filename существует<br>";
   } 
   // Начинаем строить тестовую базу и заполнять заново
   $pathBase='sqlite:'.$filename; 
   $username='tve';
   $password='23ety17';                                         
   //$pdo = new PDO($pathBase);
   $pdo = new PDO(
      $pathBase, 
      $username,
      $password,
      array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION)
   );
   try 
   {
      $pdo->beginTransaction();
      
      $sql='CREATE TABLE vids ([id-vid] INTEGER PRIMARY KEY AUTOINCREMENT, vid TEXT)';
      $st = $pdo->query($sql);
      $sql='CREATE TABLE colours (
         [id-colour] INTEGER PRIMARY KEY AUTOINCREMENT,
         colour      TEXT
      )';
      $st = $pdo->query($sql);
      $sql='CREATE TABLE produkts (
         name        TEXT PRIMARY KEY,
         [id-colour] INTEGER,
         calories    NUMERIC( 5, 1 ),
         [id-vid]    INTEGER
      )';
      $st = $pdo->query($sql);
      
      // https://art-life-spb.ru/kaiioraz_frukty
      // https://sostavproduktov.ru/produkty/yagody
      // https://sostavproduktov.ru/potrebitelyu/vidy-produktov/frukty

      $sql="INSERT INTO [vids] ([id-vid], [vid]) VALUES (1, 'фрукты');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [vids] ([id-vid], [vid]) VALUES (2, 'ягоды');";
      $st = $pdo->query($sql);

      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (1, 'красные');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (2, 'голубые');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (3, 'жёлтые');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (4, 'оранжевые');";
      $st = $pdo->query($sql);
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (5, 'зелёные');";
      $st = $pdo->query($sql);


      $aProducts=[
         ['голубика',  2, 41, 2],
         ['брусника',  1, 41, 2],
         ['груши',     3, 42, 1],
         ['земляника', 1, 34, 2],
         ['рябина',    4, 81, 2],
         ['виноград',  5, 70, 1]
      ];
      $statement = $pdo->prepare("INSERT INTO [produkts] ".
         "([name], [id-colour], [calories], [id-vid]) VALUES ".
         "(:name,  :idcolour,   :calories,  :idvid);");
      $i=0;
      foreach ($aProducts as [$name,$idcolor,$calories,$idvid])
      $statement->execute(["name"=>$name, "idcolour"=>$idcolor, "calories"=>$calories, "idvid"=>$idvid]);
      
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

