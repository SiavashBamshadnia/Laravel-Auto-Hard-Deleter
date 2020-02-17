<?php

return [
    /*
   |--------------------------------------------------------------------------
   | Auto Hard Delete After
   |--------------------------------------------------------------------------
   |
   | This means that if you don't specify the 'time interval' known internally as AUTO_HARD_DELETE_AFTER
   | in your models, the soft deleted models will be hard deleted after the time passes.
   |
   */

    'auto_hard_delete_after' => env('AUTO_HARD_DELETE_AFTER', '60 days'),
];
