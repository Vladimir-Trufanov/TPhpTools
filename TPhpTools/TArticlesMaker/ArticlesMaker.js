// PHP7/HTML5, EDGE/CHROME                             *** ArticlesMaker.js ***

// ****************************************************************************
// * ArticlesMakerClass       ���� ������� ������������� ������ �� JavaScript *
// *                                                                          *
// * v1.0, 18.02.2023                               �����:      �������� �.�. *
// * Copyright � 2023 tve                           ���� ��������: 09.01.2023 *
// ****************************************************************************

      pathPhpTools="<?php echo pathPhpTools;?>";
      pathPhpPrown="<?php echo pathPhpPrown;?>";
      gncNoCue="<?php echo gncNoCue;?>"; 

      // **********************************************************************
      // *       ��������� ����������� ���� ������ �� 16 ��������� �������    *
      // **********************************************************************
      function GetPunktTestBase()
      {
         // �������� ��������� ����������� uid
         TestPoint=Number(localStorage.getItem('TestPoint'));
         if (Number.isNaN(TestPoint)) TestPoint=0;
         //console.log('� ���� '+TestPoint);
         // ������ ������ �� ����������� ������������ ������� ����������
         pathphp="TestBase.php";
         $.ajax({
            url: pathphp,
            type: 'POST',
            data: {TestPoint:TestPoint, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            // ������� ������ ��� ���������� ������� � PHP-��������
            error: function (jqXHR,exception) {SmarttodoError(jqXHR,exception)},
            // ������������ �������� ���������
            success: function(message)
            {
               // �������� �� ������� ������ ���������
               messa=FreshLabel(message);
               // �������� ��������� ������
               parm=JSON.parse(messa);
               // ���� ������, �� ������� ���������
               if (parm.error==true) Error_Info(parm.messa);
               // ����� ������ �������� ������������ uid-�
               else 
               {
                  //console.log('� ���� '+parm.TestPoint);
                  // �������� ��������� ����������� uid
                  localStorage.setItem('TestPoint',parm.TestPoint);
                  // ������� ���������, ��� ��� ������
                  // Info_Info(parm.messa); 
               }
            }
         });
      }
      // **********************************************************************
      // *        ������ ���������� ����-������� �� �������� ���������        *
      // **********************************************************************
      function UdalitMater(Uid)
      {
         $('#DialogWind').dialog
         ({
            buttons:[{text:"OK",click:function(){xUdalitMater(Uid)}}]
         });
         htmlText="������� ��������� �������� �� "+Uid+"?";
         Notice_Info(htmlText,"������� ��������");
      }
      function xUdalitMater(Uid)
      {
         // ������� � ������ ��������������� ��������� ���������� �������
         htmlText="������� ������ �� "+Uid+" �� �������!";
         // ��������� ������ �� ��������
         pathphp="deleteArt.php";
         // ������ ������ �� ����������� ������������ ������� ����������
         $.ajax({
            url: pathphp,
            type: 'POST',
            data: {idCue:Uid, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            // ������� ������ ��� ���������� ������� � PHP-��������
            error: function (jqXHR,exception) {SmarttodoError(jqXHR,exception)},
            // ������������ �������� ���������
            success: function(message)
            {
               // �������� �� ������� ������ ���������
               messa=FreshLabel(message);
               // �������� ��������� ������
               parm=JSON.parse(messa);
               // ������� ��������� ����������
               if (parm.NameArt==gncNoCue) htmlText=parm.NameArt+' Uid='+Uid;
               else htmlText=parm.NameArt;
               $('#DialogWind').html(htmlText);
            }
         });
         // ������� ������ �� ������� � ����������� �������� �� ��������
         delayClose=1500;
         $('#DialogWind').dialog
         ({
            buttons:[],
            hide:{effect:"explode",delay:delayClose,duration:1000,easing:'swing'},
            title: "�������� ���������",
         });
         // ��������� ����
         $("#DialogWind").dialog("close");
         // ������������� �������� ����� 4 �������
         setTimeout(function() {location.reload();}, 4000);
      }
      // **********************************************************************
      // *  ������ ���������� ����-������� �� ����� ������ ������� ���������� *
      // *  ��� ���������� ����� ������:                                      *
      // *         !!! 16.01.2023 - �� ������� ��������� ���������� �� ������ *
      // *       ����, ����� ��������� ��������. ��� ����������� ������ ��-�� *
      // *                     ����, ��� ������ ����������� � ������� ������. *
      // **********************************************************************
      function SelMatiSection(Uid)
      {
         pathphp="getNameCue.php";
         // ������ ������ �� ����������� ������������ ������� ����������
         $.ajax({
            url: pathphp,
            type: 'POST',
            data: {idCue:Uid, pathTools:pathPhpTools, pathPrown:pathPhpPrown},
            // ������� ������ ��� ���������� ������� � PHP-��������
            error: function (jqXHR,exception) {SmarttodoError(jqXHR,exception)},
            // ������������ �������� ���������
            success: function(message)
            {
               // �������� �� ������� ������ ���������
               messa=FreshLabel(message);
               // �������� ��������� ������
               parm=JSON.parse(messa);
               $('#Message').html(parm.NameGru+': ������� �������� � ���� ��� ����� ������');
               $('#nsCue').attr('value',Uid);
               $('#nsGru').attr('value',parm.NameGru);
            }
         });
      }
// ******************************************************* ArticlesMaker.js *** 
