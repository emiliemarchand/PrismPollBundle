<?php

namespace Prism\PollBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class PollController extends Controller
{
    /**
     * Init
     */
    public function init()
    {
        $this->pollEntity = $this->container->getParameter('prism_poll.poll_entity');
        $this->opinionEntity = $this->container->getParameter('prism_poll.opinion_entity');
        $this->pollEntityRepository = $this->getDoctrine()->getManager()->getRepository($this->pollEntity);
        $this->opinionEntityRepository = $this->getDoctrine()->getManager()->getRepository($this->opinionEntity);
        $this->voteForm = $this->container->getParameter('prism_poll.vote_form');
    }

    /**
     * List all published and opened polls
     *
     * @return Response
     */
    public function listAction()
    {
        $this->init(); // TODO: create a controller listener to call it automatically

        $polls = $this->pollEntityRepository->findBy(
            array('published' => true, 'closed' => false),
            array('createdAt' => 'DESC')
        );

        return $this->render('PrismPollBundle:Frontend\Poll:list.html.twig', array(
            'polls' => $polls
        ));
    }

    /**
     * Display and process a form to vote on a poll
     *
     * @param Request $request
     * @param int     $pollId
     *
     * @throws NotFoundHttpException
     * @return Response|RedirectResponse
     */
    public function voteAction(Request $request, $pollId)
    {
        $this->init();

        $poll = $this->pollEntityRepository->findOneBy(array('id' => $pollId, 'published' => true, 'closed' => false));

        if (!$poll) {
            throw $this->createNotFoundException("This poll doesn't exist or has been closed.");
        }

        // If the user has already voted, show the results
        if ($this->hasVoted($request, $pollId)) {
            return $this->forward('PrismPollBundle:Frontend\Poll:results', array('pollId' => $pollId, 'hasVoted' => true));
        }

        $opinionsChoices = array();
        foreach ($poll->getOpinions() as $opinion) {
            $opinionsChoices[$opinion->getId()] = $opinion->getName();
        }

        $form = $this->container->get('form.factory')->createNamed('poll' . $pollId, new $this->voteForm, null, array('opinionsChoices' => $opinionsChoices));

        if ('POST' == $request->getMethod()) {

            $form->submit($request);

            if ($form->isValid()) {

                $data = $form->getData();
                $opinion = $this->opinionEntityRepository->find($data['opinions']);
                $opinion->setVotes($opinion->getVotes() + 1);

                $em = $this->getDoctrine()->getManager();
                $em->persist($opinion);
                $em->flush();

                // If the form hasn't been sent via ajax, we redirect to the list page
                if (!$request->isXmlHttpRequest()) {
                    $response = new RedirectResponse($this->generateUrl('PrismPollBundle_frontend_poll_list'));

                // Show the results
                } else {
                    $response = $this->forward('PrismPollBundle:Frontend\Poll:results', array('pollId' => $pollId, 'hasVoted' => true));
                }

                $this->addVotingProtection($pollId, $response);
                return $response;
            }
        }

        return $this->render('PrismPollBundle:Frontend\Poll:vote.html.twig', array(
            'poll' => $poll,
            'form' => $form->createView()
        ));
    }

    /**
     * Show the results of a poll
     *
     * @param int $pollId
     *
     * @return Response
     */
    public function resultsAction($pollId, $hasVoted = false)
    {
        $this->init();

        $poll = $this->pollEntityRepository->findOneBy(array('id' => $pollId, 'published' => true));

        if (!$poll) {
            throw $this->createNotFoundException("This poll doesn't exist.");
        }

        return $this->render('PrismPollBundle:Frontend\Poll:results.html.twig', array(
            'poll' => $poll,
            'hasVoted' => $hasVoted
        ));
    }

    /**
     * Add a cookie to prevent a user from voting multiple times on the same poll
     *
     * @param int                       $pollId
     * @param Response|RedirectResponse $response
     */
    protected function addVotingProtection($pollId, $response)
    {
        return $response->headers->setCookie(new Cookie('prism_poll_' . $pollId, true, time()+3600*24*365));
    }

    /**
     * Check if the user has voted on a poll
     *
     * @param Request $request
     * @param         $pollId
     *
     * @return bool
     */
    protected function hasVoted(Request $request, $pollId)
    {
        $cookies = $request->cookies;
        if ($cookies->has('prism_poll_' . $pollId)) {
            return true;
        }

        return false;
    }
}
