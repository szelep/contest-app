<?php

namespace ContestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ContestBundle\Form\PostType;
use ContestBundle\Entity\Post;
use ContestBundle\Service\ContestService;

class PostController extends Controller
{
    /**
     * @Route("/contest/add/{id}", name="add_new_post")
     */
    public function addNewPostAction(Request $request, $id)
    {
        
        $contestService = $this->container->get('contest_service');
        $fileService = $this->container->get('file_service');
        $contest = $contestService->findContest($id);
        $post = new Post();
        $form = $this->createForm(PostType::class, $post);

        $form->handleRequest($request);
        if ('POST' === $request->getMethod()) {
            $formData = $form->getData();
            $media = $formData->getMedia();
            
            

            $entityManager = $this->getDoctrine()->getManager();
            $formData
                ->setAuthor($this->getUser())
                ->setThumbnail('x')
                ->setMedia(null)
                ->setContest($contest)
            ;

            $entityManager->persist($formData);
            $entityManager->flush();

            $fileService->manageMultipleUpload($media, $post);
        }

        return $this->render('@Contest/Form/addNewPost.html.twig', array(
            'form' => $form->createView(),
            'contest_id' => $id,
        ));
    }

    /**
     * @Route("/contest/post/show/{id}", name="show_post")
     */
    public function showPostAction(Request $request, $id)
    {
        $contestService = $this->container->get('contest_service');
        $post = $contestService->findPost($id);
    }
}
