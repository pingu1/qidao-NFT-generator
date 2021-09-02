<?php

// main call to the function to generate a big image with 20 x 15 pandas, each
// being a 48x48 pixel are
generateBigImage(48, 20, 15);

// This function generates a pixel art of size $size x $size, and save it to
// a destination folder
function generate(int $size = null): string
{
    // png elements are 24x24
	$x = $y = 24;
    if ($size === null) {
        $size = 24;
    }

	header('Content-type: image/png');
	$targetDir = '/generatedPixelart/';
	$targetPath = getcwd();

	// Face (50% giant panda, 35% red panda, 15% Qinling panda)
	$pandaType = mt_rand(1, 100);
	if ($pandaType >= 50) {
		$facePng = sprintf('%s/face/face1.png', $targetPath);
	} else if ($pandaType < 15) {
		$facePng = sprintf('%s/face/face2.png', $targetPath);
	} else {
		$facePng = sprintf('%s/face/face3.png', $targetPath);
	}

	// Eyes (only 5% chances to get sunglasses
	$glassesChances = 5;
	if (mt_rand(1, 100) <= $glassesChances) {
		$eyesPng = sprintf('%s/eye/eyes5.png', $targetPath);
	} else {
		$eyesPng = sprintf('%s/eye/eyes%s.png', $targetPath, mt_rand(1,4));
	}

	// Ear rings (only 25% chances getting ear rings)
	$earringsChances = 25;
	if (mt_rand(1, 100) <= $earringsChances) {
		$earsPng = sprintf('%s/ear/ear%s.png', $targetPath, mt_rand(2,4));
	} else {
		$earsPng = sprintf('%s/ear/ear1.png', $targetPath);
	}

	// mouth
	$mouthPng = sprintf('%s/mouth/mouth%s.png', $targetPath, mt_rand(1,7));

	// nose
	$nosePng = sprintf('%s/nose/nose%s.png', $targetPath, mt_rand(1,4));

	// Base image with colored background
	$outputImage = imagecreatetruecolor($size, $size);
	$bgColor = imagecolorallocate($outputImage, mt_rand(1,255), mt_rand(1,255), mt_rand(1,255));
	imagefill($outputImage, 0, 0, $bgColor);

	// importing pixelated pngs
	$face = imagecreatefrompng($facePng);
	$eyes = imagecreatefrompng($eyesPng);
	$ears = imagecreatefrompng($earsPng);
	$mouth = imagecreatefrompng($mouthPng);
	$nose = imagecreatefrompng($nosePng);

	// adding elements and resize if required
	imagecopyresized($outputImage, $face, 0, 0, 0, 0, $size, $size, $x, $y);
	imagecopyresized($outputImage, $eyes, 0, 0, 0, 0, $size, $size, $x, $y);
	imagecopyresized($outputImage, $ears, 0, 0, 0, 0, $size, $size, $x, $y);
	imagecopyresized($outputImage, $mouth, 0, 0, 0, 0, $size, $size, $x, $y);
	imagecopyresized($outputImage, $nose, 0, 0, 0, 0, $size, $size, $x, $y);

	// save final image
	$fileName = $targetPath . $targetDir . round(microtime(true)) . '.png';
	imagepng($outputImage, $fileName);
	imagedestroy($outputImage);

	return $fileName;
}

// This function is generating a big image composed of $n x $m smaller images
// and stores it in the destination directory
function generateBigImage($size = null, $n = 10, $m = 10)
{
    $x = $y = 24;
    if ($size !== null) {
        $x = $y = $size;
    }

    header('Content-type: image/png');
    $targetDir = '/generatedPixelart/';
    $targetPath = getcwd();

    // Base image with colored background
    $outputImage = imagecreatetruecolor($x * $n, $y * $m);
    $bgColor = imagecolorallocate($outputImage, 255, 255, 255);
    imagefill($outputImage, 0, 0, $bgColor);

    for ($i = 0; $i < $n; $i++) {
        for ($j = 0; $j < $m; $j++) {
            $pandaPng = generate($size);
            $panda = imagecreatefrompng($pandaPng);
            imagecopyresized($outputImage, $panda, $i * $x, $j * $y, 0, 0, $x, $y, $x, $y);
            unlink($pandaPng);
        }
    }

    $fileName = $targetPath . $targetDir . 'family_' . round(microtime(true)) . '.png';
    imagepng($outputImage, $fileName);
    imagedestroy($outputImage);
}
