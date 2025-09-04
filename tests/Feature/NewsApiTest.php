<?php

namespace Tests\Feature;

use App\Models\User;
use App\Models\News;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class NewsApiTest extends TestCase
{
    use RefreshDatabase;

    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
        $this->user = User::factory()->create();
    }

    public function test_can_get_news_list()
    {
        News::factory()->count(3)->create();

        $response = $this->actingAs($this->user)->get('/api/news');

        $response->assertStatus(200);
        $response->assertJsonCount(3, 'data');
    }

    public function test_can_get_news_detail()
    {
        $news = News::factory()->create([
            'title' => 'Test News',
            'content' => 'Test content',
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->get("/api/news/{$news->id}");

        $response->assertStatus(200);
        $response->assertJsonPath('title', 'Test News');
        $response->assertJsonPath('content', 'Test content');
    }

    public function test_can_create_news()
    {
        $data = [
            'title' => 'New News Item',
            'content' => 'News content here',
        ];

        $response = $this->actingAs($this->user)->post('/api/news', $data);

        $response->assertStatus(201);
        $response->assertJsonPath('title', 'New News Item');
        $this->assertDatabaseHas('news', ['title' => 'New News Item']);
    }

    public function test_can_update_news()
    {
        $news = News::factory()->create([
            'title' => 'Original Title',
            'content' => 'Original content',
            'user_id' => $this->user->id
        ]);

        $data = [
            'title' => 'Updated Title',
            'content' => 'Updated content',
        ];

        $response = $this->actingAs($this->user)->put("/api/news/{$news->id}", $data);

        $response->assertStatus(200);
        $response->assertJsonPath('title', 'Updated Title');
        $this->assertDatabaseHas('news', ['title' => 'Updated Title']);
    }

    public function test_can_delete_news()
    {
        $news = News::factory()->create([
            'user_id' => $this->user->id
        ]);

        $response = $this->actingAs($this->user)->delete("/api/news/{$news->id}");

        $response->assertStatus(204);
        $this->assertDatabaseMissing('news', ['id' => $news->id]);
    }

    public function test_can_upload_news_image()
    {
        Storage::fake('public');
        
        $news = News::factory()->create([
            'user_id' => $this->user->id
        ]);

        $image = UploadedFile::fake()->image('news-image.jpg');

        $response = $this->actingAs($this->user)->post("/api/news/{$news->id}/image", [
            'image' => $image,
        ]);

        $response->assertStatus(200);
        $response->assertJsonStructure(['image_url']);
        Storage::disk('public')->assertExists('images/news-image.jpg');
    }
}