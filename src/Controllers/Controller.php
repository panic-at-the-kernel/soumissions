<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;

class Controller
{

    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * Get the value of container
     */
    public function getContainer()
    {
        return $this->container;
    }
}
