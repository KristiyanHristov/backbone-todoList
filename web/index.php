<?php
require_once __DIR__.'/../vendor/autoload.php';

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

$app = new Silex\Application();

$app['debug'] = true;

$app->register(new Silex\Provider\TwigServiceProvider(), array(
    'twig.path' => __DIR__.'/../views',
));

$app->before(function (Request $request) {
    if (0 === strpos($request->headers->get('Content-Type'), 'application/json')) {
        $data = json_decode($request->getContent(), true);
        $request->request->replace(is_array($data) ? $data : array());
    }
});

// Add Doctrine
$app->register(new Silex\Provider\DoctrineServiceProvider(), array(
    'db.options' => array(
        'driver'    => 'pdo_mysql',
        'dbname'    => 'backbone',
        'host'      => '127.0.0.1',
        'user'      => 'root',
        'password'  => '',
        'port'      => '3306'
    ),
));

require('../php/Todo.php');
require('../php/Notebook.php');


$app->get('/', function () use ($app) {
    return $app['twig']->render('home.twig');
});


/* ----------------------- NotebookOperations (/notebook/notebook_id) ----------------------*/


$app->get('/notebook', function () use ($app) {

    $notebooks = $app['db']->fetchAll("SELECT id, title FROM notebooks");
    
    return new Response(json_encode($notebooks), 200);
    
});

$app->post('/notebook', function (Request $request) use ($app) {
    
    $notebook = new Notebook();
    $notebook->setTitle($request->get('title'));

    $sql = "INSERT INTO notebooks (id, title) VALUES (?, ?)";
    $app['db']->executeUpdate($sql, array($notebook->getId(), $request->get('title')));
    
    return new Response(json_encode($notebook), 201);
    
});

$app->get('/notebook/{id}', function ($id) use ($app) {

    $sql = "SELECT * FROM notebooks WHERE id = ?";
    $notebook = $app['db']->fetchAssoc($sql, array((string) $id));
    
    return new Response(json_encode($notebook), 200);
    
});

$app->put('/notebook/{id}', function (Request $request, $id) use ($app) {

    $sql = "UPDATE notebooks SET title = ? WHERE id = ?";
    $notebook = $app['db']->executeUpdate($sql, array($request->get('title'),(string) $id));
    
    if ($notebook && $request->get('title')) {
        return new Response("Notebook updated successfully!", 200);
    }
    return new Response("Notebook not updated!", 200);
    
});

$app->delete('/notebook/{id}', function ($id) use ($app) {

    $sql = "DELETE FROM notebooks WHERE id = ?";
    $app['db']->executeUpdate($sql, array((string) $id));
    
    return new Response("Deleted successfully!", 200);
    
});


/* --------------------- TodoOperations (/todo/notebook_id/todo_id) ------------------*/

$app->get('/todo/{notebookId}', function ($notebookId) use ($app) {
    
    $sql = "SELECT id, title, description FROM todos WHERE notebook_id = ?";
    $notebook = $app['db']->fetchAll($sql, array($notebookId));
    
    return new Response(json_encode($notebook), 200);
    
});

$app->post('/todo/{notebookId}', function (Request $request, $notebookId) use ($app) {
    
    $todo = new Todo();
    $todo->setTitle($request->get('title'));
    $todo->setDescription($request->get('description'));

    $sql = "INSERT INTO todos (id, title, description, notebook_id) VALUES (?, ?, ?, ?)";
    $app['db']->executeUpdate($sql, array($todo->getId(), $request->get('title'), $request->get('description'), $notebookId));
    
    return new Response(json_encode($todo), 201);
    
});

$app->get('/todo/{notebookId}/{todoId}', function ($notebookId, $todoId) use ($app) {
    
    $sql = "SELECT id, title, description FROM todos WHERE notebook_id = ? AND id = ?";
    $notebook = $app['db']->fetchAssoc($sql, array($notebookId, $todoId));
    
    return new Response(json_encode($notebook), 200);
    
});

$app->put('/todo/{notebookId}/{todoId}', function (Request $request, $notebookId, $todoId) use ($app) {

    $sql = "UPDATE todos SET title = ?, description = ? WHERE notebook_id = ? AND id = ?";
    $app['db']->executeUpdate($sql, array($request->get('title'), $request->get('description'), $notebookId, $todoId));
    
    return new Response("Todo updated successfully!", 200);
    
});

$app->delete('/todo/{notebookId}/{todoId}', function ($todoId) use ($app) {

    $sql = "DELETE FROM todos WHERE id = ?";
    $app['db']->executeUpdate($sql, array($todoId));
    
    return new Response("Deleted successfully!", 200);
    
});

$app->run();
