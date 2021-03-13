<?php
// PHP7/HTML5, EDGE/CHROME                  *** TBaseMaker_UpdateInsert.php ***

// ****************************************************************************
// * TPhpTools.TBaseMaker           Протестировать методы Values,Rows, quotes *
// *                                                                          *
// * v1.0, 16.02.2021                              Автор:       Труфанов В.Е. *
// * Copyright © 2021 tve                          Дата создания:  16.02.2021 *
// ****************************************************************************

function test_UpdateInsert($db,$thiss)
{
   PointMessage('Проверяются методы Update и Insert');
   $sql='SELECT COUNT(*) FROM vids';
   $count=$db->queryValue($sql);
   $thiss->assertEqual($count,0);
   
   // Заполняем таблицы тестовой базы
   try 
   {
      $db->beginTransaction();

      $sql="INSERT INTO [vids] ([id-vid],[vid]) VALUES ('1','фрукты');";
      $st = $db->query($sql);
      $sql="INSERT INTO [vids] ([id-vid],[vid]) VALUES ('2','ягоды');";
      $st = $db->query($sql);
      
      $result = $db->insert('vids',array('id-vid' => 5, 'vid' => 'трава'));
      $result = $db->insert('vids',array('id-vid' => 3, 'vid' => 'корни'));

     
      $colour=array('id-colour'=>1,'colour'=>'красные');
      $result=$db->insert('colours',$colour);
      $aColours=array
      (
         array('id-colour'=>2,'colour'=>'голубые'),
         array('id-colour'=>3,'colour'=>'чёрные'),
         array('id-colour'=>4,'colour'=>'оранжевые'),
         array('id-colour'=>5,'colour'=>'зелёные')
      );
      foreach ($aColours as $colour)
      $result=$db->insert('colours',$colour);

      $aProducts=[
         ['голубика',  2, 41, 2],
         ['брусника',  1, 41, 2],
         ['груши',     3, 42, 5],   //  1
         ['земляника', 1, 34, 5],   //  2
         ['рябина',    4, 81, 2] ,
        /* ['рябина',    4, 81, 2] , */
         ['виноград',  5, 70, 1]    
      ];
      
      foreach ($aProducts as [$name,$idcolor,$calories,$idvid])
      $result=$db->insert('produkts',
         array('name'=>$name,'id-colour'=>$idcolor,'calories'=>$calories,'id-vid'=>$idvid));
      
      /*
      $sql="INSERT INTO [colours] ([id-colour], [colour]) VALUES (3, 'жёлтые');";
      $st = $db->query($sql);

      $aProducts=[
         ['голубика',  2, 41, 2],
         ['брусника',  1, 41, 2],
         ['груши',     3, 42, 1],
         ['земляника', 1, 34, 2],
         ['рябина',    4, 81, 2],
         ['виноград',  5, 70, 1]
      ];
      
      
      $statement = $db->prepare("INSERT INTO [produkts] ".
         "([name], [id-colour], [calories], [id-vid]) VALUES ".
         "(:name,  :idcolour,   :calories,  :idvid);");
      
      $i=0;
      foreach ($aProducts as [$name,$idcolor,$calories,$idvid])
      $statement->execute(["name"=>$name, "idcolour"=>$idcolor, "calories"=>$calories, "idvid"=>$idvid]);
      */
      
      $sql='
      CREATE TABLE artist(
      artistid    INTEGER PRIMARY KEY, 
      artistname  TEXT);
      ';
      $st = $db->query($sql);
     
      $sql='
      CREATE TABLE track(
      trackid     INTEGER,
      trackname   TEXT, 
      trackartist INTEGER,
      FOREIGN KEY(trackartist) REFERENCES artist(artistid)     
      );
      ';
      $st = $db->query($sql);
     
      $sql="
      INSERT INTO artist VALUES(1, 'Dean Martin');
      ";
      $st = $db->query($sql);
    
      $sql="
      INSERT INTO artist VALUES(2, 'Frank Sinatra');
      ";
      $st = $db->query($sql);
    
      $sql="
      INSERT INTO track VALUES(11, 'Thats Amore', 1);
      ";
      $st = $db->query($sql);
    
      $sql="
      INSERT INTO track VALUES(12, 'Christmas Blues', 1);
      ";
      $st = $db->query($sql);
    
      $sql="
      INSERT INTO track VALUES(13, 'My Way ', 2);
      ";
      $st = $db->query($sql);
    
      $sql="
      INSERT INTO track VALUES(14, 'Mr. Bojangles', 3);
      ";
      $st = $db->query($sql);
          
            
      
      
      $db->commit();
   } 
   catch (Exception  $e) 
   {
      if ($db->inTransaction()) 
      {
         $db->rollback();
      }
      //echo 'Подключение не удалось: ';
      throw $e;
   }
  
   
   
   
   
   
   
   
   OkMessage();
   
        
 
   
   
}
// ******************************************** TBaseMaker_UpdateInsert.php ***
