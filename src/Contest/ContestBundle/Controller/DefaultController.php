<?php

namespace ContestBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use ContestBundle\Service\ContestService;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('@Contest/MainPage/index.html.twig');
    }

    /**
     * @Route("/contest/{id}", name="show_contest")
     * 
     * @todo przydałoby sie coś takiego żeby była metoda ->getOptions
     * która by zwracała tylko te ustawienai zamiast całego obiektu contest
     */
    public function showContestAction($id)
    {
        $contestService = $this->container->get('contest_service');

        $contest = $contestService->findContest($id);

        return $this->render('@Contest/MainPage/contestPage.html.twig', array(
            'contest_title' => $contest->getTitle(),
            'post_list' => $contest->getPublishedPosts(),
            'template'  => $contest->getTemplate(),
            'contest_id' => $contest->getId(),
            'max_files' => $contest->getMediaCountLimit(),
        ));
    }
}
