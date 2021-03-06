# Description
Rest api to use local git repositories without remote

# How working 
* src/
    * /Controller
        * BranchController.php
        * RepositoryController.php
    * /Service
        * GitManagerService.php

GitManagerService permit use git command on project use like parameter in the request and include in the path define in configuration, path of repositories is define
on .env

# features
* Makefile  for simple command
* docker installation just need docker-compose and docker
* test 
* endpoints
    * GET localhost/repositories/{nameRepository}/commits (get commits of repository)
    * GET localhost/repositories/{nameRepository}/branchs" (get branchs of repository)
    * GET localhost/repositories (get all repositories work with api for path define in .env file)
    * POST localhost/branchs (create new branch) Payload {branch=myNameBranch, repository=repositoryName}
    * POST localhost/branchs/merge (merge two branch of repository)  Payload {
                                                                                branchOne: "test",
                                                                                branchTwo: "test2",
                                                                                name: "name-repo"
                                                                      } 



# install
* Require  docker & dockercompose, you can use postman for test
* RUN `make install`
* RUN `make phpunit`
* You can go `localhost/repositories/python-memo/commits`

# test

`make phpunit`

# todo
- improve test for more isolation with fake repo
- custom exception for api errors
- better code error
- add listener  for api exception
- add vuejs for front interaction.
- add authentification with jwt and authentificator
- add behat test
- improve security for system command

# contributor
You can open issue and push PR like you want, thanks for help :)
