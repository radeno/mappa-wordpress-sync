<?php

namespace Mappa;

class SynchronizerHelper
{
    public static function increaseExecutionTime() : void
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', -1);
    }
    public static function preRunSynchronization() : void
    {
        self::increaseExecutionTime();
        ignore_user_abort(true);
        set_time_limit(0);

        \wp_defer_term_counting(true);
        \wp_defer_comment_counting(true);
    }

    public static function afterRunSynchronization() : void
    {
        \wp_defer_term_counting(false);
        \wp_defer_comment_counting(false);
    }
}
