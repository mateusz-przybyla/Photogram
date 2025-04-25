<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\String\Slugger\SluggerInterface;

class ImageUploader
{
  private SluggerInterface $slugger;

  public function __construct(SluggerInterface $slugger)
  {
    $this->slugger = $slugger;
  }

  public function upload(UploadedFile $file, string $targetDirectory): string
  {
    $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
    $safeFilename = $this->slugger->slug($originalFilename);
    $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

    $file->move($targetDirectory, $newFilename);

    return $newFilename;
  }

  public function remove(string $filename, string $targetDirectory): void
  {
    $filePath = $targetDirectory . '/' . $filename;

    if (file_exists($filePath)) {
      unlink($filePath);
    }
  }

  public function getOriginalImageName(string $filename): string
  {
    $dotPosition = strrpos($filename, '.');
    $extension = substr($filename, $dotPosition);
    $base = substr($filename, 0, $dotPosition);

    $originalName = preg_replace('/-\w+$/', '', $base);
    return $originalName . $extension;
  }
}
