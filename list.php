<?php
$jsonfileList = glob("*.json");
if (($jsonfileList === false) or (count($jsonfileList) == 0)) {
    echo '<a href="admin.php">Перейти к форме загрузки тестов</a><br>';
    exit('Ошибка поиска .json файлов');
}
$maxTestIndex = count($jsonfileList);
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тесты: Список тестов</title>
  </head>

  <body>
    <h1>Список тестов</h1>
    <ol>
        <?php
        foreach ($jsonfileList as $item) {
            echo "<li>" . $item . "</li>";
        }
        ?>
    </ol>


    <form action="test.php" method="GET">
      <p>Введите номер теста, который Вы хотите пройти</p>
      <input type="number" name="test_nm" value="" min="1" max="<?= $maxTestIndex?>">
      <input type="hidden" name="action" value="test">
      <input type="submit" value="Пройти тест">
    </form>
  </body>
</html>