<?php

namespace ContestBundle\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use ContestBundle\Entity\Filetype;
use ContestBundle\Entity\File;
use ContestBundle\Exception\AdminAccessRequiredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;

class AdminController extends BaseAdminController
{
    /**
     * @todo przenieść do serwisu
     */
    public function persistContestEntity($entity)
    {
        $entity
            ->setCreatedBy($this->getUser());
        
        if (null !== $entity->getThumbnail()) {
            $thumbnail = $entity->getThumbnail();
            $file = new File();
            $file
                ->setMimeType($thumbnail->getMimeType())
                ->setExtension($thumbnail->guessExtension())
                ->setFileSize($thumbnail->getClientSize())
                ->setTempName($this->generateUniquename())
                ->setOriginalName($thumbnail->getClientOriginalName())
                ->setPost(null);
            ;

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($file);
            $entityManager->flush($file);
        }

        parent::persistEntity($entity);
    }

    private function generateUniqueName()
    {
        return uniqid();
    }
}
