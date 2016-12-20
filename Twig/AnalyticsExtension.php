<?php
/**
 * @author   Anis Ahmad <anis.programmer@gmail.com>
 * @package  SymfonyAnalyticsBundle
 */

namespace Ajaxray\SymfonyAnalyticsBundle\Twig;

class AnalyticsExtension extends \Twig_Extension
{
    /**
     * @var array
     */
    protected $paths;

    /**
     * @var array
     */
    protected $hashMap;


    /**
     * @codeCoverageIgnore
     */
    public function getFilters()
    {
        return [
            new \Twig_SimpleFilter('analytics_trigger', [$this, 'getTriggerLink']),
        ];
    }

    public function getTriggerLink()
    {
        return "LATER.". __FILE__;
    }

    public function getName()
    {
        return 'analytics_extension';
    }
}
