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

require('../php/Todo.php');
require('../php/Notebook.php');
require('../php/DataStorage.php');

$dataStorage = DataStorage::load();

$app->get('/', function () use ($app) {
    return $app['twig']->render('home.twig');
});


/* ----------------------- NotebookOperations (/notebook/notebook_id) ----------------------*/


$app->get('/notebook', function () use ($dataStorage) {
    
    $notebooks = $dataStorage->getNotebooks();
    return new Response(json_encode($notebooks), 200);
    
});

$app->post('/notebook', function (Request $request) use ($dataStorage) {
    
    $notebook = new Notebook();
    $notebook->setTitle($request->get('title'));
    $dataStorage->addNotebook($notebook);
    $dataStorage->save();
    return new Response(json_encode($notebook), 201);
    
});

$app->get('/notebook/{id}', function ($id) use ($dataStorage) {
    
    $notebook = $dataStorage->getNotebook($id);
    return new Response(json_encode($notebook), 200);
    
});

$app->put('/notebook/{id}', function (Request $request, $id) use ($dataStorage) {
    
    $notebook = $dataStorage->getNotebook($id);
    if ($notebook && $request->get('title')) {
        $notebook->setTitle($request->get('title'));
        $dataStorage->save();
        return new Response("Notebook updated successfully!", 200);
    }
    return new Response("Notebook not updated!", 200);
    
});

$app->delete('/notebook/{id}', function ($id) use ($dataStorage) {
    
    $dataStorage->deleteNotebook($id);
    return new Response("Deleted successfully!", 200);
    
});


/* --------------------- TodoOperations (/todo/notebook_id/todo_id) ------------------*/

$app->get('/todo/{notebookId}', function ($notebookId) use ($dataStorage) {
    
    $notebook = $dataStorage->getNotebook($notebookId);
    if (!$notebook) {
        return new Response("Notebook not found!", 404);
    }
    
    return new Response(json_encode($notebook->getTodos()), 200);
    
});

$app->post('/todo/{notebookId}', function (Request $request, $notebookId) use ($dataStorage) {

    $notebook = $dataStorage->getNotebook($notebookId);
    /*if (!$notebook) {
        return new Response("Notebook not found!", 404);
    }*/
    
    $todo = new Todo();
    $todo->setTitle($request->get('title'));
    $todo->setDescription($request->get('description'));
    $notebook->addTodo($todo);
    $dataStorage->save();
    return new Response(json_encode($todo), 201);
    
});

$app->get('/todo/{notebookId}/{todoId}', function ($notebookId, $todoId) use ($dataStorage) {
    
    $notebook = $dataStorage->getNotebook($notebookId);
    $todo = $notebook->getTodo($todoId);
    return new Response(json_encode($todo), 200);
    
});

$app->put('/todo/{notebookId}/{todoId}', function (Request $request, $notebookId, $todoId) use ($dataStorage) {
    
    $notebook = $dataStorage->getNotebook($notebookId);
    if (!$notebook) {
        return new Response("Todo not updated!", 404);
    }

    $todo = $notebook->getTodo($todoId);
    $todo->setTitle($request->get('title'));
    $todo->setDescription($request->get('description'));
    $dataStorage->save();
    return new Response("Todo updated successfully!", 200);
    
});

$app->delete('/todo/{notebookId}/{todoId}', function ($notebookId, $todoId) use ($dataStorage) {
    
    $notebook = $dataStorage->getNotebook($notebookId);
    $notebook->deleteTodo($todoId);
    $dataStorage->save();
    return new Response("Deleted successfully!", 200);
    
});

$app->run();
