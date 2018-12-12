@extends('layouts.mst_errors')
@section('title', '404 Error - Oops.. Page not found!')
@section('error_number', '404')
@section('error_title', 'Sorry but we couldn\'t find this page')
@section('error_message')
    The page you are looking for might have been removed, had its name changed or is temporarily unavailable.
    <a href="javascript:void(0)">That's all we know.</a>
@endsection