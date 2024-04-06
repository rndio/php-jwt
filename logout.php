<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/src/Auth.php';

Auth::logout();
header('Location: login.php');
