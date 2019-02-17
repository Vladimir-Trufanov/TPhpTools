<html>
<head>
  <title>ViewDir</title>
</head>
<body>

  <?php
  include('CtrlDir.php');
  // Входим в корневой каталог и печатаем его
  chdir($_SERVER['DOCUMENT_ROOT']);
  $CtrlDir=new TCtrlDir;
  $CtrlDir->Init();
  $CtrlDir->MakeTree(); 
  // Выводим текст фиксированным шрифтом
  echo "<pre>";
  $CtrlDir->Show();
  $CtrlDir->ViewList(); 
  echo "</pre>";
  ?>
</body>
</html>
