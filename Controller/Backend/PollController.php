<?php

namespace Prism\PollBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use Prism\PollBundle\Form\PollType;

class PollController extends Controller
{
    /**
     * List all polls
     *
     * @return Response
     */
    public function listAction()
    {
        $polls = $this->getDoctrine()->getEntityManager()->getRepository('PrismPollBundle:Poll')->findAll();

        return $this->render('PrismPollBundle:Backend\Poll:list.html.twig', array(
            'polls' => $polls
        ));
    }

    /**
     * Edit or add a new Poll
     *
     * @param Poll $pollId
     *
     * @return Response
     */
    public function editAction($pollId)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $poll = $em->getRepository('PrismPollBundle:Poll')->find($pollId);

        if (!$poll) {
            $this->pollEntity = $this->container->getParameter('prism_poll.poll_entity');
            $poll = new $this->pollEntity;
        }

        $form = $this->createForm(new PollType(), $poll);

        if ('POST' == $this->getRequest()->getMethod()) {

            $form->bindRequest($this->getRequest());

            if ($form->isValid()) {
                $em->persist($poll);
                $em->flush();
            }
        }

        return $this->render('PrismPollBundle:Backend\Poll:edit.html.twig', array(
            'poll' => $poll,
            'form' => $form->createView()
        ));
    }
}
