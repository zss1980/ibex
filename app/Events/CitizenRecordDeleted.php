<?php

namespace App\Events;

use App\Citizen;
use App\Events\Event;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class CitizenRecordDeleted extends Event implements ShouldBroadcast
{
    use SerializesModels;

    public $id;
    public $firstName;
    public $lastName;
    public $sex;
    public $age;
    public $phone;
    public $email;
    public $created_at;
    public $updated_at;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Citizen $record)
    {
        
        $this->id = $record->id;
        $this->firstName = $record->firstName;
        $this->lastName = $record->lastName;
        $this->sex = $record->sex;
        $this->age = $record->age;
        $this->phone = $record->phone;
        $this->email = $record->email;
        $this->created_at = $record->created_at;
        $this->updated_at = $record->updated_at;

        
    }

    /**
     * Get the channels the event should be broadcast on.
     *
     * @return array
     */
    public function broadcastOn()
    {
        return ['RecordChanges'];
    }
}
