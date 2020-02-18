<?php


namespace sbamtr\LaravelAutoHardDeleter\Tests\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SampleModel extends Model
{
    public const CREATED_AT = null;
    public const UPDATED_AT = null;

    use SoftDeletes;
    const DELETED_AT = 'del';

    const AUTO_HARD_DELETE_ENABLED = true;
    const AUTO_HARD_DELETE_AFTER = '15 days';
}