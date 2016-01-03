<?php

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->level(Symfony\CS\FixerInterface::CONTRIB_LEVEL)
    ->fixers([
        '-pre_increment',
        '-empty_return',
        '-align_double_arrow',
        '-align_equals',
        '-logical_not_operators_with_spaces',
        '-logical_not_operators_with_successor_space',
        '-ordered_use',
        '-phpdoc_var_to_type',
    ])
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
        ->in(__DIR__)
    )
;
