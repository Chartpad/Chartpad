<?php
	error_reporting(-1);
	if(isset($_POST['filename']) || isset($_POST['filetype']) || isset($_POST['imagedata'])) {
		$filename = $_POST['filename'];
		$imagedata = $_POST['imagedata'];
		$imagedata = str_replace(" ", "+", $imagedata);
		$imagedata = base64_decode($imagedata);
		$image = imagecreatefromstring($imagedata);

		switch ($_POST['filetype']) {
			case 'jpg':
				header("Content-type: image/jpeg");
				header("Content-Disposition: attachment; filename='" . $filename . "'");
				imagejpeg($image);
				break;

			case 'png':
				header("Content-type: image/png");
				header("Content-Disposition: attachment; filename='" . $filename . "'");
				imagepng($image);
				break;

			case 'gif':
				header("Content-type: image/gif");
				header("Content-Disposition: attachment; filename='" . $filename . "'");
				imagegif($image);
				break;
			
			default:
				break;
		}
	}
?>