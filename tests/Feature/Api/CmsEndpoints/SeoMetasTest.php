<?php

namespace Tests\Feature\Api\CmsEndpoints;

use App\Models\CmsAdmin;
use App\Models\SeoMeta;
use Faker\Generator;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Http\UploadedFile;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Tests\TestCase;

class SeoMetasTest extends TestCase
{
    use DatabaseMigrations;

    /**
     * Currently logged in Cms Admin.
     *
     * @var CmsAdmin
     */
    protected $cmsAdmin;

    /**
     * Current endpoint url which being tested.
     *
     * @var string
     */
    protected $endpoint = '/cms-api/seo_metas/';

    /**
     * Faker generator instance.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * The model which being tested.
     *
     * @var SeoMeta
     */
    protected $model;

    /**
     * Setup the test environment.
     *
     * return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->seed(['PermissionSeeder', 'RoleSeeder']);

        $this->faker = new Generator();
        $this->cmsAdmin = CmsAdmin::factory()->create()->assignRole('super-administrator');

        $this->actingAs($this->cmsAdmin, config('api.cms_guard'));

        SeoMeta::factory()->count(10)->create();
        $this->model = SeoMeta::findOrFail(5);
    }

    /** @test */
    public function index_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint)
            ->assertStatus(200)
            ->assertJsonFragment([
                'seo_url'         => $this->model->getAttribute('seo_url'),
                'model'           => $this->model->getAttribute('model'),
                'foreign_key'     => $this->model->getAttribute('foreign_key'),
                'locale'          => $this->model->getAttribute('locale'),
                'seo_title'       => $this->model->getAttribute('seo_title'),
                'seo_description' => $this->model->getAttribute('seo_description'),
                'open_graph_type' => $this->model->getAttribute('open_graph_type'),
            ]);
    }

    /** @test */
    public function show_endpoint_works_as_expected()
    {
        $this->getJson($this->endpoint.$this->model->getKey())
            ->assertStatus(200)
            ->assertJsonFragment([
                'seo_url'         => $this->model->getAttribute('seo_url'),
                'model'           => $this->model->getAttribute('model'),
                'foreign_key'     => $this->model->getAttribute('foreign_key'),
                'locale'          => $this->model->getAttribute('locale'),
                'seo_title'       => $this->model->getAttribute('seo_title'),
                'seo_description' => $this->model->getAttribute('seo_description'),
                'open_graph_type' => $this->model->getAttribute('open_graph_type'),
            ]);
    }

    /** @test */
    public function create_endpoint_works_as_expected_with_url_and_with_image()
    {
        \Storage::fake('public');

        $fakeImage = UploadedFile::fake()
            ->image('user.jpg', 1200, 630)
            ->size(378);

        // Submitted data
        $data = SeoMeta::factory()->raw([
            'seo_image' => $fakeImage,
        ]);

        // The data which should be shown
        $seenData = $data;
        unset($seenData['seo_image']);

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);

        $seoMeta = SeoMeta::orderBy('id', 'desc')->first();
        $media = $seoMeta->getFirstMedia('seo-image');

        $this->assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        \Storage::disk('public')->assertExists($path);
    }

    /** @test */
    public function create_endpoint_works_as_expected_with_url_and_without_image()
    {
        // Submitted data
        $data = SeoMeta::factory()->raw();

        // The data which should be shown
        $seenData = $data;

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function create_endpoint_works_as_expected_with_model_and_with_image()
    {
        \Storage::fake('public');

        $fakeImage = UploadedFile::fake()
            ->image('user.jpg', 1200, 630)
            ->size(378);

        // Submitted data
        $data = SeoMeta::factory()->raw([
            'seo_url'     => null,
            'model'       => get_class($this->cmsAdmin),
            'foreign_key' => $this->cmsAdmin->getKey(),
            'locale'      => 'en',
            'seo_image'   => $fakeImage,
        ]);

        // The data which should be shown
        $seenData = $data;
        unset($seenData['seo_image']);

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);

        $seoMeta = SeoMeta::orderBy('id', 'desc')->first();
        $media = $seoMeta->getFirstMedia('seo-image');

        $this->assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        \Storage::disk('public')->assertExists($path);
    }

    /** @test */
    public function create_endpoint_works_as_expected_with_model_and_without_image()
    {
        // Submitted data
        $data = SeoMeta::factory()->raw([
            'seo_url'     => null,
            'model'       => get_class($this->cmsAdmin),
            'foreign_key' => $this->cmsAdmin->getKey(),
            'locale'      => 'en',
        ]);

        // The data which should be shown
        $seenData = $data;

        $this->postJson($this->endpoint, $data)
            ->assertStatus(201)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function update_endpoint_works_as_expected_with_url_and_with_image()
    {
        \Storage::fake('public');

        $fakeImage = UploadedFile::fake()
            ->image('user.jpg', 1200, 630)
            ->size(378);

        // Submitted data
        $data = SeoMeta::factory()->raw([
            'seo_image' => $fakeImage,
        ]);

        // The data which should be shown
        $seenData = $data;
        unset($seenData['seo_image']);

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);

        $this->model->refresh();
        $media = $this->model->getFirstMedia('seo-image');

        $this->assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        \Storage::disk('public')->assertExists($path);
    }

    /** @test */
    public function update_endpoint_works_as_expected_with_url_and_without_image()
    {
        // Submitted data
        $data = SeoMeta::factory()->raw();

        // The data which should be shown
        $seenData = $data;

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);
    }

    /** @test */
    public function update_endpoint_works_as_expected_with_model_and_with_image()
    {
        \Storage::fake('public');

        $fakeImage = UploadedFile::fake()
            ->image('user.jpg', 1200, 630)
            ->size(378);

        // Submitted data
        $data = SeoMeta::factory()->raw([
            'seo_url'     => null,
            'model'       => get_class($this->cmsAdmin),
            'foreign_key' => $this->cmsAdmin->getKey(),
            'locale'      => 'en',
            'seo_image'   => $fakeImage,
        ]);

        // The data which should be shown
        $seenData = $data;
        unset($seenData['seo_image']);

        $this->patchJson($this->endpoint.$this->model->getKey(), $data)
            ->assertStatus(200)
            ->assertJsonFragment($seenData);

        $this->model->refresh();
        $media = $this->model->getFirstMedia('seo-image');

        $this->assertInstanceOf(Media::class, $media);

        $path = str_replace(dirname(\Storage::disk('public')->path('test')), '', $media->getPath());
        \Storage::disk('public')->assertExists($path);
    }

    public function update_endpoint_works_as_expected_with_model_and_without_image()
    {
        // Submitted data
        $data = SeoMeta::factory()->raw([
            'seo_url'     => null,
            'model'       => get_class($this->cmsAdmin),
            'foreign_key' => $this->cmsAdmin->getKey(),
        ]);

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
                'info' => 'The seo meta has been deleted.',
            ]);

        $this->assertDatabaseMissing('cms_admins', [
            'seo_url'         => $this->model->getAttribute('seo_url'),
            'model'           => $this->model->getAttribute('model'),
            'foreign_key'     => $this->model->getAttribute('foreign_key'),
            'locale'          => $this->model->getAttribute('locale'),
            'seo_title'       => $this->model->getAttribute('seo_title'),
            'seo_description' => $this->model->getAttribute('seo_description'),
            'open_graph_type' => $this->model->getAttribute('open_graph_type'),
            'deleted_at'      => null,
        ]);
    }
}
