<?php

declare(strict_types=1);

if (preg_match('/^\/admin(\/|$)/', $_SERVER['REQUEST_URI'])) {
    require_once __DIR__ . '/admin.php';
} else {
    require_once __DIR__ . '/website.php';
}
