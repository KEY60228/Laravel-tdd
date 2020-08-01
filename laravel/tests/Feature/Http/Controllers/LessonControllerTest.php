<?php

namespace Tests\Feature\Http\Controllers;

use App\Models\Lesson;
use App\Models\User;
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
   * @dataProvider dataShow
   */
  public function testShow(int $capacity, int $reservationCount, string $expectedVacancyLevelMark)
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

  public function dataShow()
  {
    return [
      '空き状況なし' => [
        'capacity' => 10,
        'reservationCount' => 10,
        'expectedVacancyLevelMark' => '×',
      ],
      '残りわずか' => [
        'capacity' => 10,
        'reservationCount' => 7,
        'expectedVacancyLevelMark' => '△',
      ],
      '空き十分' => [
        'capacity' => 10,
        'reservationCount' => 5,
        'expectedVacancyLevelMark' => '◎',
      ],
    ];
  }
}
