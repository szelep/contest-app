<?php

namespace ContestBundle\Controller;

use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AdminController as BaseAdminController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\Choice;
use ContestBundle\Entity\Contest;
use ContestBundle\Entity\Filetype;
use ContestBundle\Entity\File;
use ContestBundle\Entity\Template;
use ContestBundle\Form\TemplateType;
use ContestBundle\Exception\AdminAccessRequiredException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;

class AdminController extends BaseAdminController
{
    public function szablonAction()
    {
        $contestId = $this
                        ->request
                        ->query
                        ->get('id');

        $contest = $this
                        ->em
                        ->getRepository(Contest::class)
                        ->find($contestId);

        if (null === $contest) {
            throw new \Exception("Nie znaleziono konkursu.");
        }

        $contestTemplate = $this
                                ->em
                                ->getRepository(Template::class)
                                ->findOneBy(
                                    array(
                                        'contest' => $contestId
                                    )
                                );
        $clonedContestTemplate = clone $contestTemplate;
        if (null !== $contestTemplate) {
            $contestTemplate
                        ->setThumbnail(null)
                        ->setHeaderImage(null)
                        ->setFooterImage(null)
                        ->setBackgroundImage(null)
            ;
        }

        $elements = array(
            'thumbnail'         => $clonedContestTemplate->getThumbnail(),
            'header_image'      => $clonedContestTemplate->getHeaderImage(),
            'footer_image'      => $clonedContestTemplate->getFooterImage(),
            'background_image'  => $clonedContestTemplate->getBackgroundImage()
        );

        $form = $this->createForm(TemplateType::class, $contestTemplate);

        $form->handleRequest($this->request);

        if ('POST' === $this->request->getMethod()) {
            $entityManager = $this->getDoctrine()->getManager();

            $formData = $form->getData();

            $formData = $this->manageFiles($formData, $clonedContestTemplate);

            $formData->setContest($contest);

            $entityManager->persist($formData);
            $entityManager->flush();
        }

       return $this->render("@Contest/EasyAdmin/setTemplate.html.twig", array(
           'form'        => $form->createView(),
           'elements'    => $elements
           )
        );
    }

    private function manageFiles($formData, $clonedContestTemplate)
    {
        if (null !== $formData->getThumbnail()) {
            $thumbnail = $this->persistFile($formData->getThumbnail());
            $formData->setThumbnail($thumbnail);
        } else {
            $formData->setThumbnail($clonedContestTemplate->getThumbnail());
        }

        if (null !== $formData->getHeaderImage()) {
            $headerImage = $this->persistFile($formData->getHeaderImage());
            $formData->setHeaderImage($headerImage);
        } else {
            $formData->setHeaderImage($clonedContestTemplate->getHeaderImage());
        }

        if (null !== $formData->getFooterImage()) {
            $footerImage = $this->persistFile($formData->getFooterImage());
            $formData->setFooterImage($footerImage);
        } else {
            $formData->setFooterImage($clonedContestTemplate->getFooterImage());
        }

        if (null !== $formData->getBackgroundImage()) {
            $backgroundImage = $this->persistFile($formData->getBackgroundImage());
            $formData->setBackgroundImage($backgroundImage);
        } else {
            $formData->setBackgroundImage($clonedContestTemplate->getBackgroundImage());
        }

        return $formData;
    }

    public function persistContestEntity($entity)
    {
        $entity
            ->setCreatedBy($this->getUser());
        
        parent::persistEntity($entity);
    }

    private function persistFile(UploadedFile $uploadedFile)
    {
        $file = new File();
        $fileName = $this->generateUniquename();
        $file
            ->setMimeType($uploadedFile->getMimeType())
            ->setExtension($uploadedFile->guessExtension())
            ->setFileSize($uploadedFile->getClientSize())
            ->setTempName($fileName)
            ->setOriginalName($uploadedFile->getClientOriginalName())
            ->setPost(null);

        $filesDirectory = $this->container->getParameter('upload_dir');
        $templatesDirectory = 'template';
        $fullPath = array(
            $filesDirectory,
            $templatesDirectory
        );
        
        $uploadedFile->move(implode(\DIRECTORY_SEPARATOR, $fullPath), $fileName . '.' . $uploadedFile->guessExtension());

        $this->em->persist($file);
        $this->em->flush($file);
        

        return $file;
    }

    private function generateUniqueName()
    {
        return uniqid();
    }
}
