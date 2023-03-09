<?php

namespace Tests\Unit\Exceptions\Concerns;

use App\Exceptions\Handler;
use ErrorException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Tests\TestCase;

class HandleApiExceptionsTest extends TestCase
{
    /**
     * @var Handler
     */
    protected $handler;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->handler = app(Handler::class);
    }

    /** @test */
    public function it_returns_correct_error_code_for_validation_exception()
    {
        $exception = new ValidationException(null);

        $actual = $this->invokeMethod($this->handler, 'getApiErrorCode', [$exception]);

        $this->assertEquals(422, $actual);
    }

    /** @test */
    public function it_returns_correct_error_code_for_model_not_found_exception()
    {
        $exception = new ModelNotFoundException();

        $actual = $this->invokeMethod($this->handler, 'getApiErrorCode', [$exception]);

        $this->assertEquals(404, $actual);
    }

    /** @test */
    public function it_returns_correct_error_code_for_http_exception()
    {
        $exception = new HttpException(403, 'Access Forbidden');

        $actual = $this->invokeMethod($this->handler, 'getApiErrorCode', [$exception]);

        $this->assertEquals(403, $actual);
    }

    /** @test */
    public function it_returns_correct_error_code_for_other_exceptions()
    {
        $exception = new ErrorException('Oops, Something went wrong.');

        $actual = $this->invokeMethod($this->handler, 'getApiErrorCode', [$exception]);

        $this->assertEquals(500, $actual);
    }

    /** @test */
    public function it_can_convert_exception_content_to_array_with_debug_enabled()
    {
        config(['app.debug' => true]);

        $exception = new ErrorException('Oops, Something went wrong.');
        $actual = $this->invokeMethod($this->handler, 'convertApiExceptionToArray', [$exception]);

        $this->assertEquals(500, $actual['code']);
        $this->assertEquals('Oops, Something went wrong.', $actual['message']);
        $this->assertEquals([], $actual['errors']);
        $this->assertEquals('ErrorException', $actual['exception']);
        $this->assertEquals(__FILE__, $actual['file']);
    }

    /** @test */
    public function it_can_convert_exception_content_to_array_with_debug_disabled()
    {
        config(['app.debug' => false]);

        $exception = new ErrorException('Oops, Something went wrong.');
        $actual = $this->invokeMethod($this->handler, 'convertApiExceptionToArray', [$exception]);

        $this->assertEquals(500, $actual['code']);
        $this->assertEquals('Oops, Something went wrong.', $actual['message']);
        $this->assertEquals([], $actual['errors']);

        $this->assertArrayNotHasKey('exception', $actual);
        $this->assertArrayNotHasKey('file', $actual);
        $this->assertArrayNotHasKey('line', $actual);
        $this->assertArrayNotHasKey('trace', $actual);
    }

    /** @test */
    public function it_can_render_exception_into_json_response()
    {
        config(['app.debug' => false]);

        $exception = new ErrorException('Oops, Something went wrong.');
        $actual = $this->invokeMethod($this->handler, 'renderApiException', [$exception]);

        $this->assertInstanceOf(JsonResponse::class, $actual);
        $this->assertEquals('500', $actual->getData()->code);
        $this->assertEquals('Oops, Something went wrong.', $actual->getData()->message);
        $this->assertEquals([], $actual->getData()->errors);
    }
}
