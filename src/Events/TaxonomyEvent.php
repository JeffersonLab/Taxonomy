<?php
/**
 * Created by PhpStorm.
 * User: theo
 * Date: 5/22/18
 * Time: 2:44 PM
 */

namespace Jlab\Taxonomy\Events;


use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Auth;

class TaxonomyEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;


    public $model;
    public $user;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Model $model)
    {
        $this->model = $model;
        if (Auth::user()){
            $this->user = Auth::user();
        }
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
