<?php

namespace ContestBundle\Service;

use Doctrine\ORM\EntityManagerInterface;
use ContestBundle\Entity\Contest;
use ContestBundle\Entity\Post;
use ContestBundle\Entity\Vote;
use UserBundle\Entity\User;
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

    public function manageVote(User $user, Post $post)
    {
        if (false === $this->checkContestAvailable($post->getContest())) {
            throw new \Exception('konkurs przedawniony');
            /**
             * @todo wszelkie formularze powinny być blokowane w trakcie renderowania twig
             */
        }

        $vote = $this
                ->entityManager
                ->getRepository(Vote::class)
                ->findOneBy(array(
                    'user' => $user,
                    'post' => $post
                ));

        if (null === $vote) {
            $vote = new Vote();
            $vote
                ->setUser($user)
                ->setPost($post)
                ->setCreatedAt(new \DateTime()); //ZMIEŃ NA TIMESTAMPABLE!!*/

            $this->entityManager->persist($vote);
            $removed = false;
        } elseif (null !== $vote) {
            $this->entityManager->remove($vote);
            $removed = true;
        }
        $this->entityManager->flush();

        return $removed;
    }

    public function checkContestAvailable(Contest $contest)
    {
        $finishDate = $contest->getFinishDate();

        if ($finishDate < new \DateTime()) {
            return false;
        } elseif (false === $contest->getIsPublished()) {
            return false;
        }

        return true;
    }
}