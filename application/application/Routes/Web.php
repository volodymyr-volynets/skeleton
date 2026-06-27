<?php

\Route::uri('Home', '/', 'Index', ['GET'], [\Controller\Index::class, 'Index'])
    ->acl('Public');
