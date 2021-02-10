<?php

namespace ForwardForce\Daxko;

use ForwardForce\Daxko\Traits\Pagable;
use ForwardForce\Daxko\Traits\Parametarable;
use GuzzleHttp\Client as GuzzleHttpClient;


class Client extends GuzzleHttpClient
{
    use Pagable;
    use Parametarable;
}

