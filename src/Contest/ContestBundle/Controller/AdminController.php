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
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Filesystem\Filesystem;
use ContestBundle\Service\FileService;

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
        if (null === $contestTemplate) {
            $contestTemplate = new Template();
        }

        $clonedContestTemplate = clone $contestTemplate;
        $elements = $this->getElementsToRender($clonedContestTemplate);
        $thumbnailWidth = (string) $contestTemplate->getPostThumbnailWidth();
        $info = array(
                'k_width' => (empty($thumbnailWidth)? '200' : $thumbnailWidth),
            );
 
        
        if (null !== $contestTemplate) {
            $contestTemplate
                        ->setThumbnail(null)
                        ->setHeaderImage(null)
                        ->setFooterImage(null)
                        ->setBackgroundImage(null)
            ;
        }


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
           'elements'    => $elements,
           'info'        => $info
           )
        );
    }

    private function getElementsToRender(Template $contestTemplate)
    {
        $elements = array();

        if (null !== $contestTemplate->getThumbnail()) {
            $elements['thumbnail'] = $this->joinFileName($contestTemplate->getThumbnail());
        }

        if (null !== $contestTemplate->getHeaderImage()) {
            $elements['header_image'] = $this->joinFileName($contestTemplate->getHeaderImage());
        }

        if (null !== $contestTemplate->getFooterImage()) {
            $elements['footer_image'] = $this->joinFileName($contestTemplate->getFooterImage());
        }

        if (null !== $contestTemplate->getBackgroundImage()) {
            $elements['background_image'] = $this->joinFileName($contestTemplate->getBackgroundImage());
        }

        return $elements;
    }

    private function joinFileName(File $contestTemplateItem)
    {
        $fileName = $contestTemplateItem->getTempName();
        $fileExtension = $contestTemplateItem->getExtension();

        return $fileName . '.' . $fileExtension;
    }

    private function manageFiles($formData, $clonedContestTemplate)
    {
        $fileService = $this->container->get('file_service');
        if (null !== $formData->getThumbnail()) {
            $thumbnail = $fileService->persistFile($formData->getThumbnail());
            $formData->setThumbnail($thumbnail);
        } else {
            $formData->setThumbnail($clonedContestTemplate->getThumbnail());
        }

        if (null !== $formData->getHeaderImage()) {
            $headerImage = $fileService->persistFile($formData->getHeaderImage());
            $formData->setHeaderImage($headerImage);
        } else {
            $formData->setHeaderImage($clonedContestTemplate->getHeaderImage());
        }

        if (null !== $formData->getFooterImage()) {
            $footerImage = $fileService->persistFile($formData->getFooterImage());
            $formData->setFooterImage($footerImage);
        } else {
            $formData->setFooterImage($clonedContestTemplate->getFooterImage());
        }

        if (null !== $formData->getBackgroundImage()) {
            $backgroundImage = $fileService->persistFile($formData->getBackgroundImage());
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
}
