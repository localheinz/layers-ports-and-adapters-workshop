<?php

use Localheinz\PhpCsFixer\Config;

$config = Config\Factory::fromRuleSet(new Config\RuleSet\Php71());

$config->getFinder()->in(__DIR__);
$config->setCacheFile(__DIR__ . '/.php_cs.cache');

return $config;
