<html>
<head>
  <title>ViewDir</title>
</head>
<body>

  <?php
  include('CtrlDir.php');
  // ������ � �������� ������� � �������� ���
  chdir($_SERVER['DOCUMENT_ROOT']);
  $CtrlDir=new TCtrlDir;
  $CtrlDir->Init();
  $CtrlDir->MakeTree(); 
  // ������� ����� ������������� �������
  echo "<pre>";
  $CtrlDir->Show();
  $CtrlDir->ViewList(); 
  echo "</pre>";
  ?>
</body>
</html>
