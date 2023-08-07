@extends('layouts.guest')

@section('content')
<h1 class="text-4xl text-center font-base text-primary-blue">Authorization Request</h1>


<div class="max-w-lg px-8 mx-auto">
    <div class="mt-16">
        <!-- Introduction -->
        <p><strong>{{ $client->name }}</strong> is requesting permission to access your account.</p>

        <!-- Scope List -->
        @if (count($scopes) > 0)
            <div class="scopes">
                    <p><strong>This application will be able to:</strong></p>

                    <ul>
                        @foreach ($scopes as $scope)
                            <li>{{ $scope->description }}</li>
                        @endforeach
                    </ul>
            </div>
        @endif

        <div class="flex items-center justify-center gap-6 mt-8">
            <!-- Authorize Button -->
            <form method="post" action="{{ route('passport.authorizations.approve') }}">
                @csrf

                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button type="submit" class="btn btn-primary btn-approve">Authorize</button>
            </form>

            <!-- Cancel Button -->
            <form method="post" action="{{ route('passport.authorizations.deny') }}">
                @csrf
                @method('DELETE')

                <input type="hidden" name="state" value="{{ $request->state }}">
                <input type="hidden" name="client_id" value="{{ $client->getKey() }}">
                <input type="hidden" name="auth_token" value="{{ $authToken }}">
                <button class="underline">Cancel</button>
            </form>
        </div>
    </div>
</div>


@endsection