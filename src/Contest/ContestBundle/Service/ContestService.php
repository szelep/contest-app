<?php

namespace ContestBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use ContestBundle\Entity\Contest;
use ContestBundle\Entity\Post;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use ContestBundle\Service\FileService;

class ContestService
{
    public function __construct(EntityManagerInterface $entityManager, FileService $fileService)
    {
        $this->entityManager = $entityManager;
        $this->fileService = $fileService;
    }

    public function findContest($id)
    {
        $contest = $this
                ->entityManager
                ->getRepository(Contest::class)
                ->findOneById($id);

        if (null !== $contest) {
            return $contest;
        }
        
        throw new NotFoundHttpException("Nie odnaleziono konkursu.");
    }

    public function findPost($id)
    {
        $post = $this
                ->entityManager
                ->getRepository(Post::class)
                ->findOneById($id);

        if (null !== $post) {
            return $post;
        }
        
        throw new NotFoundHttpException("Nie odnaleziono wpisu.");
    }
}