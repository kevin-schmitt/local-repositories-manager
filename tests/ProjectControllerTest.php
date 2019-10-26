<?php

namespace App\Tests;

use Symfony\Component\Panther\PantherTestCase;

class ProjectControllerTest extends PantherTestCase
{
    public function setup()
    {
        //$this->repoHandler('create_repo.sh');
    }

    public function testGetCommits()
    {
        $client = static::createClient();
        $name = Parameters::NAME_REPO;
        $crawler = $client->request('GET', "/repositories/$name/commits");

        $this->assertTrue(
            $client->getResponse()->isSuccessful(),
            'bad http response'
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header not is "application/json"'
        );
    }

    public function testListProject()
    {
        $client = static::createClient();
        $name = Parameters::NAME_REPO;
        $crawler = $client->request('GET', "/repositories/");
 
        $this->assertTrue(
            $client->getResponse()->isSuccessful(),
            'bad http response'
        );
        $this->assertTrue(
            $client->getResponse()->headers->contains(
                'Content-Type',
                'application/json'
            ),
            'the "Content-Type" header not is "application/json"'
        );
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
