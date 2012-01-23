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
     * @Route("/{repository_name}/commit/{sha}", name="repository_commit")
     * @Template()
     *
     * @param $ref The treeish reference
     */
    public function showAction($repository_name, $sha)
    {
        $repository = $this->get('git_repositories')->get($repository_name);
        $commit = $repository->getCommit($sha);
        $diff = $repository->getDiff($commit);
        return array(
            'repositories'    => $this->get('git_repositories'),
            'repository_name' => $repository_name,
            //'repository'      => $repository,
            'tree'            => $repository->getTree($commit),
            'commit'          => $commit,
            'diff'            => $diff,
            'reference'       => null
        );
    }

    /**
     * @Route("/{repository_name}/commits", name="repository_commits")
     * @Template()
     */
    public function listAction($repository_name)
    {
        $repository = $this->get('git_repositories')->get($repository_name);
        return array(
            'repositories'    => $this->get('git_repositories'),
            'repository_name' => $repository_name,
            //'repository'      => $repository,
            'reference'       => null,
            'logs'            => $repository->getLog('HEAD', null, 8, 0)
        );
    }
}
