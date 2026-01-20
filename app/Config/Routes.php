<?php

namespace Config;

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
    require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/**
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// Home page
$routes->get('/', 'Home::index');

// DB test (optional)
$routes->get('db-test', 'DbTest::index');

// Courses page (license filter)
$routes->get('courses', 'Courses::index');

/**
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * You can load environment-based routes here.
 */
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

$routes->group('admin', function($routes) {

    // TERMS
    $routes->get('terms', 'Admin\CourseTerms::index');
    $routes->get('terms/create', 'Admin\CourseTerms::create');
    $routes->post('terms/store', 'Admin\CourseTerms::store');
    $routes->get('terms/edit/(:num)', 'Admin\CourseTerms::edit/$1');
    $routes->post('terms/update/(:num)', 'Admin\CourseTerms::update/$1');
    $routes->get('terms/delete/(:num)', 'Admin\CourseTerms::delete/$1');

    // COURSES
    $routes->get('courses', 'Admin\Courses::index');
    $routes->get('courses/create', 'Admin\Courses::create');
    $routes->post('courses/store', 'Admin\Courses::store');

    $routes->get('courses/edit/(:num)', 'Admin\Courses::edit/$1');
    $routes->post('courses/update/(:num)', 'Admin\Courses::update/$1');
    $routes->get('courses/delete/(:num)', 'Admin\Courses::delete/$1');
    $routes->get('courses/ajax', 'Courses::ajaxCourses');


});






