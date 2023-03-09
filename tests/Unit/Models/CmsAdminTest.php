<?php

namespace Tests\Unit\Models;

use App\Models\CmsAdmin;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class CmsAdminTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Cms Admin instance.
     *
     * @var CmsAdmin
     */
    protected $cmsAdmin;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->cmsAdmin = CmsAdmin::factory()->create();
    }

    /** @test */
    public function it_can_append_profile_picture_attribute()
    {
        $data = $this->cmsAdmin->append(['medium_profile_picture', 'small_profile_picture'])->toArray();

        $this->assertEmpty($data['medium_profile_picture']);
        $this->assertEmpty($data['small_profile_picture']);
    }
}
