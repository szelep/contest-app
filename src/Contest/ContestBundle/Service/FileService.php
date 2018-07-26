<?php

namespace ContestBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use ContestBundle\Entity\Contest;
use ContestBundle\Service\AccessService;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Filesystem\Exception\IOException;
use ContestBundle\Entity\File;
use ContestBundle\Entity\Filetype;
use ContestBundle\Entity\Media;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use ContestBundle\Entity\Post;
use Doctrine\Common\Collections\ArrayCollection;
use \Eventviva\ImageResize;

class FileService
{
    private $thumbnailGenerated = false;

    private $mediaTarget;

    public function __construct(EntityManagerInterface $entityManager, $uploadDir, AccessService $accessService)
    {
        $this->entityManager = $entityManager;
        $this->uploadDir = $uploadDir;
        $this->accessService = $accessService;
    }

    public function manageMultipleUpload(ArrayCollection $files, $post = null)
    {
        $mediaFile = null;
        foreach ($files as $file)
        {
            $persistedFile = $this->persistFile($file->getFile(), $post);

            if (false === $persistedFile) {
                return false;
            }

            if (null !== $post) {
                $mediaFile = $this->persistFileToMedia($post, $persistedFile);
            }
        }

        if ($mediaFile instanceof Media) {
            return $mediaFile;
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
            $this->mediaTarget = Media::MEDIA_POST_TARGET;
            $allowedType = $this
                    ->accessService
                    ->checkIsFileAllowedInContest($uploadedFile->getMimeType(), $post);
            if (false === $allowedType) {
                return false;
            }
        } elseif (null === $post) {
            $templatesDirectory = 'template';
            $this->mediaTarget = Media::MEDIA_TEMPLATE_TARGET;
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
       /* $this
            ->entityManager
            ->flush();*/

        if ($this->mediaTarget === Media::MEDIA_POST_TARGET) {

        }

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

    private function getPathToPostFile(File $file)
    {
        $fileName = $file->getTempName();
        $fileExtension = $file->getExtension();

        $fullPath = array(
            $this->getPostUploadDir(),
            $fileName . '.' . $fileExtension
        );

        $fullPath = implode(\DIRECTORY_SEPARATOR, $fullPath);

        return $fullPath;
    }

    private function getPostUploadDir()
    {
        return $this->uploadDir . \DIRECTORY_SEPARATOR . 'post';
    }

    private function persistFileToMedia(Post $post, File $file)
    {
        $media = new Media();
        $media
            ->setPost($post)
            ->setFile($file);




            $image = new ImageResize($this->getPathToPostFile($file));



            //wielkość miniatury ma być pobierana z tempalatki



            $newFileName = $file->getTempName() . '_thmb';
            $image->resize(200, 100, ImageResize::CROPCENTER);
            $image->save($this->getPostUploadDir() .  \DIRECTORY_SEPARATOR . $newFileName . '.' . $file->getExtension());

            $baseFile = clone $file;
            $file = new File();
            $file
            ->setFiletype($baseFile->getFiletype())
            ->setExtension($baseFile->getExtension())
            ->setFileSize(0)
            ->setTempName($newFileName)
            ->setOriginalName($baseFile->getTempName());

            $this->entityManager->persist($file);




        $media->setThumbnail($file);
        $this->entityManager->persist($media);

        if (false === $this->thumbnailGenerated)
        {
            return $media;
            $this->thumbnailGenerated = true;
        }
    }

    private function generateUniqueName()
    {
        return uniqid();
    }
}