<?php
/**
 * Automaticky resizer a pridavac vodoznaku.
 * by Jan Horak
 * Datum: 1.11.2009
 *
 * Jak pracuje:
 * 1) vezme vsechny obrazky (png) zacinajici na znak _ a oznaci je jako vodoznaky
 * 2) vezme vsechny obrazky (png), ktere nejsou vodoznaky, prida vsechny vodoznaky a zmensi je na veliskosti 16x16, 24x24, ..
 * 3) vytvorene obrazky ulozi do adresaru podle velikosti
 *
 * Priklad: v tomto adresari se nachazi soubory: _edit.png, _delete.png, masinka.png
 * 1) _edit.png a _delete.png jsou oznaceny za vodoznaky
 * 2) ze souboru masinka.png jsou vytvoreny soubory: masinka_edit.png a masinka_delete.png
 * 3) soubory jsou zmenseny a ulozeny do: 
 *    16/masinka_edit.png
 *    16/masinka_delete.png
 *    24/masinka_edit.png
 *    24/masinka_delete.png
 *    ... atd
 */


header("Content-Type: text/plain");


// nacitani vodoznaku a ulozeni nactenych obrazku
$ext = array("__none__" => false);
foreach (scandir("./") as $f) {
	if (substr($f, strrpos($f, '.')) == ".png" && $f[0] == '_') {
		$name = substr($f, 0, strrpos($f, '.'));
		$ext[$name] = imagecreatefrompng($f);
		//imagesavealpha($ext[$name], true);
		echo "nacitam rozsireni $name\n";
	}
}

// vytvareni adresaru
$sizes = array(16, 24, 32, 48, 64, 128);
foreach ($sizes as $x) {
	if (!is_dir($x)) {
		mkdir($x);
	}
}

// prochazeni obrazku, ktere nejsou vodoznaky a generovani novych obrazku s vodoznaky v ruznych velikosti
foreach (scandir("./") as $f) {
	$fe = substr($f, strrpos($f, '.'));
	$fb = substr($f, 0, strrpos($f, '.'));
	if ($fe == ".png" && !array_key_exists(substr($fb, strrpos($fb, '_')), $ext)) {
		echo "zpracovavam soubor $f\n";
		
		// pro kazdy vodoznak
		foreach ($ext as $k => $e) {
			if ($k == "__none__") {
				$k = "";
			}
			$base = imagecreatefrompng($f);
			imagesavealpha($base, true);
			echo "  '- zpracovavam soubor $f a rozsireni $k\n";
			if ($e) {
				imagecopy($base, $e, 0, 0, 0, 0, imagesx($e), imagesy($e));
			}
			
			// pro kazdou velkost
			foreach ($sizes as $x) {
				$n = imagecreatetruecolor($x, $x);
				imagealphablending($n, false);
				// nutne pro nastaveni pruhlednosti
				$color = imagecolortransparent($n, imagecolorallocatealpha($n, 255, 255, 255, 127));
				imagefill($n, 0, 0, $color);
				imagesavealpha($n, true);				
				imagealphablending($n, true);
				imagecopyresampled($n, $base, 0, 0, 0, 0, $x, $x, 128, 128);
				echo "  | '- zpracovavam soubor $f a rozsireni $k ve velikosti {$x}x{$x}px\n";
				imagepng($n, $x . '/' . substr($f, 0, strrpos($f, '.')) . $k . ".png");
				imagedestroy($n);
			}
			imagedestroy($base);
		}
	}
}

// uvolneni vodoznaku z pameti
foreach ($ext as $k => $e) {
	if ($e) {
		imagedestroy($e);
	}
}

?>
