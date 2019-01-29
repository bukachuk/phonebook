<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

/**
 * Routes configuration
 */
$routes = new RouteCollection();

$routes->add('phonebook', new Route('/phonebook/{id}', ['_controller' => 'PhoneBookController', 'id' => NULL], array(), array(), '', array(), ['GET']));
$routes->add('create', new Route('/phonebook', ['_controller' => 'PhoneBookController'], array(), array(), '', array(), ['POST']));
$routes->add('update', new Route('/phonebook', ['_controller' => 'PhoneBookController'], array(), array(), '', array(), ['PUT']));
$routes->add('delete', new Route('/phonebook', ['_controller' => 'PhoneBookController'], array(), array(), '', array(), ['DELETE']));

return $routes;