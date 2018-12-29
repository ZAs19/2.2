<?php
if (isset($_POST) && isset($_FILES) && isset($_FILES['userfile'])) {
    $filename = $_FILES['userfile']['name'];
    $tmpFile = $_FILES['userfile']['tmp_name'];
    $testDir = '';//'tests';
    $pathParts = pathinfo($filename);
    if ($pathParts['extension'] === 'json') {
        move_uploaded_file($tmpFile, $testDir . $filename);
        echo 'Тест загружен!';
    }
    else {
        echo 'Извините, нужен файл с расширением JSON';
    }
}

?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тесты: Администрирование</title>
  </head>

  <body>
    <h1>Администрирование</h1>

    <form method="POST" action="admin.php" enctype="multipart/form-data">
        <label>Загрузите *.json файл с тестом</label><br>
        <input type="hidden" name="MAX_FILE_SIZE" value="300000">
        <input type="file" name="userfile">
        <input type="submit" value="Добавить тест">
    </form>

    <a href="list.php">Перейти к списку тестов</a>

  </body>
</html>

