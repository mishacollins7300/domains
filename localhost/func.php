<?php
  function can_upload($file){
	// если имя пустое, значит файл не выбран
    if($file['name'] == '')
		return 'Вы не выбрали файл.';
	
	/* если размер файла 0, значит его не пропустили настройки 
	сервера из-за того, что он слишком большой */
	if($file['size'] == 0)
		return 'Файл слишком большой.';
	
	// разбиваем имя файла по точке и получаем массив
	$getMime = explode('.', $file['name']);
	// нас интересует последний элемент массива - расширение
	$mime = strtolower(end($getMime));
	// объявим массив допустимых расширений
	$types = array('jpg', 'png', 'gif', 'bmp', 'jpeg');
	
	// если расширение не входит в список допустимых - return
	if(!in_array($mime, $types))
		return 'Недопустимый тип файла.';
	
	return true;
  }
  
  function make_upload($file, $text){	

	// получаем полезные данные о картинке и водяном знаке
	$image_info = getimagesize($file['tmp_name']);
	$watermark_info = getimagesize('watermark.png');

	
	// определяем MIME-тип изображения, для выбора соответствующей функции
	$format = strtolower(substr($image_info['mime'], 
									 strpos($image_info['mime'], '/') + 1));
	
	// определяем названия функция для создания и сохранения картинки
	$im_cr_func = "imagecreatefrom" . $format;
	$im_save_func = "image" . $format;
	
	// загружаем изображение в php
	$img = $im_cr_func($file['tmp_name']);
	
	// загружаем в php наш водяной знак
	$watermark = imagecreatefrompng('watermark.png');
	
	// определяем координаты левого верхнего угла водяного знака
	$pos_x = ($image_info[0] - $watermark_info[0]) / 2; 
	$pos_y = ($image_info[1] - $watermark_info[1]) / 2; 
	
	// помещаем водяной знак на изображение
	imagecopy($img, $watermark, $pos_x, $pos_y, 0, 0, $watermark_info[0], 
					  $watermark_info[1]);
					  
	$font = "C:/OpenServer/domains/localhost/font2.ttf"; // Ссылка на шрифт
	$font_size = 54; // Размер шрифта
	$degree = 0; // Угол поворота текста в градусах
	$y = 110; // Смещение сверху (координата y)
	$x = 200; // Смещение слева (координата x)
 
	$color = imagecolorallocate($img, 138, 3, 3);
	
	
	// сохраняем изображение с уникальным именем
	$name = mt_rand(0, 10000) . $file['name'];
	$im_save_func($img, 'img/' . $name);
	$name2 = 'img/' . $name;
	$pic = imagecreatefrompng($name2);
	imagettftext($pic, $font_size, $degree, $x, $y, $color, $font, $text); // Функция нанесения текста
	imagepng($pic, "img/pic.png"); // Сохранение рисунка
	ob_start();
	$pdf = new FPDF('L');
	$pdf->AddPage();
	$pdf->Image('img/pic.png',0,0,0,0,'PNG');
	$pdf->Output('D','label.pdf', true); 
  }