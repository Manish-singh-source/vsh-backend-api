<?php

namespace App\Observers;

use App\Models\Visitor;
use App\Jobs\IndexVisitorFace;

class VisitorObserver
{
    public function created(Visitor $visitor)
    {
        // queue a job to detect/crop/index the visitor face so creation remains fast
        if (! $visitor->image_path) {
            return;
        }

        IndexVisitorFace::dispatch($visitor);
    }
}
