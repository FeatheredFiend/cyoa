<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class FileUploader
{
    private $targetDirectoryEnemy;
    private $targetDirectoryParagraph;
    private $slugger;

    public function __construct($targetDirectoryEnemy, $targetDirectoryParagraph, SluggerInterface $slugger)
    {
        $this->targetDirectoryEnemy = $targetDirectoryEnemy;
        $this->targetDirectoryParagraph = $targetDirectoryParagraph;
        $this->slugger = $slugger;
    }

    public function uploadEnemy(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryEnemy(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function uploadParagraph(UploadedFile $file)
    {
        $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $safeFilename = $this->slugger->slug($originalFilename);
        $fileName = $safeFilename.'-'.uniqid().'.'.$file->guessExtension();

        try {
            $file->move($this->getTargetDirectoryParagraph(), $fileName);
        } catch (FileException $e) {
            // ... handle exception if something happens during file upload
        }

        return $fileName;
    }

    public function getTargetDirectoryEnemy()
    {
        return $this->targetDirectoryEnemy;
    }
    
    public function getTargetDirectoryParagraph()
    {
        return $this->targetDirectoryParagraph;
    }
}