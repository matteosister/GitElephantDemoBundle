<?php
/**
 * User: matteo
 * Date: 14/11/11
 * Time: 22.56
 *
 * Just for fun...
 */

namespace Cypress\GitElephantDemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class CommitController extends Controller
{
    /**
     * @Route("/commit/{sha}", name="repository_commit")
     * @Template()
     *
     * @param $ref The treeish reference
     */
    public function showAction($sha)
    {
        $repository = $this->get('git_repository');
        $commit = $repository->getCommit($sha);
        $diff = $repository->getDiff($commit);
        return array(
            'repository'    => $this->get('git_repository'),
            'tree'          => $this->get('git_repository')->getTree($commit),
            'commit'        => $commit,
            'diff'          => $diff
        );
    }

    /**
     * @Route("/commits", name="repository_commits")
     * @Template()
     */
    public function listAction()
    {
        $repository = $this->get('git_repository');
        return array(
            'repository'    => $repository,
            'reference'     => null,
            'logs'          => $repository->getLog()
        );
    }
}
