<!-- resources/views/form.blade.php -->
@if ($errors->any())
    <div class="alert alert-danger">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif
@if (session('success'))
    <div class="alert alert-success">
        {{ session('success') }}
    </div>
@endif
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
<div id="loader" style="display: none;">Loading...</div>

<form action="{{ route('submit-form') }}" method="POST">
    @csrf
    <div>
        <label for="long_url">Url:</label>
        <input type="text" id="long_url" name="long_url" value="{{ old('long_url') }}">
        @error('long_url')
            <div class="text-danger">{{ $message }}</div>
        @enderror
    </div>
    <div>
        <button type="submit">Submit</button>
    </div>
</form>

<table>
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
