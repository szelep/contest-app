<?php

namespace ContestBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use ContestBundle\Entity\Contest;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ContestBundle\Entity\File;
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
            $this->persistFile($file->getFile(), $post);
        }
    }
    public function persistFile(UploadedFile $uploadedFile, $post = null)
    {
        $file = new File();
        $fileName = $this->generateUniquename();
        $file
            ->setMimeType($uploadedFile->getMimeType())
            ->setExtension($uploadedFile->guessExtension())
            ->setFileSize($uploadedFile->getClientSize())
            ->setTempName($fileName)
            ->setOriginalName($uploadedFile->getClientOriginalName());
        
        if (null !== $post) {
            $file->setPost($post);
            $templatesDirectory = 'post';
        } elseif (null === $post) {
            $file->setPost(null);
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
            ->flush($file);
        

        return $file;
    }

    private function generateUniqueName()
    {
        return uniqid();
    }
}