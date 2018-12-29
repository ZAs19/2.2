<?php

if (!empty($_GET['action'])) {
    $action = $_GET['action'];
}
else {
    $action = 'none';
}

switch ($action) {
    case 'test':
        if (!empty($_GET['test_nm'])) {
            $testNmb = $_GET['test_nm'];
        } else {
            $testNmb = 0;
        }

        $jsonfileList = glob("*.json");
        if (($jsonfileList === false) or (count($jsonfileList) == 0)) {
            echo '<a href="admin.php">Перейти к форме загрузки тестов</a><br>';
            echo '<a href="list.php">Перейти к форме выбора теста</a>';
            exit('Ошибка поиска .json файлов');
        }

        if (($testNmb >= 1) and ($testNmb <= count($jsonfileList))) {
            $testfile = $jsonfileList[$testNmb - 1];
        }
        else {
            echo '<a href="admin.php">Перейти к форме загрузки тестов</a><br>';
            echo '<a href="list.php">Перейти к форме выбора теста</a>';
            exit('Тест не найден');
        }

//        $testfile = 'laws.json';

        $testJSON = file_get_contents($testfile);
        $curTest = json_decode($testJSON, true);
        if ($curTest === null) {
            exit('Ошибка декодирования .json файла');
        }
        $maxSum = 0;
        break;
    case 'calc':
        if (!empty($_GET['q'])) {
            $goals = $_GET['q'];
        } else {
            exit('Ошибка получения ответов на тест');
        }

        if (!empty($_GET['max'])) {
            $maxGoals = $_GET['max'];
        } else {
            $maxGoals = 0;
        }

        if ($maxGoals == 0) {
            exit('Ошибка подсчета максимально возможного результата');
        }

        $sumGoals = 0;
        $tmpSum = 0;

        foreach ($goals as $answers) {
            foreach ($answers as $shot) {
                $tmpSum = $tmpSum + $shot;
            }
            if ($tmpSum > 0) {
                $sumGoals = $sumGoals + $tmpSum;
            }
            $tmpSum = 0;
        }
        echo 'Ваш результат: ' . $sumGoals / $maxGoals * 100 . '%<br>';
        exit('Тест пройден');
    default:
        echo '<a href="admin.php">Перейти к форме загрузки тестов</a><br>';
        exit('Ошибка передачи параметра действия');
}
?>

<!DOCTYPE html>
<html lang="ru">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Тесты: прохождение теста</title>
  </head>

  <body>
    <h1>Прохождение теста <?= $testfile ?></h1>

    <form action="test.php" method="GET">
      <?php
      $i = 0;
      $j = 0;
      foreach ($curTest as $curQuestion) {
          $j++;
          if (count($curQuestion["answers"]) == count($curQuestion["results"])) {
              $i++;
          }
          else {
              continue;
          }
/*          if ($curQuestion["is_single"] == 'true') {
              $inputType = 'radio';
          }
          else {
              $inputType = 'checkbox';
          }
*/
          $inputType = 'checkbox'
      ?>
      <fieldset>
        <legend>Вопрос № <?= $i ?>: <?=$curQuestion["question"] ?></legend>
        <ol>
          <?php
          $q = 1;
          foreach ($curQuestion["answers"] as $key => $curAnswer) {
          ?>
          <li><input type="<?= $inputType ?>" name="<?= 'q[' . $j .']['. $key . ']'?>" value="<?= $curQuestion["results"][$q] ?>"><?= $curAnswer ?></li>
          <?php
              if ($curQuestion["results"][$q] > 0) {
                  $maxSum = $maxSum + $curQuestion["results"][$q];
              }
              $q++;
          }
          ?>
          <input type="hidden" name="<?= 'answ_count[' . $j .']'?>" value="<?= count($curQuestion["results"]) ?>">
        </ol>
      </fieldset>
          <?php
        }
        ?>
      <input type="hidden" name="action" value="calc">
      <input type="hidden" name="max" value="<?= $maxSum ?>">
      <input type="submit" value="Посчитать результаты">
    </form>

  </body>
</html>
