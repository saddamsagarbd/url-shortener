<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>URL Shortener</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <style>
        #loader {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(255, 255, 255, 0.8);
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        }

    </style>
  </head>
  <body>
    <!-- resources/views/form.blade.php -->
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    <div class="container mt-3">
        <div class="row">
            <div class="col-md-12">
                <div id="loader" style="display: none;">Loading...</div>
                <form action="{{ route('submit-form') }}" method="POST">
                    @csrf
                    <div class="mb-3 row">
                        <div class="col-auto">
                            <label for="exampleInputEmail1" class="form-label">Url</label>
                        </div>
                        <div class="col-md-10">
                            <input type="text" class="form-control" name="long_url" aria-describedby="emailHelp" value="{{ old('long_url') }}">
                            @error('long_url')
                                <div class="alert alert-warning">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">Submit</button>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <table class="table">
                    <thead>
                        <tr>
                            <th>SL</th>
                            <th>Long URL</th>
                            <th>Short URL</th>
                            <th>Click Count</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($urls as $url)
                        <tr>
                            <td>{{ $url->id }}</td>
                            <td>{{ $url->long_url }}</td>
                            <td>{{ $url->short_url }}</td>
                            <td>{{ (isset($url->totalCount) && $url->totalCount != NULL) ? $url->totalCount : 0 }}</td>
                            <td>
                                <form class="hit-count-form" action="" method="post">
                                    <input type="hidden" name="url_id" value="{{ $url->id }}">
                                    <input type="hidden" name="user_id" value="{{ $url->user_id }}">
                                    <a href="javascript:void(0)" class="hitCounter">Click</a:href>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
    <script>
        jQuery(document).ready(function(){
            jQuery(document).on("click", ".hitCounter",function(e){
                e.preventDefault();
                jQuery('#loader').show();
                var formData = "";
                const form = jQuery(this).closest(".hit-count-form");
                if (form.length === 0) {
                    console.error('Form not found or selector is incorrect.');
                } else {
                    // Check if form elements have names
                    formData = form.serializeArray();
                    if (formData.length === 0) {
                        console.error('Form has no serializable elements with names.');
                    } else {
                        // FormData should contain form data
                        console.error('Form data not found.');
                    }
                }

                jQuery.ajax({
                    url: "{{ route('hit-count') }}",
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        formData
                    },
                    success: function(response) {
                        console.log(response.message);
                        if(response.success == true) location.reload();
                        jQuery('#loader').hide();
                    },
                    error: function(xhr, status, error) {
                        console.error(xhr.responseText);
                        jQuery('#loader').hide();
                    }
                });
            })
        })
    </script>
  </body>
</html>
