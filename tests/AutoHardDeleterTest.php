<?php

namespace sbamtr\LaravelAutoHardDeleter\Tests;

use Carbon\Carbon;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Testing\WithFaker;
use Orchestra\Testbench\TestCase;
use sbamtr\LaravelAutoHardDeleter\Tests\Models\SampleModel;
use Symfony\Component\Console\Tester\CommandTester;

class AutoHardDeleterTest extends TestCase
{
    use WithFaker;

    public function test()
    {
        factory(SampleModel::class, $numberOfNotExpiredRows = $this->faker->numberBetween(1, 100))->create(['del' => Carbon::now()->subDays($this->faker->numberBetween(1, 14))]);
        factory(SampleModel::class, $this->faker->numberBetween(1, 100))->create(['del' => Carbon::now()->subDays(15)]);
        factory(SampleModel::class, $this->faker->numberBetween(1, 100))->create(['del' => Carbon::now()->subDays($this->faker->numberBetween(16, 100))]);

        $app = new Application(realpath(__DIR__.'/../'));
        $command = $this->app->make(HardDeleteExpiredTestCommand::class);
        $command->setLaravel($app);
        $tester = new CommandTester($command);
        $tester->execute([]);

        self::assertEquals($numberOfNotExpiredRows, SampleModel::withTrashed()->count());
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testbench');
        $app['config']->set('database.connections.testbench', [
            'driver'   => 'sqlite',
            'database' => ':memory:',
            'prefix'   => '',
        ]);
    }

    protected function setUp(): void
    {
        parent::setUp();
        $this->loadMigrationsFrom(__DIR__.'/database/migrations');
        $this->withFactories(__DIR__.'/database/factories');
    }
}
