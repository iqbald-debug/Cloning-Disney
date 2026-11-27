<?php

namespace Tests\Feature;

use App\Models\Content;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DisneyApiTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed();
    }

    public function test_can_fetch_profiles_and_home_data(): void
    {
        $this->getJson('/api/v1/profiles')
            ->assertStatus(200)
            ->assertJsonPath('status', 'success')
            ->assertJsonCount(3, 'data');

        $this->getJson('/api/v1/home')
            ->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'data' => ['featured_banners', 'brands', 'rows'],
            ]);
    }

    public function test_polymorphic_duration_label_for_movie_and_series(): void
    {
        // Movie: 181 menit -> "3h 1m"
        $this->getJson('/api/v1/contents/avengers-endgame')
            ->assertStatus(200)
            ->assertJsonPath('data.detail.duration_label', '3h 1m');

        // Series: 2 season -> "2 Seasons"
        $this->getJson('/api/v1/contents/loki')
            ->assertStatus(200)
            ->assertJsonPath('data.detail.duration_label', '2 Seasons');
    }

    public function test_can_filter_contents_by_brand_and_search(): void
    {
        $this->getJson('/api/v1/contents?brand=marvel')
            ->assertStatus(200)
            ->assertJsonPath('status', 'success');

        $this->getJson('/api/v1/contents?search=Lion')
            ->assertStatus(200)
            ->assertJsonFragment(['title' => 'The Lion King']);
    }

    public function test_can_toggle_watchlist_in_database(): void
    {
        $profile = Profile::first();
        $content = Content::first();

        $this->postJson("/api/v1/profiles/{$profile->id}/watchlist/toggle", [
            'content_id' => $content->id,
        ])
            ->assertStatus(200)
            ->assertJsonPath('data.action', 'added');

        $this->assertDatabaseHas('watchlists', [
            'profile_id' => $profile->id,
            'content_id' => $content->id,
        ]);

        $this->postJson("/api/v1/profiles/{$profile->id}/watchlist/toggle", [
            'content_id' => $content->id,
        ])
            ->assertStatus(200)
            ->assertJsonPath('data.action', 'removed');
    }
}