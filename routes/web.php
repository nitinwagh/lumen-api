<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return $router->app->version();
});


$router->group(['prefix' => 'api/user/'], function ($router) {
    $router->post('login', 'AuthController@authenticate');
    $router->post('register', 'AuthController@register');
});

$router->group(['prefix' => 'api/', 'middleware' => 'auth'], function ($router) {
    
    $router->get('me', 'AuthController@me');
    $router->get('user/logout', 'AuthController@logout');

    $router->get('posts', 'PostsController@index');
    $router->post('posts/create', 'PostsController@store');
    $router->get('posts/show/{id}', 'PostsController@show');
    $router->patch('posts/update/{id}', 'PostsController@update');
    $router->delete('posts/delete/{id}', 'PostsController@destoy');
    
    $router->get('comments', 'CommentsController@index');
    $router->post('comments/create', 'CommentsController@store');
    $router->get('comments/show/{id}', 'CommentsController@show');
    $router->patch('comments/update/{post_id}/{id}', 'CommentsController@update');
    $router->delete('comments/delete/{post_id}/{id}', 'CommentsController@destoy');
});