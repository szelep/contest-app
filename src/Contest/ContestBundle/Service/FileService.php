<?php

namespace ContestBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use ContestBundle\Entity\Contest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Filesystem\Exception\IOException;
use ContestBundle\Entity\File;
use ContestBundle\Entity\Filetype;
use ContestBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ContestBundle\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;

class FileService
{
    public function __construct(EntityManagerInterface $entityManager, $uploadDir)
    {
        $this->entityManager = $entityManager;
        $this->uploadDir = $uploadDir;
    }

    public function manageMultipleUpload(ArrayCollection $files, $post = null)
    {
        foreach ($files as $file)
        {
           $persistedFile = $this->persistFile($file->getFile(), $post);

            if (null !== $post) {
                $allowedFileTypes = $this->findAllowedFileTypes($post);
                if (in_array($persistedFile->getFiletype()->getValue(), $allowedFileTypes)) {
                    $this->persistFileToMedia($post, $persistedFile);
                } else {
                    throw new IOException("niepoprawne rozszerzenie. zamien to na flash a nie exception! jakiś response");
                }
            }
        }
    }
    public function persistFile(UploadedFile $uploadedFile, $post)
    {
        $file = new File();
        $fileName = $this->generateUniquename();
        $filetype = $this->findFiletype($uploadedFile->getMimeType());
        $file
            ->setFiletype($filetype)
            ->setExtension($uploadedFile->guessExtension())
            ->setFileSize($uploadedFile->getClientSize())
            ->setTempName($fileName)
            ->setOriginalName($uploadedFile->getClientOriginalName());
        
        if (null !== $post) {
            $templatesDirectory = 'post';
        } elseif (null === $post) {
            $templatesDirectory = 'template';
        } else {
            throw new \Exception('Wystąpił błąd.');
        }

        $filesDirectory = $this->uploadDir;
        $fullPath = array(
            $filesDirectory,
            $templatesDirectory
        );
        
        $uploadedFile->move(implode(\DIRECTORY_SEPARATOR, $fullPath), $fileName . '.' . $uploadedFile->guessExtension());

        $this
            ->entityManager
            ->persist($file);
        $this
            ->entityManager
            ->flush();

        return $file;
    }

    private function findFiletype($mimeType)
    {
        $filetype = $this
                ->entityManager
                ->getRepository(Filetype::class)
                ->findOneByValue($mimeType);
        if (null === $filetype) {
            throw new IOException('Wystąpił nieoczekiwany błąd - brak wpisu tego typu pliku.');
        }
        
        return $filetype;
    }

    private function findAllowedFileTypes(Post $post)
    {
        $allowedFiles = $post->getContest()->getAllowedFiles();
        $allowedTypesArray = array();

        foreach ($allowedFiles as $allowedFile) {
            $allowedTypesArray[] = $allowedFile->getValue();
        }

        return $allowedTypesArray;
    }

    private function persistFileToMedia(Post $post, File $file)
    {
        $media = new Media();
        $media
            ->setPost($post)
            ->setFile($file)
            ->setThumbnail('e');

        $this->entityManager->persist($media);
        $this->entityManager->flush();
    }

    private function generateUniqueName()
    {
        return uniqid();
    }
}