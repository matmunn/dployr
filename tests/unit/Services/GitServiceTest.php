<?php

namespace Tests\Unit\Services;

use Mockery;
use Tests\TestCase;
use App\Models\Repository;
use GitWrapper\GitWrapper;
use GitWrapper\GitBranches;
use App\Services\GitService;
use GitWrapper\GitWorkingCopy;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class GitServiceTest extends TestCase
{
    use DatabaseMigrations;

    function generateGitWrapperMock($workingCopyMock)
    {
        $mock = Mockery::mock(GitWrapper::class)
            ->shouldReceive('setGitBinary')
            ->shouldReceive('setPrivateKey')
            ->shouldReceive('setTimeout')
            ->shouldReceive('workingCopy')
            ->andReturn($workingCopyMock->getMock());

        return $mock->getMock();
    }

    /** @test */
    function can_get_the_underlying_repository()
    {
        $repo = factory(Repository::class)->create();

        $gitService = new GitService($repo);

        $this->assertEquals($repo->id, $gitService->getRepository()->id);
    }

    /** @test */
    function can_get_a_git_instance_for_the_repository()
    {
        $repo = factory(Repository::class)->create();
        
        $gitService = new GitService($repo);

        $this->assertInstanceOf(GitWrapper::class, $gitService->getGitInstance(false));
    }

    /** @test */
    function can_get_a_git_working_copy_for_the_repository()
    {
        $repo = factory(Repository::class)->create();

        $gitService = new GitService($repo);

        $this->assertInstanceOf(GitWorkingCopy::class, $gitService->getGitInstance());
    }

    /** @test */
    function can_get_a_list_changed_files_for_single_commit()
    {
        $repo = factory(Repository::class)->create();

        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('run')
            ->with(['diff', '--name-status', 'HEAD~1', 'HEAD'])
            ->andReturn(['file1', 'file2']);
        
        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertEquals(['file1', 'file2'], $gitService->changedFiles('HEAD'));
    }

    /** @test */
    function can_get_a_list_changed_files_for_two_given_commits()
    {
        $repo = factory(Repository::class)->create();

        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('run')
            ->with(['diff', '--name-status', 'commit2', 'commit1'])
            ->andReturn(['file1', 'file2']);
        
        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertEquals(['file1', 'file2'], $gitService->changedFiles('commit1', 'commit2'));
    }

    /** @test */
    function can_get_the_current_commit()
    {
        $repo = factory(Repository::class)->create();
        
        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('run')
            ->with(['rev-parse', 'HEAD'])
            ->andReturn('commit-hash');

        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertEquals('commit-hash', $gitService->currentCommit());
    }

    /** @test */
    function can_get_a_list_of_remote_branches()
    {
        $repo = factory(Repository::class)->create();

        $gitBranchesMock = Mockery::mock(GitBranches::class)
            ->shouldReceive('remote')
            ->andReturn(['master', 'staging']);
        
        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('getBranches')
            ->andReturn($gitBranchesMock->getMock());

        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertEquals(['master', 'staging'], $gitService->getBranches('remote'));
    }

    /** @test */
    function can_get_a_list_of_all_branches()
    {
        $repo = factory(Repository::class)->create();

        $gitBranchesMock = Mockery::mock(GitBranches::class)
            ->shouldReceive('all')
            ->andReturn(['testing1', 'testing2']);
        
        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('getBranches')
            ->andReturn($gitBranchesMock->getMock());

        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertEquals(['testing1', 'testing2'], $gitService->getBranches());
    }

    /** @test */
    function head_branches_get_stripped()
    {
        $repo = factory(Repository::class)->create();

        $gitBranchesMock = Mockery::mock(GitBranches::class)
            ->shouldReceive('all')
            ->andReturn(['HEAD', 'master']);
        
        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('getBranches')
            ->andReturn($gitBranchesMock->getMock());

        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertEquals([1 => 'master'], $gitService->getBranches());
    }

    /** @test */
    function can_get_the_current_branch()
    {
        $repo = factory(Repository::class)->create();

        $gitBranchesMock = Mockery::mock(GitBranches::class)
            ->shouldReceive('head')
            ->andReturn('test123');
        
        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('getBranches')
            ->andReturn($gitBranchesMock->getMock());

        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertEquals('test123', $gitService->getCurrentBranch());
    }

    /** @test */
    function cannot_change_to_a_branch_if_it_doesnt_exist_on_remote()
    {
        $repo = factory(Repository::class)->create();

        $gitBranchesMock = Mockery::mock(GitBranches::class)
            ->shouldReceive('remote')
            ->andReturn(['test-branch'])
            ->shouldReceive('head')
            ->andReturn('other-branch');

        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('getBranches')
            ->andReturn($gitBranchesMock->getMock());

        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertFalse($gitService->changeBranch('new-branch'));
    }

    /** @test */
    function git_branch_doesnt_change_if_already_on_it()
    {
        $repo = factory(Repository::class)->create();

        $gitBranchesMock = Mockery::mock(GitBranches::class)
            ->shouldReceive('remote')
            ->andReturn(['new-branch'])
            ->shouldReceive('head')
            ->andReturn('new-branch');

        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('getBranches')
            ->andReturn($gitBranchesMock->getMock());

        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertFalse($gitService->changeBranch('new-branch'));
    }

    /** @test */
    function can_change_to_a_branch_if_it_exists_and_not_already_on_it()
    {
        $repo = factory(Repository::class)->create();

        $gitBranchesMock = Mockery::mock(GitBranches::class)
            ->shouldReceive('remote')
            ->andReturn(['new-branch'])
            ->shouldReceive('head')
            ->andReturn('other-branch');

        $testingGitWorkingCopy = Mockery::mock(GitWorkingCopy::class)
            ->shouldReceive('getBranches')
            ->andReturn($gitBranchesMock->getMock())
            ->shouldReceive('checkout')
            ->shouldReceive('pull');

        $gitWrapperMock = $this->generateGitWrapperMock($testingGitWorkingCopy);

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertTrue($gitService->changeBranch('new-branch'));
    }

    /** @test */
    function can_get_commit_message_from_hash()
    {
        $repo = factory(Repository::class)->create();

        $gitWrapperMock = Mockery::mock(GitWrapper::class)
            ->shouldReceive('setGitBinary')
            ->shouldReceive('setPrivateKey')
            ->shouldReceive('setTimeout')
            ->shouldReceive('getDirectory')
            ->andReturn('./test-path')
            ->shouldReceive('git')
            ->with('log --format=%B -n 1 COMMITHASH', './test-path')
            ->andReturn('This is an example commit message')
            ->getMock();

        $gitService = new GitService($repo, $gitWrapperMock);

        $this->assertEquals('This is an example commit message', $gitService->getCommitMessage('COMMITHASH'));
    }
}
