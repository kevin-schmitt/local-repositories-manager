<?php

namespace App\Service;

use Gitonomy\Git\Repository as Repository;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class GitManagerService
{
    protected $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    /**
     * get All branch for specific project.
     *
     * @return array|string
     */
    public function getBranchs(string $path)
    {
        $messages = [
            'error' => 'Impossible to display branch',
        ];

        try {
            $branchs = $this->systemCall($path, 'git branch');
            // parse for clean array
            return array_filter(
                preg_split("/[\s,]+/", $branchs, -1),
                function ($branch) {
                    return '' !== $branch;
                }
            );
        } catch (Exception $e) {
            return $messages['error'];
        }
    }

    /**
     * Permit with path of repository to get all commit.
     */
    public function getCommits(string $path): array
    {
        $messages = [
            'error' => 'Impossible to display log',
        ];

        try {
            $log = $this->systemCall($path, 'git log');

            return explode('commit', $log);
        } catch (Exception $e) {
            return $messages['error'];
        }
    }

    /**
     * Permit create new branch.
     *
     * @return array
     */
    public function createBranch(string $path, string $name): string
    {
        $messages = [
            'error' => 'Impossible to add new branch',
            'success' => "New branch $name created",
        ];

        try {
            $this->systemCall($path, "git branch $name");

            return $messages['success'];
        } catch (Exception $e) {
            return $messages['error'];
        }
    }

    /**
     * merge branchs.
     *
     * @param string $name
     *
     * @return array
     */
    public function merge(string $path, array $data): string
    {
        $messages = [
            'error' => 'Impossible to merge',
            'success' => 'Merge success',
        ];

        extract($data);
        $resCommand = $this->systemCall($path, "git checkout $branchOne && git merge $branchTwo");

        return (is_int($resCommand)) ? $messages['error'] : $messages['success'];
    }

    private function systemCall(string $path, string $command)
    {
        chdir($path);
        ob_start();
        system($command, $retval);

        return (0 !== $retval) ? $retval : ob_get_clean();
    }
}
