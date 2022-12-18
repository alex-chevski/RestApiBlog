<?php

declare(strict_types=1);

use DevCoder\DotEnv;
use DI\ContainerBuilder;

$builder = new ContainerBuilder();
$builder->addDefinitions(__DIR__.'/../config/di.php');
(new DotEnv(__DIR__.'/../.env'))->load();

return $builder->build();
