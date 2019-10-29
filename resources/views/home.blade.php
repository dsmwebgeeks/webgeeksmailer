@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12" style="display: grid; grid-template-columns: 1fr 1fr 1fr; grid-gap: 1rem;">
            @if (session('status'))
            <div class="alert alert-primary" role="alert" style="grid-column: span 3">
                {{ session('status') }}
            </div>
            @endif

            @foreach ($events as $event)
            <div class="card" style="margin-bottom: 1rem;">
                <img src="{{ $event->image }}" alt="" class="card-img-top">
                <div class="card-body">
                    <h5 class="card-title">{{ $event->name }}</h5>
                    <p><code>{{ $event->id }}</code></p>
                    <p class="card-text">{{ $event->date }} at {{ $event->start }}</p>
                    {!! $event->description !!}
                    <form action="/emails/generate/{{ $event->id }}" method="post">
                        @csrf
                        <button type="submit" class="btn btn-light btn-sm">Draft Email</button>
                    </form>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>
@endsection
