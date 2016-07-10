<?php

if ( ! function_exists( 'exif_imagetype' ) ) {
    function exif_imagetype ( $filename ) {
        if ( ( list($width, $height, $type, $attr) = getimagesize( $filename ) ) !== false ) {
            return $type;
        }
    return false;
    }
}

class Image {
	public $tmp = 'tmp';
	public $availableExtensions = array('jpg','jpeg','png');
	
	private $path;
	private $res;
	private $current;
	private $width;
	private $height;
	private $type;
	private $ext;
	private $size;
	
	private $selection = array();
	
	public function init() {
		
	}
	public function __toString() {
		return 'Image component';
	}
	
	public function load($path) {
		if (!file_exists($path)) throw new CException('Картинка не найдена');
		$m = exif_imagetype($path);
		if ($m != 2 && $m != 3) throw new CException('Поддерживаемые форматы иконки: jpeg, png');
		
		$this->path = $path;
		$info = @getimagesize($this->path);
		list($this->width, $this->height) = $info;
		list($this->type, $this->ext) = @explode('/', $info['mime']);
		if ($this->ext == 'pjpeg') $this->ext = 'jpeg';
		$this->size = filesize($this->path);
		
		$create = 'imagecreatefrom'.$this->ext;
		$this->res = $create($this->path);
		$this->current = $this->res;
		imagesavealpha($this->res, true);
		
		$this->select();
		
		return $this;
	}
	public function loadFromURL($url) {
		if (empty($this->tmp) || !is_dir($this->tmp)) throw new CException('Временная директория не существует');
		if (substr(sprintf('%o', fileperms('/'.$this->tmp)), -3) != '777') throw new CException('Временная директория не доступна для записи');
		
		$fp = fopen($url, 'rb');
		if (!$fp) throw new CException('Не могу открыть удаленный файл');
		
		$fileContent = '';
		while(!feof($fp)) {
			$fileContent .= fread($fp, 8192);
		}
		fclose($fp);
		
		$fileName = uniqid('imagetmp');
		$fp = fopen($this->tmp.'/'.$fileName, 'wb');
		fwrite($fp, $fileContent);
		fclose($fp);
		
		return $this->load($this->tmp.'/'.$fileName);
	}
	public function select($selection=null) {
		if (is_null($selection)) $selection = array(0,0,$this->width,$this->height);
		if (is_array($selection)) {
			$this->selection = array($selection[0],$selection[1],$selection[2],$selection[3]);
		}
		return $this;
	}
	public function crop($size=null) {
		$create = 'imagecreatefrom'.$this->ext;
		
		// Нужный размер иконки
		$width = $size[0] ? $size[0] : $this->selection[2];
		$height = $size[1] ? $size[1] : $this->selection[3];
		
		// Получаем масштаб
		$scale = 1;
		$widthScale = $this->selection[2] / $width; 
		$heightScale = $this->selection[3] / $height;
		if ($widthScale > $heightScale) {
			$scale = $heightScale;
		} else {
			$scale = $widthScale;
		}
		
		// Актуальный размер иконки (без артефактов)
		$actualWidth = round($this->selection[2] / $scale);
		$actualHeight = round($this->selection[3] / $scale);
		

		$actualSizedThumbnail = imagecreatetruecolor($actualWidth, $actualHeight);
		imagesavealpha($actualSizedThumbnail, true);
		$transparent = imagecolorallocatealpha($actualSizedThumbnail, 0, 0, 0 ,127);
		imagefill($actualSizedThumbnail, 0, 0, $transparent);
		
		imagecopyresampled($actualSizedThumbnail, $this->res, 0, 0, $this->selection[0], $this->selection[1], 
						   $actualWidth, $actualHeight, $this->selection[2], $this->selection[3]);
		
		// Смещение для центрирования иконки
		$offsetX = round(($actualWidth - $width) / 2);
		$offsetY = round(($actualHeight - $height) / 2);


		$this->current = imagecreatetruecolor($width, $height);
		imagesavealpha($this->current, true);
		$transparent = imagecolorallocatealpha($this->current, 0, 0, 0 ,127);
		imagefill($this->current, 0, 0, $transparent);
		$this->imagecopymerge_alpha($this->current, $actualSizedThumbnail, 0, 0, $offsetX, $offsetY, $width, $height, 100);
		return $this;		
	}
	
	// scale не больше заданных размеров
	public function scale($size=null) {
		$create = 'imagecreatefrom'.$this->ext;
		
		if ($size[0] == 'w') {
			$width = $size[1] ? $size[1] : $this->selection[2];
			if ($this->width <= $width) {
				$this->current = $this->res;
				return $this;
			}
			$scale = $this->selection[2] / $width;
		} else if ($size[0] == 'h') {
			$height = $size[1] ? $size[1] : $this->selection[3];
			if ($this->height <= $height) {
				$this->current = $this->res;
				return $this;
			}
			$scale = $this->selection[3] / $height;
		} else {
			// Нужный размер иконки
			$width = $size[0] ? $size[0] : $this->selection[2];
			$height = $size[1] ? $size[1] : $this->selection[3];
			
			if ($this->width <= $width && $this->height <= $height) {
				$this->current = $this->res;
				return $this;
			}
			
			// Получаем масштаб
			$scale = 1;
			$widthScale = $this->selection[2] / $width;
			$heightScale = $this->selection[3] / $height;
			if ($widthScale < $heightScale) {
				$scale = $heightScale;
			} else {
				$scale = $widthScale;
			}
		}
		
		// Актуальный размер иконки (без артефактов)
		$actualWidth = round($this->selection[2] / $scale);
		$actualHeight = round($this->selection[3] / $scale);
		
		$this->current = imagecreatetruecolor($actualWidth, $actualHeight);
		imagesavealpha($this->current, true);
		$transparent = imagecolorallocatealpha($this->current, 0, 0, 0 ,127);
		imagefill($this->current, 0, 0, $transparent);
		
		imagecopyresampled($this->current, $this->res, 0, 0, $this->selection[0], $this->selection[1], 
						   $actualWidth, $actualHeight, $this->selection[2], $this->selection[3]);
		
		return $this;
	}
	
	// scale не меньше заданных размеров
	public function scaleMin($size=null) {
		//print_r($size); 
		$width = $this->selection[2];
		$height = $this->selection[3];
		$mynew_width = $size[0];
		$mynew_height = $size[1];
		
		if($width < $mynew_width && $width > $height) {
			$new_width = $width;
			$new_height = ($height / $width) * $new_width;		
		} 
		elseif($height < $mynew_height && $width <= $height) {
			$new_height = $height;
			$new_width = ($width / $height) * $new_height;		
		} else {
			$width_scale = $width/$mynew_width;
			$height_scale = $height/$mynew_height;
			if($width_scale < $height_scale) {
				$new_width = $mynew_width;
				$new_height = ($height / $width) * $new_width;
			} else {
				$new_height = $mynew_height;
				$new_width = ($width / $height) * $new_height;			
			}		
		}
	
		$this->current = $this->res;
		
		$this->current = imagecreatetruecolor($new_width, $new_height);
		imagesavealpha($this->current, true);
		$transparent = imagecolorallocatealpha($this->current, 0, 0, 0 ,127);
		imagefill($this->current, 0, 0, $transparent);
		
		imagecopyresampled($this->current, $this->res, 0, 0, $this->selection[0], $this->selection[1], 
						   $new_width, $new_height, $this->selection[2], $this->selection[3]);

		return $this;		
		
	}
	
	public function save($destination=null) {
		if (is_null($destination)) $destination = $this->path;
		$save = 'image'.$this->ext;
		$save($this->current, $destination.'.'.$this->ext, $this->ext == 'png' ? 9 : 100);
		$this->current = $this->res;
		return $destination.'.'.$this->ext;
	}
	public function close() {
		imagedestroy($this->res);
	}
	public function getPath() {
		return $this->path;
	}
	public function getExt() {
		return $this->ext;
	}
	public function getWidth() {
		return $this->width;
	}
	public function getHeight() {
		return $this->height;
	}
	public function imagecopymerge_alpha($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h, $pct) {
		if(!isset($pct)){ 
			return false;
		} 
		$pct /= 100; 
		// Get image width and height 
		$w = imagesx( $src_im ); 
		$h = imagesy( $src_im ); 
		// Turn alpha blending off 
		imagealphablending( $src_im, false ); 
		// Find the most opaque pixel in the image (the one with the smallest alpha value) 
		$minalpha = 127; 
		for( $x = 0; $x < $w; $x++ ) 
		for( $y = 0; $y < $h; $y++ ){ 
			$alpha = ( imagecolorat( $src_im, $x, $y ) >> 24 ) & 0xFF; 
			if( $alpha < $minalpha ){ 
				$minalpha = $alpha; 
			} 
		} 
		//loop through image pixels and modify alpha for each 
		for( $x = 0; $x < $w; $x++ ){ 
			for( $y = 0; $y < $h; $y++ ){ 
				//get current alpha value (represents the TANSPARENCY!) 
				$colorxy = imagecolorat( $src_im, $x, $y ); 
				$alpha = ( $colorxy >> 24 ) & 0xFF; 
				//calculate new alpha 
				if( $minalpha !== 127 ){ 
					$alpha = 127 + 127 * $pct * ( $alpha - 127 ) / ( 127 - $minalpha ); 
				} else { 
					$alpha += 127 * $pct; 
				} 
				//get the color index with new alpha 
				$alphacolorxy = imagecolorallocatealpha( $src_im, ( $colorxy >> 16 ) & 0xFF, ( $colorxy >> 8 ) & 0xFF, $colorxy & 0xFF, $alpha ); 
				//set pixel with the new color + opacity 
				if( !imagesetpixel( $src_im, $x, $y, $alphacolorxy ) ){ 
					return false; 
				} 
			} 
		} 
		// The image copy 
		imagecopy($dst_im, $src_im, $dst_x, $dst_y, $src_x, $src_y, $src_w, $src_h);
	}

	
	public function resizeThumbnailImage($thumb_image_name, $image, $width, $height, $start_width, $start_height, $scale){

		list($imagewidth, $imageheight, $imageType) = getimagesize($image);
		$imageType = image_type_to_mime_type($imageType);
		
		$newImageWidth = ceil($width * $scale);
		$newImageHeight = ceil($height * $scale);
		$newImage = imagecreatetruecolor($newImageWidth,$newImageHeight);
		
		switch($imageType) {
			case "image/gif":
				$source=imagecreatefromgif($image); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				$source=imagecreatefromjpeg($image); 

				break;
			case "image/png":
			case "image/x-png":
				$source=imagecreatefrompng($image); 				
				break;
		}
		
		imagecopyresampled($newImage,$source,0,0,$start_width,$start_height,$newImageWidth,$newImageHeight,$width,$height);
		switch($imageType) {
			case "image/gif":
				imagegif($newImage,$thumb_image_name); 
				break;
			case "image/pjpeg":
			case "image/jpeg":
			case "image/jpg":
				imagejpeg($newImage,$thumb_image_name,90); 
				break;
			case "image/png":
			case "image/x-png":
				imagepng($newImage,$thumb_image_name);  
				break;
		}
		chmod($thumb_image_name, 0777);
		return $thumb_image_name;
	}	
	
	public function addBg($file, $maxWidth=0, $maxHeight=0) {

		$size = getimagesize($file);

		// если ширина и высота не переданы - добавляем поля до размера квадрата
		if($maxWidth==0 && $maxHeight==0) {
			if($size[0] > $size[1]) { $maxHeight = $size[0]; $maxWidth = $size[0];}
			if($size[1] > $size[0]) {  $maxHeight = $size[1]; $maxWidth = $size[1]; }		
		}
		$bgColor = array(255, 255, 255);
		$img = imagecreatefromjpeg($file);
		$width = imagesx($img);
		$height = imagesy($img);
		$kw = $width / $maxWidth;
		$kh = $height / $maxHeight;
		$k = $kw > $kh ? $kw : $kh;
		$newImg = imagecreatetruecolor($maxWidth, $maxHeight);
		$bg = imagecolorallocate($newImg, $bgColor[0], $bgColor[1], $bgColor[2]);
		imagefill($newImg, 0, 0, $bg);
		if($k > 1) {
			$newWidth = (int) ($width / $k);
			$newHeight = (int) ($height / $k);
		} else {
			$newWidth = $width;
			$newHeight = $height;
		}
		$left = (int) (($maxWidth - $newWidth) / 2);
		$top = (int) (($maxHeight - $newHeight) / 2);
		imagecopyresampled($newImg, $img, $left, $top, 0, 0, $newWidth, $newHeight, $width, $height);
		imagejpeg($newImg, $file, 100);	
		return $file;
	}	
}