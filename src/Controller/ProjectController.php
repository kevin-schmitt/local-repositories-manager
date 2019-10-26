<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GitManagerService;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/repositories")
 */
class ProjectController extends AbstractController
{
    private $parameterBag;
    private $gitManager;

    public function __construct(ParameterBagInterface $parameterBag, GitManagerService $gitManager)
    {
        $this->parameterBag = $parameterBag;
        $this->gitManager = $gitManager;
    }

    /**
     * get commit for project $name.
     *
     * @Route("/{name}/commits", methods={"GET"})
     *
     * @param string            $name
     * @param GitManagerService $gitManager
     *
     * @return JsonResponse
     */
    public function getCommits(string $name, GitManagerService $gitManager): JsonResponse
    {
        $path = $this->parameterBag->get('path_repositories').$name;

        if (!is_dir($path)) {
            return $this->json("The path $path directory not exist", Response::HTTP_BAD_REQUEST);
        }

        $commits = $gitManager->getCommits($path);

        return $this->json($commits, Response::HTTP_OK);
    }

    /**
     * get all  repositories avaible.
     *
     * @Route("/", methods={"GET"})
     *
     * @return JsonResponse
     */
    public function list(): JsonResponse
    {
        $path = $this->parameterBag->get('path_repositories');

        if (!is_dir($path)) {
            return $this->json("The path $path directory not exist", Response::HTTP_BAD_REQUEST);
        }

        $repositories = scandir($path);

        return $this->json($repositories,  Response::HTTP_OK);
    }

      /**
     * get all branch for one project.
     *
     * @Route("/{name}/branchs", methods={"GET"})
     *
     * @param string $name
     *
     * @return JsonResponse
     */
    public function listBranchs(string $name): JsonResponse
    {
        $path = $this->parameterBag->get('path_repositories').$name;

        if (!is_dir($path)) {
            return $this->json("The path $path directory not exist", Response::HTTP_BAD_REQUEST);
        }

        $branchs = $this->gitManager->getBranchs($path);

        return $this->json($branchs);
    }
}
