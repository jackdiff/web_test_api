<?php

// Environment: test | dev | staging | production
$app['env'] = isset($_ENV['env']) ? $_ENV['env'] : 'dev';
