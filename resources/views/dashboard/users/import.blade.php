@extends('layouts.dash')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Import Users') }}</div>

                <div class="card-body">
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    @if (isset($errors) && $errors->has('import_errors'))
                        <div class="alert alert-danger">
                            <p><strong>Import Validation Errors:</strong></p>
                            <ul>
                                @foreach ($errors->get('import_errors') as $errorMessages)
                                    @foreach ((array)$errorMessages as $message)
                                        <li>{{ $message }}</li>
                                    @endforeach
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('users.import') }}" enctype="multipart/form-data">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="file">{{ __('Excel/CSV File') }}</label>
                            <input id="file" type="file" class="form-control @error('file') is-invalid @enderror" name="file" required>
                            
                            @error('file')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <p><strong>Note:</strong> The Excel/CSV file should contain the following columns:</p>
                            <ul>
                                <li>first_name (required)</li>
                                <li>last_name (required)</li>
                                <li>email (required, must be unique)</li>
                                <li>password (optional - if not provided, a random password will be generated)</li>
                                <li>email_verified_at (optional)</li>
                                <li>temp_password (optional)</li>
                            </ul>
                            
                            <a href="{{ route('users.download-template') }}" class="btn btn-sm btn-secondary">
                                Download Import Template
                            </a>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-primary">
                                {{ __('Import Users') }}
                            </button>
                            <a href="{{ route('users.index') }}" class="btn btn-secondary">
                                {{ __('Cancel') }}
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection