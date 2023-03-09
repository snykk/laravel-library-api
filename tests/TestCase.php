<?php

namespace Tests;

use App\Providers\CmsAuthServiceProvider;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Http\Testing\File;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Setup the test environment.
     *
     * return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->app->register(CmsAuthServiceProvider::class);
    }

    /**
     * Fake any media file using the given filename and
     * based on the given dummy source path.
     *
     * @param string $filename
     * @param string $dummySource
     *
     * @return File
     */
    protected function fakeMedia(string $filename, string $dummySource): File
    {
        $tmpFile = tap(tmpfile(), static function ($temp) use ($dummySource) {
            fwrite($temp, file_get_contents(public_path($dummySource)));
        });

        return new File($filename, $tmpFile);
    }

    /**
     * Get any protected / private property value.
     *
     * @param mixed  $object
     * @param string $propertyName
     *
     * @throws \ReflectionException If no property exists by that name.
     *
     * @return mixed
     */
    public function getPropertyValue($object, $propertyName)
    {
        $reflection = new \ReflectionClass(get_class($object));
        $property = $reflection->getProperty($propertyName);
        $property->setAccessible(true);

        return $property->getValue($object);
    }

    /**
     * Invoke protected / private method of the given object.
     *
     * @param object $object
     * @param string $methodName
     * @param array  $parameters
     *
     * @throws \ReflectionException if the method does not exist.
     *
     * @return mixed
     */
    protected function invokeMethod($object, $methodName, array $parameters = [])
    {
        $reflection = new \ReflectionClass(get_class($object));
        $method = $reflection->getMethod($methodName);
        $method->setAccessible(true);

        return $method->invokeArgs($object, $parameters);
    }
}
