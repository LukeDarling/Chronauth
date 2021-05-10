<?php
// Script written by Luke Darling.
// All rights reserved.

function chronauth_generate(string $publicSalt, string $privateSalt, int $time = null) {
    if(is_null($time)) {
        $time = time();
    }
    return strtoupper(hash("whirlpool", "\$Chronauth\$v1.0\$$privateSalt\$$time\$$publicSalt\$"));
}

function chronauth_verify(string $publicSalt, string $privateSalt, string $hash, int $time = null, int $tolerance = 4) {
    if(is_null($time)) {
        $time = time();
    }
    $toleratedHashes = [chronauth_generate($publicSalt, $privateSalt, $time)];
    $tolerance = abs($tolerance);
    for($i = 1; $i < $tolerance; $i++) {
        $toleratedHashes[] = chronauth_generate($publicSalt, $privateSalt, $time + $i);
        $toleratedHashes[] = chronauth_generate($publicSalt, $privateSalt, $time - $i);
    }
    return in_array($hash, $toleratedHashes);
}
