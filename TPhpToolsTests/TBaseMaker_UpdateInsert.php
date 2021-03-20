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
      $result = $db->insert('vids',array('id-vid' => 78, 'vid' => 'испорченные'));
     
      $colour=array('id-colour'=>1,'colour'=>'красные');
      $result=$db->insert('colours',$colour);
      $aColours=array
      (
         array('id-colour'=>2,'colour'=>'голубые'),
         array('id-colour'=>3,'colour'=>'чёрные'),
         array('id-colour'=>4,'colour'=>'оранжевые'),
         array('id-colour'=>5,'colour'=>'зелёные'),
         array('id-colour'=>75,'colour'=>'почерневшие или сморщенные')
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

      // Проверяем загруженные записи
      $sql='SELECT * FROM produkts';
      $st=$db->query($sql);
      $aaProducts=[
        0=>['name'=>'голубика', 'id-colour'=>2,'calories'=>41,'id-vid'=>2],
        1=>['name'=>'брусника', 'id-colour'=>1,'calories'=>41,'id-vid'=>2], 
        2=>['name'=>'груши',    'id-colour'=>3,'calories'=>42,'id-vid'=>5], 
        3=>['name'=>'земляника','id-colour'=>1,'calories'=>34,'id-vid'=>5],
        4=>['name'=>'рябина',   'id-colour'=>4,'calories'=>81,'id-vid'=>2], 
        5=>['name'=>'виноград', 'id-colour'=>5,'calories'=>70,'id-vid'=>1]]; 
      if ($thiss!==NULL) $thiss->assertEqual($st,$aaProducts);
      
      // Отмечаем испорченные продукты с помощью query и сравниваем
      $sql=
         "update produkts set [id-vid]=78, [id-colour]=75, calories=0 ".
         "where name='земляника' or calories>=70";
      $st = $db->query($sql);
      $sql='SELECT * FROM produkts';
      $st=$db->query($sql);
      //echo '<br>';
      //print_r($st);
      $aaProducts=[
        0=>['name'=>'голубика', 'id-colour'=>2, 'calories'=>41,'id-vid'=> 2],
        1=>['name'=>'брусника', 'id-colour'=>1, 'calories'=>41,'id-vid'=> 2], 
        2=>['name'=>'груши',    'id-colour'=>3, 'calories'=>42,'id-vid'=> 5], 
        3=>['name'=>'земляника','id-colour'=>75,'calories'=> 0,'id-vid'=>78],
        4=>['name'=>'рябина',   'id-colour'=>75,'calories'=> 0,'id-vid'=>78], 
        5=>['name'=>'виноград', 'id-colour'=>75,'calories'=> 0,'id-vid'=>78]]; 
      if ($thiss!==NULL) $thiss->assertEqual($st,$aaProducts);
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
