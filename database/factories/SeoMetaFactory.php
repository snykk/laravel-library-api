<?php

namespace Database\Factories;

use App\Models\SeoMeta;
use Illuminate\Database\Eloquent\Factories\Factory;

class SeoMetaFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SeoMeta::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'seo_url'         => '/'.$this->faker->slug(2).'/'.$this->faker->slug(4),
            'model'           => null,
            'foreign_key'     => null,
            'locale'          => null,
            'seo_title'       => $this->faker->sentence(4),
            'seo_description' => $this->faker->paragraph(1),
            'open_graph_type' => $this->faker->randomElement(['article', 'website']),
        ];
    }
}
