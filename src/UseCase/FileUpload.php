<?php


namespace App\UseCase;

use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUpload
{
    private $fsRoot;

    public function __construct($fsRoot)
    {
        $this->fsRoot = $fsRoot;
    }

    public function uploadFile(UploadedFile $file)
    {
        $token = bin2hex(random_bytes(8));
        $webPath = array_merge(
            ['/uploads'],
            str_split($token, 2),
            [$file->getClientOriginalName()]
        );
        $webPath = implode('/', $webPath);

        $fsPath = $this->fsRoot . $webPath;
        mkdir(dirname($fsPath), 0777, true);
        $file->move(dirname($fsPath), basename($fsPath));

        return $webPath;
    }
}
