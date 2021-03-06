<?php
/**
* Class for handling images
*
*/
class CImage {
	
	/**
	* Members
	*
	*/
	private $src;
	private $verbose;
	private $saveAs;
	private $quality;
	private $ignoreCache;
	private $filesiz3;
	private $maxWidth;
	private $maxHeight;
	private $width;
	private $height;
	private $newWidth;
	private $newHeight;
	private $cropWidth;
	private $cropHeight;
	private $cropToFit;
	private $sharpen;
	private $pathToImage;
	private $cacheFileName;
	private $fileExtension;
	private $image;
	
	/**
	*
	* Constructor
	*
	*/
	public function __construct($src, $verbose, $saveAs, $quality, $ignoreCache, $newWidth, $newHeight, $cropToFit, $sharpen, $pathToImage){
		$this->maxWidth = $this->maxHeight = 2000;
		
		$this->src = $src;
		$this->verbose = $verbose;
		$this->saveAs = $saveAs;
		$this->quality = $quality;
		$this->ignoreCache = $ignoreCache;
		$this->newWidth = $newWidth;
		$this->newHeight = $newHeight;
		$this->cropToFit = $cropToFit;
		$this->sharpen = $sharpen;
		$this->pathToImage = $pathToImage;
	}
	
	
	/**
	* Display error message.
	*
	* @param string $message the error message to display.
	*/
	public function errorMessage($message) {
		header("Status: 404 Not Found");
		die('img.php says 404 - ' . htmlentities($message));
	}
	
	/**
	* Display log message.
	*
	* @param string $message the log message to display.
	*/
	public function verbose($message) {
		echo "<p>" . htmlentities($message) . "</p>";
	}

	/**
	* Output an image together with last modified header.
	*
	* @param string $file as path to the image.
	* @param boolean $verbose if verbose mode is on or off.
	*/
	public function outputImage($file) {
		$info = getimagesize($file);
		!empty($info) or $this->errorMessage("The file doesn't seem to be an image.");
		$mime   = $info['mime'];

		$lastModified = filemtime($file);  
		$gmdate = gmdate("D, d M Y H:i:s", $lastModified);

		if($this->verbose) {
		$this->verbose("Memory peak: " . round(memory_get_peak_usage() /1024/1024) . "M");
		$this->verbose("Memory limit: " . ini_get('memory_limit'));
		$this->verbose("Time is {$gmdate} GMT.");
		}

		if(!$this->verbose) header('Last-Modified: ' . $gmdate . ' GMT');
		if(isset($_SERVER['HTTP_IF_MODIFIED_SINCE']) && strtotime($_SERVER['HTTP_IF_MODIFIED_SINCE']) == $lastModified){
			if($this->verbose) { $this->verbose("Would send header 304 Not Modified, but its verbose mode."); exit; }
			header('HTTP/1.0 304 Not Modified');
		} else {  
			if($this->verbose) { $this->verbose("Would send header to deliver image with modified time: {$gmdate} GMT, but its verbose mode."); exit; }
			header('Content-type: ' . $mime);  
			readfile($file);
		}
		exit;
	}

	/**
	* Sharpen image as http://php.net/manual/en/ref.image.php#56144
	* http://loriweb.pair.com/8udf-sharpen.html
	*
	* @param resource $image the image to apply this filter on.
	* @return resource $image as the processed image.
	*/
	public function sharpenImage($image) {
		$matrix = array(
			array(-1,-1,-1,),
			array(-1,16,-1,),
			array(-1,-1,-1,)
		);
		$divisor = 8;
		$offset = 0;
		imageconvolution($image, $matrix, $divisor, $offset);
		return $image;
	}
	
	/**
	* Create new image and keep transparency
	*
	* @param resource $image the image to apply this filter on.
	* @return resource $image as the processed image.
	*/
	function createImageKeepTransparency($width, $height) {
		$image = imagecreatetruecolor($width, $height);
		imagealphablending($image, false);
		imagesavealpha($image, true);  
		return $image;
	}
	
	
	public function validateIncArg() {
		//
		// Validate incoming arguments
		//
		is_dir(IMG_PATH) or $this->errorMessage('The image dir is not a valid directory.');
		is_writable(CACHE_PATH) or $this->errorMessage('The cache dir is not a writable directory.');
		isset($this->src) or $this->errorMessage('Must set src-attribute.');
		preg_match('#^[a-z0-9A-Z-_\.\/]+$#', $this->src) or $this->errorMessage('Filename contains invalid characters.');
		substr_compare(IMG_PATH, $this->pathToImage, 0, strlen(IMG_PATH)) == 0 or $this->errorMessage('Security constraint: Source image is not directly below the directory IMG_PATH.');
		is_null($this->saveAs) or in_array($this->saveAs, array('png', 'jpg', 'jpeg', 'gif')) or errorMessage('Not a valid extension to save image as');
		is_null($this->quality) or (is_numeric($this->quality) and $this->quality > 0 and $this->quality <= 100) or $this->errorMessage('Quality out of range');
		is_null($this->newWidth) or (is_numeric($this->newWidth) and $this->newWidth > 0 and $this->newWidth <= $this->maxWidth) or $this->errorMessage('Width out of range');
		is_null($this->newHeight) or (is_numeric($this->newHeight) and $this->newHeight > 0 and $this->newHeight <= $this->maxHeight) or $this->errorMessage('Height out of range');
		is_null($this->cropToFit) or ($this->cropToFit and $this->newWidth and $this->newHeight) or $this->errorMessage('Crop to fit needs both width and height to work');

	}
	
	/**
	* Start displaying log if verbose mode & create url to current image
	*/
	public function displayLog() {
		if($this->verbose) {
			$query = array();
			parse_str($_SERVER['QUERY_STRING'], $query);
			unset($query['verbose']);
			$url = '?' . http_build_query($query);


			$html =  <<<EOD
			<html lang='en'>
			<meta charset='UTF-8'/>
			<title>img.php verbose mode</title>
			<h1>Verbose mode</h1>
			<p><a href=$url><code>$url</code></a><br>
			<img src='{$url}' /></p>
EOD;
			echo $html;
		}	
	}
	

	/**
	* Get information on the image
	*/
	public function getInfo() {
		$imgInfo = list($width, $height, $type, $attr) = getimagesize($this->pathToImage);
		!empty($imgInfo) or $imageObj->errorMessage("The file doesn't seem to be an image.");
		$mime = $imgInfo['mime'];
		
		$this->width = $width;
		$this->height = $height;
		
		if($this->verbose) {
			//$imageObj->getInfo();
			$this->filesiz3 = filesize($this->pathToImage);
			$this->verbose("Image file: {$this->pathToImage}");
			$this->verbose("Image information: " . print_r($imgInfo, true));
			$this->verbose("Image width x height (type): {$width} x {$height} ({$type}).");
			$this->verbose("Image file size: {$this->filesiz3} bytes.");
			$this->verbose("Image mime type: {$mime}.");
		}
	}
	
	
	/**
	* Calculate new width and height for the image
	*/
	public function calculateNewWidthAndHeight() {
		$aspectRatio = $this->width / $this->height;

		if($this->cropToFit && $this->newWidth && $this->newHeight) {
			$targetRatio = $this->newWidth / $this->newHeight;
			$this->cropWidth   = $targetRatio > $aspectRatio ? $this->width : round($this->height * $targetRatio);
			$this->cropHeight  = $targetRatio > $aspectRatio ? round($this->width  / $targetRatio) : $this->height;
			if($this->verbose) { $this->verbose("Crop to fit into box of {$this->newWidth}x{$this->newHeight}. Cropping dimensions: {$cropWidth}x{$cropHeight}."); }
		}
		else if($this->newWidth && !$this->newHeight) {
			$this->newHeight = round($this->newWidth / $aspectRatio);
			if($this->verbose) { $this->verbose("New width is known {$this->newWidth}, height is calculated to {$this->newHeight}."); }
		}
		else if(!$this->newWidth && $this->newHeight) {
			$this->newWidth = round($this->newHeight * $aspectRatio);
			if($this->verbose) { $this->verbose("New height is known {$this->newHeight}, width is calculated to {$this->newWidth}."); }
		}
		else if($this->newWidth && $this->newHeight) {
			$ratioWidth  = $this->width  / $this->newWidth;
			$ratioHeight = $this->height / $this->newHeight;
			$ratio = ($ratioWidth > $ratioHeight) ? $ratioWidth : $ratioHeight;
			$this->newWidth  = round($this->width  / $ratio);
			$this->newHeight = round($this->height / $ratio);
			if($this->verbose) { $this->verbose("New width & height is requested, keeping aspect ratio results in {$this->newWidth}x{$this->newHeight}."); }
		}
		else {
			$this->newWidth = $this->width;
			$this->newHeight = $this->height;
			if($this->verbose) { $this->verbose("Keeping original width & heigth."); }
		}
	}
	
	
	/**
	* Creating a filename for the cache
	*/
	public function createFileName() {
		$parts          = pathinfo($this->pathToImage);
		$this->fileExtension  = $parts['extension'];
		$this->saveAs   = is_null($this->saveAs) ? $this->fileExtension : $this->saveAs;
		$quality_       = is_null($this->quality) ? null : "_q{$this->quality}";
		$cropToFit_     = is_null($this->cropToFit) ? null : "_cf";
		$sharpen_       = is_null($this->sharpen) ? null : "_s";
		$dirName        = preg_replace('/\//', '-', dirname($this->src));
		$this->cacheFileName = CACHE_PATH . "-{$dirName}-{$parts['filename']}_{$this->newWidth}_{$this->newHeight}{$quality_}{$cropToFit_}{$sharpen_}.{$this->saveAs}";
		$this->cacheFileName = preg_replace('/^a-zA-Z0-9\.-_/', '', $this->cacheFileName);

		if($this->verbose) { $this->verbose("Cache file is: {$this->cacheFileName}"); }
	}
	
	/**
	* Validates the cache name and if exists open the original 
	*/
	public function validateFile() {	
		//
		// Is there already a valid image in the cache directory, then use it and exit
		//
		$imageModifiedTime = filemtime($this->pathToImage);
		$cacheModifiedTime = is_file($this->cacheFileName) ? filemtime($this->cacheFileName) : null;

		// If cached image is valid, output it.
		if(!$this->ignoreCache && is_file($this->cacheFileName) && $imageModifiedTime < $cacheModifiedTime) {
		  if($this->verbose) { $this->verbose("Cache file is valid, output it."); }
		  $this->outputImage($this->cacheFileName);
		}

		if($this->verbose) { $this->verbose("Cache is not valid, process image and create a cached version of it."); }
	}
	
	/**
	* Open up the original image from file
	*/
	public function openFile() {
		if($this->verbose) { $this->verbose("File extension is: {$this->fileExtension}"); }

		switch($this->fileExtension) {  
		  case 'jpg':
		  case 'jpeg': 
			$this->image = imagecreatefromjpeg($this->pathToImage);
			if($this->verbose) { $this->verbose("Opened the image as a JPEG image."); }
			break;  
		  
		  case 'png':  
			$this->image = imagecreatefrompng($this->pathToImage); 
			if($this->verbose) { $this->verbose("Opened the image as a PNG image."); }
			break;
			
		  case 'gif':
		    $this->image = imagecreatefromgif($this->pathToImage);
			if($this->verbose) { $this->verbose("Opened the image as a GIF image."); }
			break;

		  default: errorPage('No support for this file extension.');
		}
	}
	
	/**
	* Resizes the image if necessery
	*/
	public function resizeImage() {
		if($this->cropToFit) {
			if($this->verbose) { $this->verbose("Resizing, crop to fit."); }
			$cropX = round(($this->width - $this->cropWidth) / 2);  
			$cropY = round(($this->height - $this->cropHeight) / 2);    
			$imageResized = $this->createImageKeepTransparency($this->newWidth, $this->newHeight);
			imagecopyresampled($imageResized, $this->image, 0, 0, $cropX, $cropY, $this->newWidth, $this->newHeight, $this->cropWidth, $this->cropHeight);
			$this->image = $imageResized;
			$this->width = $this->newWidth;
			$this->height = $this->newHeight;
		}
		else if(!($this->newWidth == $this->width && $this->newHeight == $this->height)) {
			if($this->verbose) { $this->verbose("Resizing, new height and/or width."); }
			$imageResized = $this->createImageKeepTransparency($this->newWidth, $this->newHeight);
			imagecopyresampled($imageResized, $this->image, 0, 0, 0, 0, $this->newWidth, $this->newHeight, $this->width, $this->height);
			$this->image  = $imageResized;
			$this->width  = $this->newWidth;
			$this->height = $this->newHeight;
		}
	}
	
	/**
	* Apply filters and postprocessing of image
	*/
	public function applySharpen() {
		if($this->sharpen) {
			$this->image = $this->sharpenImage($this->image);
		}
	}
	
	
	/**
	* Save the image
	*/
	public function saveImage() {
		switch($this->saveAs) {
			case 'jpeg':
			case 'jpg':
				if($this->verbose) { $this->verbose("Saving image as JPEG to cache using quality = {$this->quality}."); }
				imagejpeg($this->image, $this->cacheFileName, $this->quality);
			break;  

			case 'png':  
				if($this->verbose) { $this->verbose("Saving image as PNG to cache."); }
				imagealphablending($image, false);
				imagesavealpha($image, true);
				imagepng($this->image, $this->cacheFileName);  
			break;
			
			case 'gif';
				if($this->verbose) { $this->verbose("Saving image as GIF to cache."); }
				imagegif($this->image, $this->cacheFileName);  
				break;
				
			default:
				$this->errorMessage('No support to save as this file extension.');
			break;
		}
			
			if($this->verbose) { 
				clearstatcache();
				$cacheFilesize = filesize($this->cacheFileName);
				$this->verbose("File size of cached file: {$cacheFilesize} bytes."); 
				$this->verbose("Cache file has a file size of " . round($cacheFilesize/$this->filesiz3*100) . "% of the original size.");
			}
	}
	
	
	/**
	* Output the resulting image and run all the other functions
	*/
	public function outputResult() {
		
		$this->validateIncArg();
		
		$this->displayLog();

		$this->getInfo();

		$this->calculateNewWidthAndHeight();

		$this->createFileName();

		$this->validateFile();

		$this->openFile();

		$this->resizeImage();

		$this->applySharpen();

		$this->saveImage();

		$this->outputImage($this->cacheFileName);
	}
	
	
}