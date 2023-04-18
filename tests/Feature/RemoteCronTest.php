<?php

namespace WinLocal\RemoteCron\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use WinLocal\RemoteCron\Tests\TestCase;

class RemoteCronTest extends TestCase
{
    public const TestCommand = 'test:test';

    public function testRunCronSuccess()
    {
        $this->createTestCommand(' {info}');
        $token = uniqid();
        config(['remotecron.token' => $token]);

        $response = $this->json('get', 'remote/cron', [
            'token' => $token,
            'command' => self::TestCommand,
            'parameters' => json_encode([
                'info' => 'default',
            ]),
        ]);

        $response->assertStatus(200);
        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertJsonStringEqualsJsonString('{"message":"Command is Running"}', $content);
    }

    public function testRateLimitOccur()
    {
        $this->createTestCommand();
        $token = uniqid();
        config(['remotecron.token' => $token]);

        $times = 2;
        $response = null;
        do {
            $response = $this->json('get', 'remote/cron', [
                'token' => $token,
                'command' => self::TestCommand,
            ]);
        } while ($times-- > 0);

        $response->assertStatus(429);
        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertStringContainsString('"message": "Too Many Attempts.",', $content);
    }

    protected function createTestCommand(string $params = '')
    {
        Artisan::command(self::TestCommand.$params, fn () => '');
    }
}
