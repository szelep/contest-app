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
   /* public function createContestNewForm($entity, $view)
    {
        $formBuilder = parent::createNewForm($entity, $view);

        $entity
            ->setStartDate(new \DateTime())
            ->setFinishDate(new \DateTime());

        $formBuilder->add('allowedFiles', EntityType::class, array(
            'class' => Filetype::class,
            'multiple' => true,
            'label' => 'Dozwolone typy plików',
            'expanded' => true,
            'choices_as_values' => true,
            'choice_label' => 'value',
            'attr' => array('class' => 'group')
        ));

        return $formBuilder;
    }*/

    public function persistContestEntity($entity)
    {
        $entity
            ->setCreatedBy($this->getUser());
        
        if (null !== $entity->getThumbnail()) {
            $thumbnail = $entity->getThumbnail();
           // $thumbnail->getMimeType();
            $file = new File();
          /*  $file
                ->setMimeType($thumbnail->getMimeType())
                ->setExtension($thumbnail->guessExtension())
            ;*/

            print_r($thumbnail);
            print_r($entity->getThumbnail()->getClientSize());
        }

        die();

       // parent::persistEntity($entity);
    }
}
