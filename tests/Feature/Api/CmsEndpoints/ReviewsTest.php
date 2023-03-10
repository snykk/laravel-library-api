<?php

namespace Tests\Feature\Api\CmsEndpoints;

use App\Models\CmsAdmin;
use App\Models\Review;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class ReviewsTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Currently logged in CMS Admin.
     *
     * @var CmsAdmin
     */
    protected $cmsAdmin;

    /**
     * Current endpoint url which being tested.
     *
     * @var string
     */
    protected $endpoint = '/cms-api/reviews/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Review
     */
    protected $model;

    /**
     * Setup the test environment.
     *
     * return void
     */
    public function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->faker = new Generator();
        $this->cmsAdmin = CmsAdmin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));

        $this->model = Review::factory()->create();
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'comment' => $this->model->getAttribute('comment'),
                'rating' => $this->model->getAttribute('rating'),
                'user_id' => $this->model->getAttribute('user_id'),
                'book_id' => $this->model->getAttribute('book_id'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'comment' => $this->model->getAttribute('comment'),
                'rating' => $this->model->getAttribute('rating'),
                'user_id' => $this->model->getAttribute('user_id'),
                'book_id' => $this->model->getAttribute('book_id'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected()
    {
        // Submitted data
        $data = Review::factory()->raw();

        // The data which should be shown
        $seenData = $data;

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function update_endpoint_works_as_expected()
    {
        // Submitted data
        $data = Review::factory()->raw();

        // The data which should be shown
        $seenData = $data;

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function delete_endpoint_works_as_expected()
    {
        $this->deleteJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'info' => 'The review has been deleted.',
            ]);

        $this->assertDatabaseHas('reviews', [
            'comment' => $this->model->getAttribute('comment'),
            'rating' => $this->model->getAttribute('rating'),
            'user_id' => $this->model->getAttribute('user_id'),
            'book_id' => $this->model->getAttribute('book_id'),
        ]);

        $this->assertDatabaseMissing('reviews', [
            'comment' => $this->model->getAttribute('comment'),
            'rating' => $this->model->getAttribute('rating'),
            'user_id' => $this->model->getAttribute('user_id'),
            'book_id' => $this->model->getAttribute('book_id'),
            'deleted_at' => null,
        ]);
    }
}
