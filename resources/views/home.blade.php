@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Hello {{ Auth::user()->first_name }} {{ Auth::user()->last_name }}! You are logged in!</div>

                <div class="card-body">
                <p>Your roles are..</p>
                <p>
                    {{ Auth::user()->roles }}
                </p>

                <p>Holidays</p>
                <p>
                    
                        
                   
                </p>
                <ul class="list-unstyled">
                    @foreach ($holidays as $hol)
                        <li class="media">
                            <div class="media-body">
                            <h5 class="mt-0 mb-1">#{{ $hol->id }} - {{ $hol->title }}</h5>
                                {{ $hol->subTitle }} <span class="badge badge-secondary">{{ \App\Image::find($hol->image_id)->path }}</span>
                            </div>
                        </li>
                    @endforeach
                </ul>

                    
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
