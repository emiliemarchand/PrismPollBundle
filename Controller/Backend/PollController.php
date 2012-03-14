<?php

namespace Prism\PollBundle\Controller\Backend;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

class PollController extends Controller
{
    /**
     * List all polls
     *
     * @return Response
     */
    public function listAction()
    {
        return $this->render('PrismPollBundle:Backend\Poll:list.html.twig');
    }
}
