<?php

return Symfony\CS\Config\Config::create()
    ->setUsingCache(true)
    ->level(Symfony\CS\FixerInterface::CONTRIB_LEVEL)
    ->finder(
        Symfony\CS\Finder\DefaultFinder::create()
        ->in(__DIR__)
    )
;
