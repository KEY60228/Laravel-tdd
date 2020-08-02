<?php

namespace Tests\Unit\Models;

use App\Models\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
  public function testCanReserve()
  {
    $user = new User();

    $user->plan = 'regular';
    $remainingCount = 1;
    $reservationCount = 4;
    $this->assertTrue($user->canReserve($remainingCount, $reservationCount));

    $user->plan = 'regular';
    $remainingCount = 1;
    $reservationCount = 5;
    $this->assertFalse($user->canReserve($remainingCount, $reservationCount));
  }
}
