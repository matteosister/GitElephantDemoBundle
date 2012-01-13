<?php
/**
 * User: matteo
 * Date: 13/01/12
 * Time: 15.11
 *
 * Just for fun...
 */

namespace Cypress\GitElephantDemoBundle\Twig;

class CypressTwigExtension extends \Twig_Extension
{
    public function getFilters()
    {
        return array(
            'glue_lines' => new \Twig_Filter_Method($this, 'glueLines')
        );
    }

    public function glueLines($array)
    {
        return implode(PHP_EOL, $array);
    }

    public function getName()
    {
        return "cypresslab_gitelephant";
    }
}
