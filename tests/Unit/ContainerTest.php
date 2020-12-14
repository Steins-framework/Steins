<?php

namespace Tests\Unit;

use App\Http\Kernel;
use Steins\Application\Application;
use Steins\Configuration\Configuration;
use Steins\Kernel\KernelInterface;
use Tests\TestCase;

class ContainerTest extends TestCase
{
    protected function setUp(): void
    {
        $this->app = $this->createApplication();
    }

    public function tearDown(): void
    {

    }

    public function test_container_can_resolve_interface()
    {
        $this->app->bind(KernelInterface::class, Kernel::class);

        $kernel = $this->app->make(Kernel::class);

        $this->assertInstanceOf(Kernel::class, $kernel);
    }

    public function test_container_can_resolve_closure()
    {
        $this->app->bind(KernelInterface::class, function ($app){
            return new Kernel($app, new Configuration($app));
        });

        $kernel = $this->app->make(KernelInterface::class);

        $this->assertInstanceOf(Kernel::class, $kernel);
    }

    public function test_instance_is_the_same_in_singleton_mode()
    {
        $this->app->singleton(KernelInterface::class, Kernel::class);

        $x = $this->app->make(KernelInterface::class);
        $y = $this->app->make(KernelInterface::class);

        $this->assertTrue($x === $y);
    }
}