@extends('errors.layout', [
    'title' => 'Too Many Requests',
    'code' => '429',
    'message' => 'You are making too many requests. Please slow down and try again later.'
])
