<?php

use core\session;
view('sessions/create.php',[
    'errors'=>session::get('errors'),
]);