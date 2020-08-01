<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
  use RefreshDatabase;


  public function testShow()
  {
    $lesson = factory(Lesson::class)->create(['name' => '楽しいヨガレッスン', 'capacity' => $capacity]);
    for ($i = 0; $i < $reservationCount; $i++) {
      $user = factory(User::class)->create();
      $lesson->reservations()->save(factory(Reservation::class))->make(['user_id' => $user]);
    }
    $response = $this->get("/lessons/{$lesson->id}");

    $response->assertStatus(Response::HTTP_OK);
    $response->assertSee($lesson->name);
    $response->assertSee('空き状況: $expectedVacancyLevelMark');
  }

}
