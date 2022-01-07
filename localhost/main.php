<?php
  include_once('func.php');

  require_once( "fpdf/fpdf.php" )
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>ПЕРВАЯ КРУТЯ ЛАБА</title>
    <style>
      form{
        display: flex;
        flex-direction: column;
        justify-content: center;
        width: 300px;
      }
      label, input{
        margin-bottom: 10px;
      }
    </style>
  </head>
  <body>
      <h3>Фотошоп онлайн</h3>
      <p>Здесь можно загрузить файл для следующей обработки</p>
    <form method="post" enctype="multipart/form-data">
      <label for="file">Загрузи картинку♥</label>
      <input type="file" name="file" id="file">
      <label for="text">Добавить надпись</label>
      <input type="text" name="text" id="text">
      <input type="submit" value="Скачать в pdf">
    </form>
    <a href="/lab2/main.php">Следующая л/р</a>
    <?php
    // если была произведена отправка формы
    if(isset($_FILES['file'], $_POST['text'])) {
      // проверяем, можно ли загружать изображение
      $check = can_upload($_FILES['file']);
    
      if($check === true){
        // загружаем изображение на сервер
        make_upload($_FILES['file'], $_POST['text']);
        
      }
      else{
        // выводим сообщение об ошибке
        echo "<strong>$check</strong>";  
      }
    }
    ?>
  </body>
</html>