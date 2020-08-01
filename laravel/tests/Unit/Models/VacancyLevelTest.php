<?php

namespace Tests\Unit\Models;

use PHPUnit\Framework\TestCase;
use App\Models\VacancyLevel;

class VacancyLevelTest extends TestCase
{
  /**
   * @param int $remainingCount
   * @param string $expectedMark
   * @param string $expectedSlug
   * @dataProvider dataMark
   */
  public function testMarkAndSlug(int $remainingCount, string $expectedMark, string $expectedSlug)
  {
    $level = new VacancyLevel($remainingCount);
    $this->assertSame($expectedMark, $level->mark());
    $this->assertSame($expectedSlug, $level->slug());
  }

  public function dataMark()
  {
    return [
      '空きなし' => [
        'remainingCount' => 0,
        'expectedMark' => '×',
        'expectedSlug' => 'empty',
      ],
      '残りわずか' => [
        'remainingCount' => 4,
        'expectedMark' => '△',
        'expectedSlug' => 'few',
      ],
      '空き十分' => [
        'remainingCount' => 5,
        'expectedMark' => '◎',
        'expectedSlug' => 'enough',
      ],
    ];
  }
}
