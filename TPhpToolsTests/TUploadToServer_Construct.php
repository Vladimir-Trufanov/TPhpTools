 <?php
// PHP7/HTML5, EDGE/CHROME                *** TUploadToServer_Construct.php ***

// ****************************************************************************
// * TPhpTools.TBaseMaker           Протестировать методы Values,Rows, quotes *
// *                                                                          *
// * v1.0, 30.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  14.02.2021 *
// ****************************************************************************

function test_Construct($upload,$thiss)
{
   PointMessage('Проверяется стартовый метод класса');
   $count=3;
   if ($thiss!==NULL) $thiss->assertEqual($count,3);
   OkMessage();
}

// ****************************************** TUploadToServer_Construct.php ***
