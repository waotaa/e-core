<?php

namespace Tests\Feature\Commands;

use Vng\EvaCore\Models\Township;
use Vng\EvaCore\Models\User;
use Tests\TestCase;

class SetupCommandTest extends TestCase
{
//    /** @test */
//    public function it_will_setup(): void
//    {
//        $this->artisan('eva:setup')
//            ->expectsOutput('setting up...')
//            ->expectsOutput('setting up finished!')
//            ->assertExitCode(0);
//
//        $this->assertAdminUser();
//        $this->assertNotEquals(0, $this->getTownshipCount());
//    }

//    /** @test */
//    public function it_will_setup_a_test_environment()
//    {
//        $this->artisan('eva:setup -t')
//            ->expectsOutput('setting up...')
//            ->expectsQuestion('password test users', 'password')
//            ->expectsOutput('setting up finished!')
//            ->assertExitCode(0);
//
//        $this->assertAdminUser();
//        $this->assertNotEquals(0, $this->getTownshipCount());
//    }

//    /** @test */
//    public function it_will_silently_setup_a_test_environment()
//    {
//        $this->artisan('eva:setup -t -s')
//            ->expectsOutput('setting up...')
//            ->expectsOutput('setting up finished!')
//            ->assertExitCode(0);
//
//        $this->assertAdminUser();
//        $this->assertNotEquals(0, $this->getTownshipCount());
//    }

    /** @test */
    public function it_will_setup_lean()
    {
        $this->artisan('eva:setup -l')
            ->expectsOutput('setting up...')
            ->expectsOutput('setting up finished!')
            ->assertExitCode(0);

        $this->assertAdminUser();
        $this->assertEquals(0, $this->getTownshipCount());
    }

    private function assertAdminUser()
    {
        $superAdminEmail = config('eva-core.admin.email');
        $userExists = User::query()->where('email', $superAdminEmail)->exists();
        $this->assertTrue($userExists);
    }

    private function getTownshipCount()
    {
        return Township::all()->count();
    }
}
