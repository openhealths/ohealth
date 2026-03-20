<?php

declare(strict_types=1);

namespace Tests\Unit\Exceptions\EHealth;

use App\Exceptions\EHealth\EHealthResponseException;
use Illuminate\Http\Client\Response;
use Mockery;
use Tests\TestCase;

class EHealthResponseExceptionTest extends TestCase
{
    public function test_it_has_get_translated_message_method(): void
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('status')->andReturn(400);
        $response->shouldReceive('json')->with('error.message')->andReturn('Some error');
        $response->shouldReceive('reason')->andReturn('Bad Request');

        $exception = new EHealthResponseException($response);

        $this->assertTrue(method_exists($exception, 'getTranslatedMessage'));
        $this->assertEquals($exception->getMessage(), $exception->getTranslatedMessage());
        $this->assertStringContainsString('Some error', $exception->getTranslatedMessage());
    }

    public function test_it_handles_invalid_signature_translation(): void
    {
        $response = Mockery::mock(Response::class);
        $response->shouldReceive('status')->andReturn(400);
        $response->shouldReceive('json')->with('error.message')->andReturn('Invalid signature');
        $response->shouldReceive('reason')->andReturn('Bad Request');

        $exception = new EHealthResponseException($response);

        $this->assertEquals(__('forms.invalid_kep_password'), $exception->getTranslatedMessage());
    }
}
