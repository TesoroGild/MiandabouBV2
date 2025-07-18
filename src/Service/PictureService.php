<?php

namespace App\Service;  

use Symfony\Component\HttpFoundation\File\UploadedFile;

class PictureService
{
    public function upload(UploadedFile $file, $path)
    {
        $fileName = md5(uniqid()).'.'.$file->guessExtension();
        $file->move($path, $fileName);
        return $fileName;
    }
}