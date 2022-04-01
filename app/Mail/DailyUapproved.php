<?php

namespace App\Mail;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class DailyUapproved extends Mailable
{
    use Queueable, SerializesModels;
    public $teachers, $students;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->teachers = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'teacher');
            }
        )->where('status', 0)->get();

        $this->students = User::whereHas(
            'roles',
            function ($q) {
                $q->where('name', 'student');
            }
        )->where('status', 0)->get();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.unapproved-daily');
    }
}
