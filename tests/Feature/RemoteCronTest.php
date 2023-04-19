<?php

namespace WinLocal\RemoteCron\Tests\Feature;

use Illuminate\Support\Facades\Artisan;
use WinLocal\RemoteCron\Tests\TestCase;

class RemoteCronTest extends TestCase
{
    public const TestCommand = 'test:test';

    public function testRunCronSuccess()
    {
        $this->createTestCommand('{info}');
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

    public function testRateLimitError()
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

    public function testTokenMismatchError()
    {
        $this->createTestCommand();
        $token1 = uniqid();
        $token2 = uniqid();
        config(['remotecron.token' => $token1]);

        $response = $this->json('get', 'remote/cron', [
            'token' => $token2,
            'command' => self::TestCommand,
        ]);

        $response->assertStatus(403);
        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertStringContainsString('"message": "Not Auhorized",', $content);
    }

    public function testTokenNotSetOnServerError()
    {
        $this->createTestCommand();
        $token = uniqid();

        $response = $this->json('get', 'remote/cron', [
            'token' => $token,
            'command' => self::TestCommand,
        ]);

        $response->assertStatus(403);
        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertStringContainsString('"message": "Not Auhorized",', $content);
    }

    public function testCommandNotExistsError()
    {
        $token = uniqid();
        config(['remotecron.token' => $token]);

        $response = $this->json('get', 'remote/cron', [
            'token' => $token,
            'command' => self::TestCommand,
        ]);

        $response->assertStatus(422);
        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertStringContainsString("The command test:test doesn't exists.", $content);
    }

    public function testParameterNotJsonError()
    {
        $this->createTestCommand('{info}');
        $token = uniqid();
        config(['remotecron.token' => $token]);

        $response = $this->json('get', 'remote/cron', [
            'token' => $token,
            'command' => self::TestCommand,
            'parameters' => 'Wrong Value Not Json',
        ]);

        $response->assertStatus(422);
        $content = $response->getContent();
        $this->assertJson($content);
        $this->assertStringContainsString('The parameters field must be a valid JSON string.', $content);
    }

    protected function createTestCommand(string $params = '')
    {
        Artisan::command(self::TestCommand.($params ? ' '.$params : ''), fn () => '');
    }
}
