<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
use App\Models\Reservation;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Response;
use Tests\TestCase;

class LessonControllerTest extends TestCase
{
  use RefreshDatabase;

  /**
   * @param int $capacity
   * @param int $reservationCount
   * @param string $expectedVacancyLevelMark
   * @param string $button
   * @dataProvider dataShow
   */
  public function testShow(int $capacity, int $reservationCount, string $expectedVacancyLevelMark, string $button)
  {
    $lesson = factory(Lesson::class)->create(['name' => '楽しいヨガレッスン', 'capacity' => $capacity]);
    for ($i = 0; $i < $reservationCount; $i++) {
      $user = factory(User::class)->create();
      $lesson->reservations()->save(factory(Reservation::class)->make(['user_id' => $user->id]));
    }

    $user = factory(User::class)->create();
    $this->actingAs($user);

    $response = $this->get("/lessons/{$lesson->id}");

    $response->assertStatus(Response::HTTP_OK);
    $response->assertSee($lesson->name);
    $response->assertSee("空き状況: {$expectedVacancyLevelMark}");
    $response->assertSee($button, false);
  }

  public function dataShow()
  {
    $button = '<button class="btn btn-primary">このレッスンを予約する</button>';
    $span = '<span class="btn btn-primary disabled">予約できません</span>';

    return [
      '空き状況なし' => [
        'capacity' => 10,
        'reservationCount' => 10,
        'expectedVacancyLevelMark' => '×',
        'button' => $span,
      ],
      '残りわずか' => [
        'capacity' => 10,
        'reservationCount' => 7,
        'expectedVacancyLevelMark' => '△',
        'button' => $button,
      ],
      '空き十分' => [
        'capacity' => 10,
        'reservationCount' => 5,
        'expectedVacancyLevelMark' => '◎',
        'button' => $button,
      ],
    ];
  }
}
