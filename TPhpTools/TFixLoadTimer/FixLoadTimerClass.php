<?php 
                                          
// PHP7/HTML5, EDGE/CHROME                        *** FixLoadTimerClass.php ***

// ****************************************************************************
// * TPhpTools               *
// ****************************************************************************

//                                          Авторы: Котеров Д.В., Труфанов В.Е.
//                                          Дата создания:           03.02.2018
// Copyright © 2018 tve                     Посл.изменение:          05.02.2018

class FixLoadTimer
{
   public function __construct($mask=E_ALL,$ignoreOther=false)
   {
      echo '***'.$mask.'***<br>';
      ?>
      <script>
      
      function window_onload()
      {
         console.log('say1 onload');
      }

      window.onload = function()
      {
         console.log('say onload');
         window_onload();
         // Cтавим cookie name=value по текущему пути с бесконечной датой истечения
         document.cookie = "name=value; path=/; expires=0x6FFFFFFF";
         // Удаляем кукис
         //var date = new Date(0);
         //document.cookie = "name=; path=/; expires=" + date.toUTCString();
      }
      </script>
      <?php
   }
   public function __destruct()
   {
   }
   public function res($mes)
   {
      echo $mes;
   }
} 

// ************************************************** FixLoadTimerClass.php ***
