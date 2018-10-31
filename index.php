<?php

require 'vendor/autoload.php';

echo "Hello World!\n";

$flagger = new Flagger\Flagger(new Flagger\Client\GuzzleClient('<env_key>'));
print($flagger->flag('<flag_name>')->isEnabled());
