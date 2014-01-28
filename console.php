<?php

require_once __DIR__.'/php/app.php';

// Register assetic and set up CSSMin
$app->register(new SilexAssetic\AsseticServiceProvider(), array(
    'assetic.options' => array(
        'debug' => true,
        'auto_dump_assets' => true,
    )
));

use Symfony\Component\Console\Application;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

$console = new Application('Backgone - TodoList', '0.1');

$console
    ->register('assetic:dump')
    ->setDescription('Dumping assets from project')
    ->setCode(function (InputInterface $input, OutputInterface $output) use ($app) {

        // Boot assetic
        $assetic = $app['assetic'];

        $dumper = $app['assetic.dumper'];
        if (isset($app['twig'])) {
            $dumper->addTwigAssets();
        }
        $dumper->dumpAssets();

        $output->writeln('');
        $output->writeln('<info>Assetic Dump finished.</info>');
    });

$console->run();
