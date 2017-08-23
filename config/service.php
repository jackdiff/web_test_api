<?php

/**=================================================================
------ Framework Services--------
==================================================================**/

$app->register(
    new Framework\Provider\UserServiceProvider(), array()
);

//Register Logger services  = ..
$app->register(
    new Framework\Provider\LoggerServiceProvider()
);

// Session
$app->register(
    new Framework\Provider\SessionServiceProvider(), array()
);

// Database
$app->register(
    new Framework\Provider\DatabaseServiceProvider(), array()
);