<?php

namespace ContestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use ContestBundle\Form\PostType;
use ContestBundle\Form\CommentType;
use ContestBundle\Form\VoteType;
use ContestBundle\Entity\Post;
use ContestBundle\Entity\Comment;
use ContestBundle\Entity\Vote;
use ContestBundle\Service\ContestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

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
                ->setPublished($contest->getAutoPublishNewPost())
            ;

           $entityManager->persist($formData);


            $fileUpload = $fileService->manageMultipleUpload($media, $post);

            if (false === $fileUpload) {
                $accessService = $this->container->get('access_service');
                $allowedTypes = implode(',', $accessService->findAllowedFileTypes($post));

                $this->addFlash(
                    'error',
                    'Jeden lub więcej plików ma nieprawnidłowe rozszerzenie. Dopuszczalne typy plików: ' . $allowedTypes
                );
            } elseif (false !== $fileUpload) {
                $entityManager->flush();
            }

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
        $contest = $post->getContest();
        $postHref = $this->generateUrl('show_post', array('id' => $id));
        $voteHref = $this->generateUrl('vote', array('id' => $id));

        $commentForm = $this->createForm(CommentType::class, null, array(
            'post_href' => $postHref
        ));

        $voteForm = $this->createForm(VoteType::class, null, array(
            'post_href' => $voteHref
        ));

        $parameters = array(
            'media'         => $post->getMedia(),
            'author'        => $post->getAuthor(),
            'postDate'      => $post->getCreatedAt(),
            'votes'         => count($post->getVotes()),
            'comments'      => $post->getComments(),
            'comment_form'  => $commentForm->createView(),
            'vote_form'     => $voteForm->createView(),
        );

        $errors = array();

        $commentForm->handleRequest($request);
        if ('POST' === $request->getMethod()) {
            if ($commentForm->isValid()) {
                $entityManager = $this->getDoctrine()->getManager();

                $formData = $commentForm->getData();
                 $formData
                    ->setAuthor($this->getUser())
                    ->setPost($post)
                    ->setVotes(0);

                $entityManager->persist($formData);
                $entityManager->flush();
            } else {
                $validator = $this->get('validator');
                $errorsValidator = $validator->validate($commentForm);

                foreach ($errorsValidator as $error) {
                    array_push($errors, $error->getMessage());
                }

                return new JsonResponse(array(
                    'code' => 400,
                    'status'  => 'error',
                    'message' => $errors,
                    ),
                    400
                );
            }
        }

        return $this->render('@Contest/Default/postModal.html.twig', array(
            'parameters' => $parameters
        ));
    }

    /**
     * @Route("/contest/post/comment/remove/{id}", name="remove_comment")
     *
     * @Method("POST")
     *
     * @throws AccessDeniedException ikona usunięcia jest wyświetlana tylko przy swoich komentarzach
     *      jeżeli jakimś cudem ktoś wywoła post z id nieswojego komentarza wypluwa ten wyjątek.
     */
    public function removeCommentAction($id)
    {
        $entityManager = $this->getDoctrine()->getManager();

        $comment = $entityManager
                ->getRepository(Comment::class)
                ->findOneById($id);

        if (null === $comment) {
            throw new NotFoundHttpException('Nie odnaleziono komentarza');
        }

        $commentOwner = $comment->getAuthor();
        $currentUser = $this->getUser();

        if ($currentUser === $commentOwner) {
            $entityManager->remove($comment);
            $entityManager->flush();

            return new JsonResponse(array(
                'code' => 200,
                'status'  => 'success',
                'message' => array('Usunięto komentarz'),
                ),
                200
            );
        } else {
            throw new AccessDeniedException("Ten komentarz nie należy do Ciebie");
        }


    }

    /**
     * @Route("/contest/post/vote/{id}", name="vote")
     *
     * @Method("POST")
     */
    public function voteAction($id)
    {
        // 1. trzeba sprawdzić czy pozwala na głsoowanie konkurs
        // 2. trzeba sprawdzić czy szkodnik nie zagłosował na zbyt dużo
        // 3. zrobić w serwisie opcje która sprawdza czy dany konkurs jest aktualy czyt czy data głosowania nie minęła
        // 4. przy dodawaniu/usuwaniu vote musi sprawdzać czy może to zrobić
        // 5. wystarczy serwis który odbierze wszystkie możliwości gdy konkurs jest po terminie/odpublikowany
        //


        $contestService = $this->container->get('contest_service');
        $post = $contestService->findPost($id);

        $voteStatus = $contestService->manageVote($this->getUser(), $post);

        if (false === $voteStatus) {
            $jsonResponse = array(
                'status'  => 'success',
                'message' => 'Oddano głos!',
            );
        } elseif (false !== $voteStatus) {
            $jsonResponse = array(
                'status'  => 'error',
                'message' => 'Usunięto głos!',
            );
        }

        return new JsonResponse(array(
            'code'    => 200,
            'status'  => $jsonResponse['status'],
            'message' => array($jsonResponse['message'])
            ),
            200
        );
    }
}
