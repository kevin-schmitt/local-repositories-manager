<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\{ Request, Response };
use Symfony\Component\Routing\Annotation\Route;
use App\Service\GitManagerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * @Route("/branchs")
 */
class BranchController extends AbstractController
{
    private $validator;
    private $parameterBag;
    private $gitManager;

    public function __construct(ValidatorInterface $validator, ParameterBagInterface $parameterBag, GitManagerService $gitManager)
    {
        $this->validator = $validator;
        $this->parameterBag = $parameterBag;
        $this->gitManager = $gitManager;
    }

    /**
     * merge 2 branch.
     *
     * @Route("/merge", name="branchs_merge", methods={"POST"})
     * Payload {
     *  branchOne: "test",
     *  branchTwo: "test2",
     *  name: "name-repo"
     * }
     *
     * @param Request           $request
     * @param GitManagerService $gitManager
     *
     * @return JsonResponse
     */
    public function merge(Request $request, GitManagerService $gitManager): JsonResponse
    {
        $data = [
            'branchOne' => $request->query->get('branchOne'),
            'branchTwo' => $request->query->get('branchTwo'),
            'name' => $request->query->get('name')
        ];

        $violations = $this->mergeRequest($data);
        if (count($violations) > 0) {
            return $this->json('Respect arguments branchOne, branchTwo, path', Response::HTTP_BAD_REQUEST);
        }

        $path = $this->parameterBag->get('path_repositories').$data['name'];

        if (!is_dir($path)) {
            return $this->json("The path $path directory not exist", Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            $gitManager->merge($path, $data),
             Response::HTTP_CREATED
        );
    }

    private function mergeRequest(array $data)
    {
        $constraints = new Assert\Collection([
            'branchOne' => [new Assert\Length(['min' => 2]), new Assert\NotBlank()],
            'branchTwo' => [new Assert\Length(['min' => 2]), new Assert\NotBlank()],
            'name' => [new Assert\Length(['min' => 2]), new Assert\NotBlank()],
        ]);

        return $this->validator->validate($data, $constraints);
    }

    private function createRequest(array $data)
    {
        $constraints = new Assert\Collection([
            'repository' => [new Assert\Length(['min' => 2]), new Assert\NotBlank()],
            'branch' => [new Assert\Length(['min' => 2]), new Assert\NotBlank()],
        ]);

        return $this->validator->validate($data, $constraints);
    }

    /**
     * New branch.
     *
     * @Route("", name="branchs_new", methods={"POST"})
     * Payload {
     *  branch: "myBranch",
     *  repository: "nameProject"
     * }
     *
     * @param Request           $request
     * @param GitManagerService $gitManager
     *
     * @return JsonResponse
     */
    public function createBranch(Request $request, GitManagerService $gitManager): JsonResponse
    {
        $data = [
            'branch' => $request->query->get('branch'),
            'repository' => $request->query->get('repository'),
        ];

        $violations = $this->createRequest($data);
        if (count($violations) > 0) {
            return $this->json('Invalid arguments for name branch and path project ', Response::HTTP_BAD_REQUEST);
        }

        $path = $this->getPath($data['repository']);
        if (is_array($path)) {
            return $this->json($path['error'], Response::HTTP_BAD_REQUEST);
        }

        return $this->json(
            $gitManager->createBranch($path, $data['branch']),
             Response::HTTP_CREATED
        );
    }

    private function getPath(string $name)
    {
        $path = $this->parameterBag->get('path_repositories').$name;

        if (!is_dir($path)) {
            return ['error' => "Error the path $path directory not exist"];
        }

        return $path;
    }

   
}
