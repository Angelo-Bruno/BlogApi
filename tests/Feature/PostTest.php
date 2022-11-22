<?php

namespace Tests\Feature;

use App\Http\Resources\PostResource;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class PostTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic test example.
     *
     * @return void
     */



    public function test_auth_user_can_create_post()
    {
        $user = User::factory()->john_doe()->create();

        $this->actingAs($user, "sanctum")
            ->post("http://127.0.0.1:8000/api/post", [
                "title" => "Hello from figma",
                "content" => "lorem ipsum"
            ])
            ->assertSee("author_id")
            ->assertStatus(200);
    }

    public function test_user_not_auth_cannot_create_post()
    {

        $this->withHeaders(['Accept' => 'application/json'])
            ->post("http://127.0.0.1:8000/api/post", [
                "title" => "Hello from figma",
                "content" => "lorem ipsum"
            ])
            ->assertStatus(401);
    }

    public function test_post_has_slug()
    {
        $post = Post::factory()->create(['title' => 'The Empire Strikes Back']);
        $this->assertEquals($post->slug, 'the-empire-strikes-back');
    }

    public function test_not_author_cannot_edit_post()
    {
        $user = User::factory()->john_doe()->create();
        $user2 = User::factory()->create();
        $post = Post::factory()->create(["author_id" => $user2->id]);


        $this->withHeaders(['Accept' => 'application/json'])
            ->actingAs($user, "sanctum")
            ->json(
                "put",
                "http://127.0.0.1:8000/api/post/edit/{$post->id}",
                ["title" => "Hello from figma 2"]
            )
            ->assertForbidden()
            ->assertJson([
                'message' => 'This action is unauthorized.'
            ]);
    }
}