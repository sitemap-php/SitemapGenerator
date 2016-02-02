<?php

namespace SitemapGenerator\Routing;

interface UrlGenerator
{
    /**
     * Generates a URL or path for a specific route based on the given parameters.
     *
     * Parameters that reference placeholders in the route pattern will substitute them in the
     * path or host. Extra params are added as query string to the URL.
     *
     * @param string      $name          The name of the route
     * @param mixed       $parameters    An array of parameters
     *
     * @return string The generated URL
     */
    public function generate($name, $parameters = []);
}
