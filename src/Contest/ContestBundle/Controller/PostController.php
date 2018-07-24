<?php

namespace ContestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use ContestBundle\Form\PostType;
use ContestBundle\Form\CommentType;
use ContestBundle\Entity\Post;
use ContestBundle\Service\ContestService;
use Symfony\Component\HttpFoundation\JsonResponse;


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
            $this->addFlash(
                'notice',
                'Your changes were saved!'
            );

            return $this->redirectToRoute('show_contest', array('id' => $id));
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
        $postHref = $this->generateUrl('show_post', array('id' => $id));
        $commentForm = $this->createForm(CommentType::class, null, array(
            'post_href' => $postHref
        ));

        $parameters = array(
            'media' => $post->getMedia(),
            'author' => $post->getAuthor(),
            'postDate' => $post->getCreatedAt(),
            'votes' => $post->getVotes(),
            'comments' => $post->getComments(),
            'comment_form' => $commentForm->createView(),
        );

        $errors = array();

        $commentForm->handleRequest($request);
        if ('POST' === $request->getMethod()) {
                if ($commentForm->isValid()) {
                    echo 'VALID';
                } else {
                    $validator = $this->get('validator');
                    $errorsValidator = $validator->validate($commentForm);

                    foreach ($errorsValidator as $error) {
                        array_push($errors, $error->getMessage());
                    }

                    return new JsonResponse(array(
                        'code' => 400,
                        'message' => 'error',
                        'errors' => array('errors' => $errors)),
                        400);
                }
                
                $entityManager = $this->getDoctrine()->getManager();

                $formData = $commentForm->getData();
                $formData
                    ->setAuthor($this->getUser())
                    ->setPost($post)
                    ->setVotes(0);
                $entityManager->persist($formData);
                $entityManager->flush();
        }

        return $this->render('@Contest/Default/postModal.html.twig', array(
            'parameters' => $parameters
        ));
    }
}
