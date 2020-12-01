<?php
/*
Uploadify v2.1.4
Release Date: November 8, 2010

Copyright (c) 2010 Ronnie Garcia, Travis Nickels

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/
function returnImage($width, $src, $save, $newFileName)
{


    $szerokosc_definiowana = $width;
    $obrazek = imagecreatefromjpeg($src . $newFileName);
    $szerokosc = imagesx($obrazek);

    //if ($szerokosc>=$width)
    //{
    $wysokosc = imagesy($obrazek);
    $szerokosc_koncowa = $szerokosc_definiowana;
    $proporcja1 = $szerokosc / $szerokosc_definiowana;
    $wysokosc_koncowa = $wysokosc / $proporcja1;
    $imgOut1 = imagecreatetruecolor($szerokosc_koncowa, $wysokosc_koncowa);
    imagerectangle($imgOut1, 0, 0, $szerokosc_koncowa, $wysokosc_koncowa, imagecolorallocate($imgOut1, 0, 0, 0));
    $dx1 = 0;
    $dy1 = 0;
    $dw1 = $szerokosc_koncowa;
    $dh1 = $wysokosc_koncowa;
    if ($szerokosc_koncowa * $wysokosc != $wysokosc_koncowa * $szerokosc) {
        if ($szerokosc > $wysokosc) {
            $dh1 = ($dw1 * $wysokosc) / $szerokosc;
            $dw1 = $szerokosc_koncowa;
            $dy1 = ($wysokosc_koncowa - $dh1) / 2;
        } else {
            $dh1 = $wysokosc_koncowa;
            $dw1 = ($dh1 * $szerokosc) / $wysokosc;
            $dx1 = ($szerokosc_koncowa - $dw1) / 2;
        }
    }
    imagecopyresampled($imgOut1, $obrazek, $dx1, $dy1, 0, 0, $dw1, $dh1, $szerokosc, $wysokosc);
    imagepng($imgOut1, $save . $newFileName);

    //}
    imagedestroy($imgOut1);
    imagedestroy($obrazek);
    return 1;

}

if (!empty($_FILES)) {
    $tempFile = $_FILES['Filedata']['tmp_name'];
    $targetPath = $_SERVER['DOCUMENT_ROOT'] . $_REQUEST['folder'] . '/';
    $targetFile = str_replace('//', '/', $targetPath);
    $targetFileM = str_replace('//', '/', $targetPath) . 'thumbs/';

    $explode = explode('/', $targetFile);

    $d = $explode[5]; // na serwer: 5

    $pn = str_replace(array('/', '�', '�', '�', ' '), array('-', 's', 'l', 'o', '-'), $pn[0]);
    $newFileName = "avatar.jpg";


    // $fileTypes  = str_replace('*.','',$_REQUEST['fileext']);
    // $fileTypes  = str_replace(';','|',$fileTypes);
    // $typesArray = split('\|',$fileTypes);
    // $fileParts  = pathinfo($_FILES['Filedata']['name']);

    //if (in_array($fileParts['extension'],$typesArray)) {
    // Uncomment the following line if you want to make the directory if it doesn't exist

    //mkdir(str_replace('//','/',$targetPath), 0755, true);


    move_uploaded_file($tempFile, $targetFile . $newFileName);


    returnImage(60, $targetFile, $targetFileM, $newFileName);
    returnImage(480, $targetFile, $targetFile, $newFileName);


    echo str_replace($_SERVER['DOCUMENT_ROOT'], '', $targetFile);


    //} else {
    //	echo 'Invalid file type.';
    //}
}
?>