@extends('core::layouts.master')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <h1 class="page-header">Welcome</h1>

            <p>
                This is a demo application to show you what <strong>Event Sourcing</strong> and <strong>CQRS</strong> can do for you. Prepare to be blown away.
            </p>
            <p>
                If you're new to <strong>Event Sourcing</strong> or <strong>CQRS</strong>, I recommend you read my article on <a
                        href="http://nicolaswidart.com/blog/get-up-and-running-with-event-sourcing" target="_blank">Get up and running with Event Sourcing</a>.
            </p>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <h2>Examples</h2>
        </div>
    </div>
    <div class="row">
        <div class="col-md-6">
            <a href="{{ route('parts.index') }}" class="btn btn-primary btn-block">
                Parts with child entity
            </a>
        </div>
        <div class="col-md-6">
            <a href="" class="btn btn-primary btn-block" disabled>
                Basket Example
            </a>
        </div>
    </div>
@stop
