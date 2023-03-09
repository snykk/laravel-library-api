<?php

namespace Tests\Feature\Api\CmsEndpoints;

use App\Models\CmsAdmin;
use App\Models\Book;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BooksTest extends TestCase
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
    protected $endpoint = '/cms-api/books/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var Book
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

        $this->model = Book::factory()->create();
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => $this->model->getAttribute('title'),
                'description' => $this->model->getAttribute('description'),
                'rating' => $this->model->getAttribute('rating'),
                'author_id' => $this->model->getAttribute('author_id'),
                'publisher_id' => $this->model->getAttribute('publisher_id'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'title' => $this->model->getAttribute('title'),
                'description' => $this->model->getAttribute('description'),
                'rating' => $this->model->getAttribute('rating'),
                'author_id' => $this->model->getAttribute('author_id'),
                'publisher_id' => $this->model->getAttribute('publisher_id'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected()
    {
        // Submitted data
        $data = Book::factory()->raw();

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
        $data = Book::factory()->raw();

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
                'info' => 'The book has been deleted.',
            ]);

        $this->assertDatabaseHas('books', [
            'title' => $this->model->getAttribute('title'),
            'description' => $this->model->getAttribute('description'),
            'rating' => $this->model->getAttribute('rating'),
            'author_id' => $this->model->getAttribute('author_id'),
            'publisher_id' => $this->model->getAttribute('publisher_id'),
        ]);

        $this->assertDatabaseMissing('books', [
            'title' => $this->model->getAttribute('title'),
            'description' => $this->model->getAttribute('description'),
            'rating' => $this->model->getAttribute('rating'),
            'author_id' => $this->model->getAttribute('author_id'),
            'publisher_id' => $this->model->getAttribute('publisher_id'),
            'deleted_at' => null,
        ]);
    }
}
