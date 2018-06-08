<?php

namespace App\Mail;

use App\Models\Members;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class OrderShipped extends Mailable
{
    use Queueable, SerializesModels;
    public $members;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->members = $members;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
       //
    }
}
