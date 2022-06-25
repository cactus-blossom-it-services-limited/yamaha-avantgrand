<?php

namespace Tests\Feature;

use App\Models\Book;
// use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class BookReservationTest extends TestCase
{
    // use RefreshDatabase;
    use DatabaseMigrations;

    /** @test */
    public function a_book_can_be_added_to_the_library()
    {
        $this->withoutExceptionHandling();

        $response = $this->post('/books', [
            'title' => 'Cool Book Title',
            'author' => 'Victor',
        ]);

        $response->assertOk();
        $this->assertCount(1, Book::all());
    }

        /** @test */
        public function a_title_is_required()
        {
    
            $response = $this->post('/books', [
                'title' => '',
                'author' => 'Victor',
            ]);
    
            $response->assertSessionHasErrors('title');
        }

        /** @test */
        public function a_author_is_required()
        {
    
            $response = $this->post('/books', [
                'title' => 'Cool Title',
                'author' => '',
            ]);
    
            $response->assertSessionHasErrors('author');
        }
}
