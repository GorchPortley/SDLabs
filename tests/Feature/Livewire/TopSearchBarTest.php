<?php

namespace Tests\Feature\Livewire;

use App\Http\Livewire\TopSearchBar;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Livewire\Livewire;
use Tests\TestCase;

class TopSearchBarTest extends TestCase
{
    /** @test */
    public function renders_successfully()
    {
        Livewire::test(TopSearchBar::class)
            ->assertStatus(200);
    }
}
