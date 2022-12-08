<?php
require 'vendor/autoload.php';

use App\Models\Source as Source;

$source = new Source();
$source->show();
