<?php

namespace App\Http\Controllers\Lesson;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lesson;

class ReserveController extends Controller
{
    public function __invoke(Lesson $lesson)
    {
      // ToDo: 予約

      return redirect()->route('lessons.show', ['lesson' => $lesson]);
    }
}
