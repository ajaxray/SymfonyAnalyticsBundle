<?php

namespace Ajaxray\SymfonyAnalyticsBundle\Controller;

use Ajaxray\SymfonyAnalyticsBundle\Event\RequestEvent;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return new Response('Welcome to the native Analytics of Symfony!');
    }

	public function manualRequestAction(Request $request)
	{
		$this->get('event_dispatcher')->dispatch('analytics.request.manual', new RequestEvent($request));
		return new Response('Testing tracking of manual request.');
	}
}
