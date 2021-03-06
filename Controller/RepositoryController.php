<?php

namespace Cypress\GitElephantDemoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RepositoryController extends Controller
{
    /**
     * @Route("/", name="repositories")
     * @Template("CypressGitElephantDemoBundle:Repository:repositories.html.twig")
     *
     * @return array
     */
    public function repositoriesAction()
    {
        $repositories = $this->get('git_repositories');
        return array(
            'repositories' => $repositories
        );
    }

    /**
     * @Route("/{repository_name}", name="repository_root")
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @return array
     */
    public function repositoryAction(Request $request, $repository_name)
    {
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(600);
        $response->setSharedMaxAge(600);
        if ($response->isNotModified($request)) {
            return $response;
        }
        $repository = $this->get('git_repositories')->get($repository_name);
        $ref = $repository->getMainBranch();
        return $this->render('CypressGitElephantDemoBundle:Repository:tree.html.twig', array(
            'repositories'    => $this->get('git_repositories'),
            'repository_name' => $repository_name,
            'repository'      => $repository,
            'tree'            => $repository->getTree($ref),
            'reference'       => $ref,
            'path'            => ''
        ), $response);
    }

    /**
     * @Route("/{repository_name}/tree/{reference}", name="repository_tree", requirements={"reference" = ".+"})
     * @Template()
     *
     * @param \Symfony\Component\HttpFoundation\Request $request
     * @param $ref The treeish reference
     * @param $treeish_path the relative path to the content
     * @return array
     */
    public function treeAction(Request $request, $repository_name, $reference)
    {
        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(600);
        $response->setSharedMaxAge(600);
        if ($response->isNotModified($request)) {
            //return $response;
        }
        $utils = $this->get('git_repository.utilities');
        $repository = $this->get('git_repositories')->get($repository_name);
        $utils->setRepository($repository);
        $utils->setReference($reference);
        $refName = $utils->getRef();

        $ref = $repository->getBranchOrTag($refName);
        if ($ref == null) {
            throw new \InvalidArgumentException(sprintf("the reference %s is not a reference for the current repository", $reference));
        }
        $tree = $repository->getTree($ref, $utils->getPath());
        if ($repository->getMainBranch() == $ref && $tree->isRoot() && !$tree->isBlob()) {
            return $this->redirect($this->generateUrl('repository_root', array('repository_name' => $repository_name)));
        }
        return $this->render('CypressGitElephantDemoBundle:Repository:tree.html.twig', array(
            'repositories'    => $this->get('git_repositories'),
            'repository_name' => $repository_name,
            'repository'      => $repository,
            //'tree'            => $repository->getTree($ref, $utils->getPath()),
            'reference'       => $ref,
            'path'            => $utils->getPath()
        ), $response);
    }
}
