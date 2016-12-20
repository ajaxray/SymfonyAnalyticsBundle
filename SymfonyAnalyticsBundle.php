<?php
namespace Ajaxray\SymfonyAnalyticsBundle;

use Ajaxray\SymfonyAnalyticsBundle\DependencyInjection\SymfonyAnalyticsExtension;
use Symfony\Component\HttpKernel\Bundle\Bundle;

/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  GulpBusterBundle
 */
class SymfonyAnalyticsBundle extends Bundle
{
	const MUTUALLY_EXCLUSIVE_CONFIG = 600;

    public function getContainerExtension()
    {
        return new SymfonyAnalyticsExtension();
    }
}
