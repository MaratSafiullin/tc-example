<?php

namespace App\ModesStates\Set\Transitions;

use App\Jobs\FakeTc;
use App\Models\Set;
use App\ModesStates\Set\Processing;
use Spatie\ModelStates\Transition;

class ToProcessing extends Transition
{
    public function __construct(private readonly Set $set)
    {
    }

    public function handle(): Set
    {
        $this->set->status = new Processing($this->set);
        $this->set->save();

        if (config('tc.fake')) {
            dispatch(new FakeTc($this->set));

            return $this->set;
        }

        //TODO: Implement external service call for processing

        return $this->set;
    }
}
