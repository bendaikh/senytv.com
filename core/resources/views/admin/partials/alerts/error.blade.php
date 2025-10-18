<div class="alert alert-important alert-danger alert-dismissible" role="alert">
    <div class="d-flex">
        <div>
            <!-- Download SVG icon from http://tabler.io/icons/icon/alert-circle -->
            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon alert-icon icon-2">
                <path d="M3 12a9 9 0 1 0 18 0a9 9 0 0 0 -18 0"></path>
                <path d="M12 8v4"></path>
                <path d="M12 16h.01"></path>
            </svg>
        </div>
        <div>
            <h4 class="alert-heading">{{ $title ?? 'Error' }}</h4>
            <div class="alert-description">
                <!-- Check for validation errors and display them as a list -->
                @if ($errors->any())
                    <ul class="alert-list">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @elseif(session('error'))
                    <!-- Display the session error as a single list item -->
                    <ul class="alert-list">
                        <li>{{ session('error') }}</li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <a class="btn-close" data-bs-dismiss="alert" aria-label="close"></a>
</div>
