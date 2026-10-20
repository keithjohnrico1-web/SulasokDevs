<?php

function is_admin($dept)
{
    return in_array(strtolower((string) $dept), ['admin', 'administration', 'it', 'hr']);
}

function role($dept)
{
    $d = strtolower((string) $dept);
    if (is_admin($d)) return 'admin';
    return $d === 'dispatch' ? 'dispatch' : 'tech';
}