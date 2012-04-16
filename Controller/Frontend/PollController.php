<?php

namespace Prism\PollBundle\Controller\Frontend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;


class PollController extends Controller
{
    /**
     * Init
     */
    public function init()
    {
        $this->pollEntity = $this->container->getParameter('prism_poll.poll_entity');
        $this->opinionEntity = $this->container->getParameter('prism_poll.opinion_entity');
        $this->pollEntityRepository = $this->getDoctrine()->getEntityManager()->getRepository($this->pollEntity);
        $this->opinionEntityRepository = $this->getDoctrine()->getEntityManager()->getRepository($this->opinionEntity);
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
     * @param int $pollId
     *
     * @return Response
     */
    public function voteAction($pollId)
    {
        $this->init();

        $poll = $this->pollEntityRepository->findOneBy(array('id' => $pollId, 'published' => true, 'closed' => false));

        if (!$poll) {
            throw $this->createNotFoundException("This poll doesn't exist or has been closed.");
        }

        $opinionsChoices = array();
        foreach ($poll->getOpinions() as $opinion) {
            $opinionsChoices[$opinion->getId()] = $opinion->getName();
        }

        $form = $this->container->get('form.factory')->createNamed(new $this->voteForm, 'poll' . $pollId, null, array('opinionsChoices' => $opinionsChoices));

        if ('POST' == $this->getRequest()->getMethod()) {

            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {

                $data = $form->getData();
                $opinion = $this->opinionEntityRepository->find($data['opinions']);
                $opinion->setVotes($opinion->getVotes() + 1);

                $em = $this->getDoctrine()->getEntityManager();
                $em->persist($opinion);
                $em->flush();

                // If the form hasn't been sent via ajax, we redirect to the list page
                if (!$this->getRequest()->isXmlHttpRequest()) {
                    return $this->redirect($this->generateUrl('PrismPollBundle_frontend_poll_list'));

                // Show the results
                } else {
                    return $this->forward('PrismPollBundle:Frontend\Poll:results', array('pollId' => $pollId));
                }
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
    public function resultsAction($pollId)
    {
        $this->init();

        $poll = $this->pollEntityRepository->findOneBy(array('id' => $pollId, 'published' => true));

        if (!$poll) {
            throw $this->createNotFoundException("This poll doesn't exist.");
        }

        return $this->render('PrismPollBundle:Frontend\Poll:results.html.twig', array(
            'poll' => $poll
        ));
    }
}
