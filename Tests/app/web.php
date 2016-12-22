<?php
namespace Ajaxray\SymfonyAnalyticsBundle\Tests\app;

/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
use Symfony\Component\HttpFoundation\Request;
require_once __DIR__.'/autoload.php';

$kernel = new AppKernel('prod', true);

$request = Request::createFromGlobals();
$response = $kernel->handle($request);
$response->send();