<?php
// PHP7/HTML5, EDGE/CHROME                  *** TBaseMaker_UpdateInsert.php ***

// ****************************************************************************
// * TPhpTools.TBaseMaker           Протестировать методы Values,Rows, quotes *
// *                                                                          *
// * v1.0, 30.12.2020                              Автор:       Труфанов В.Е. *
// * Copyright © 2020 tve                          Дата создания:  14.02.2021 *
// ****************************************************************************

function test_UpdateInsert($db,$thiss)
{
   PointMessage('Проверяются методы queryValue(s) по запросам без параметров');
   $sql='SELECT COUNT(*) FROM vids';
   $count=$db->queryValue($sql);
   $thiss->assertEqual($count,0);
   OkMessage();
}
// ******************************************** TBaseMaker_UpdateInsert.php ***
