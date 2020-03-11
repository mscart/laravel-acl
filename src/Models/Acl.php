<?php

namespace MsCart\Acl;


use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Config;

class Acl extends Model
{
  use LogsActivity;

  protected $fillable = [
    'name',
];
  protected static $logAttributes = ['name'];

}
