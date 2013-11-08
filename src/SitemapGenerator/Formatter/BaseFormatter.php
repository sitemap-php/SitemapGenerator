<?php

namespace SitemapGenerator\Formatter;

abstract class BaseFormatter
{
    protected function escape($string)
    {
        return htmlspecialchars($string, ENT_QUOTES);
    }
}
