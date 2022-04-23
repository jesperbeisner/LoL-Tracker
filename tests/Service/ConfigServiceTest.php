<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Entity\Config;
use App\Repository\ConfigRepository;
use App\Service\ConfigService;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PHPUnit\Framework\TestCase;

/**
 * @covers \App\Service\ConfigService
 */
final class ConfigServiceTest extends TestCase
{
    /**
     * @covers \App\Service\ConfigService::set
     */
    public function test_update_value_and_set_new_updated_when_config_already_exists(): void
    {
        $config = new Config();
        $config->setValue('Original');

        $configRepository = $this->createMock(ConfigRepository::class);
        $configRepository
            ->method('findOneBy')
            ->with(['key' => 'Test'])
            ->willReturn($config)
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getRepository')
            ->with($this->equalTo(Config::class))
            ->willReturn($configRepository)
        ;

        $configService = new ConfigService($entityManager);

        $this->assertSame('Original', $config->getValue());
        $this->assertNull($config->getUpdated());

        $configService->set('Test', 'Test');

        $this->assertSame('Test', $config->getValue());
        $this->assertInstanceOf(DateTime::class, $config->getUpdated());
    }

    /**
     * @covers \App\Service\ConfigService::set
     */
    public function test_create_and_persist_new_config_when_config_not_already_exists(): void
    {
        $configRepository = $this->createMock(ConfigRepository::class);
        $configRepository
            ->method('findOneBy')
            ->with(['key' => 'Test'])
            ->willReturn(null)
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getRepository')
            ->with($this->equalTo(Config::class))
            ->willReturn($configRepository)
        ;

        $entityManager->expects($this->once())
            ->method('persist')
            ->with($this->callback(fn(Config $config): bool => true))
        ;

        $configService = new ConfigService($entityManager);
        $configService->set('Test', 'Test');
    }

    /**
     * @covers \App\Service\ConfigService::get
     */
    public function test_return_value_when_key_exists(): void
    {
        $config = new Config();
        $config->setValue('Test');

        $configRepository = $this->createMock(ConfigRepository::class);
        $configRepository
            ->method('findOneBy')
            ->with(['key' => 'Key'])
            ->willReturn($config)
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getRepository')
            ->with($this->equalTo(Config::class))
            ->willReturn($configRepository)
        ;

        $configService = new ConfigService($entityManager);
        $this->assertSame('Test', $configService->get('Key'));
    }

    /**
     * @covers \App\Service\ConfigService::get
     */
    public function test_throw_exception_when_key_does_not_exist(): void
    {
        $configRepository = $this->createMock(ConfigRepository::class);
        $configRepository
            ->method('findOneBy')
            ->with(['key' => 'Key'])
            ->willReturn(null)
        ;

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager
            ->method('getRepository')
            ->with($this->equalTo(Config::class))
            ->willReturn($configRepository)
        ;

        $configService = new ConfigService($entityManager);

        $this->expectException(Exception::class);
        $this->expectExceptionMessage("Config with key 'Key' not found in the database");

        $configService->get('Key');
    }
}
