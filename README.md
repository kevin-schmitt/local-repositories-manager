# Description
Rest api to use local git repositories without remote

# How working 
* src/
    * /Controller
        * BranchController.php
        * RepositoryController.php
    * /Service
        * GitManagerService.php

GitManagerService permit to use git command on project use like parameter in the request, path of repositories if define
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



# installation
Require  docker & dockercompose, you can use postman for test
RUN `make install`
RUN `make phpunit`
You can go `localhost/repositories/python-demo/commits`

# test

`make phpunit`

# todo
- improve test for more isolation with fake repo
- custom exception for api errors
- add vuejs for front interaction.
- add authentification with jwt and authentificator
- improve validation of data and create class for handler request
- add behat test
- improve security for system command

# contributor
You can open issue and push PR like you want, thanks for help :)