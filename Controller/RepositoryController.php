<?php

namespace Cypress\GitElephantDemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

class RepositoryController extends Controller
{
    /**
     * @Route("/", name="repository_root")
     * @Template("CypressGitElephantDemoBundle:Repository:tree.html.twig")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function rootAction(Request $request)
    {
        $ref = $this->get('git_repository')->getMainBranch();
        return array(
            'repository'    => $this->get('git_repository'),
            'tree'          => $this->get('git_repository')->getTree($ref),
            'reference'     => $ref,
            'path'          => ''
        );
    }

    /**
     * @Route("/tree/{reference}", name="repository_tree", requirements={"reference" = ".+"})
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $ref The treeish reference
     * @param $treeish_path the relative path to the content
     * @return array
     */
    public function treeAction(Request $request, $reference)
    {
        $utils = $this->get('git_repository.utilities');
        $utils->setReference($reference);
        $refName = $utils->getRef();

        $ref = $this->get('git_repository')->getBranchOrTag($refName);
        if ($ref == null) {
            throw new \InvalidArgumentException(sprintf("the reference %s is not a reference for the current repository", $reference));
        }
        $tree = $this->get('git_repository')->getTree($ref, $utils->getPath());
        if ($this->get('git_repository')->getMainBranch() == $ref && $tree->isRoot() && !$tree->isBlob()) {
            return $this->redirect($this->generateUrl('repository_root'));
        }
        return array(
            'repository'    => $this->get('git_repository'),
            'tree'          => $this->get('git_repository')->getTree($ref, $utils->getPath()),
            'reference'     => $ref,
            'path'          => $utils->getPath()
        );
    }
}
