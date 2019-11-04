<?php

namespace App\Tests\Controller;

use App\Tests\AbstractWebTestCase;
use App\Tests\Parameters;

class ProjectControllerTest extends AbstractWebTestCase
{
    public function testGetCommits()
    {
        $name = Parameters::NAME_REPO;

        $this->client->request('GET', "/repositories/$name/commits");
        $response = $this->getDecodedResponse();

        unset($response[0]);
        $this->assertGreaterThanOrEqual(1, count($response));
        foreach ($response as $commit) {
            $this->assertEquals(true, strpos($commit, 'Date'));
            $this->assertEquals(true, strpos($commit, 'Author'));
            $this->assertEquals(true, strpos($commit, '@'));
        }
    }

    public function testGetRepositories()
    {
        $name = Parameters::NAME_REPO;
        $this->client->request('GET', '/repositories/');

        $response = $this->getDecodedResponse();

        $this->assertEquals(true, in_array('python-memo', $response));
    }

    /*
    private function repoHandler(string $nameScript)
    {
        //create fake repo for git
        $old_path = getcwd();
        $name = Parameters::NAME_REPO;
        $path = Parameters::PATH;
        shell_exec("tests/$nameScript $path $name");
        chdir($old_path);
    }

    protected function tearDown()
    {
        $this->repoHandler('delete_repo.sh');
    }
    */
}
