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
   if ($thiss!==NULL) $thiss->assertEqual($count,0);
   
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
         ['виноград',  5, 70, 1]    
      ];
      
      foreach ($aProducts as [$name,$idcolor,$calories,$idvid])
      $result=$db->insert('produkts',
         array('name'=>$name,'id-colour'=>$idcolor,'calories'=>$calories,'id-vid'=>$idvid));
         
      $sql="update produkts set [id-vid]=2 where name='земляника'";
      $st = $db->query($sql);
      //$db->update('produkts',array('[id-vid]'=>1),"name='земляника'"); 
   
      
     
      $db->commit();
   } 
   catch (Exception  $e) 
   {
      if ($db->inTransaction()) $db->rollback();
      throw $e;
   }
   OkMessage();
}
// ******************************************** TBaseMaker_UpdateInsert.php ***
