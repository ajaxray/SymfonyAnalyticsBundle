<?php
namespace Ajaxray\SymfonyAnalyticsBundle\Tests\app;
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */
set_time_limit(0);
require_once __DIR__.'/autoload.php';

use \Symfony\Bundle\FrameworkBundle\Console\Application;

$kernel = new AppKernel('test', true);
$application = new Application($kernel);
$application->run();