<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <div id="loader" style="display: none;">Loading...</div>
                    <form method="POST" action="{{ route('submit-form') }}">
                        @csrf

                        <!-- Email Address -->
                        <div>
                            <x-input-label for="long_url" :value="__('Url')" />
                            <x-text-input id="long_url" class="block mt-1 w-full" type="text" name="long_url" :value="old('long_url')" required autofocus autocomplete="username" />
                            <x-input-error :messages="$errors->get('long_url')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-4">

                            <x-primary-button class="ms-3">
                                {{ __('Submit') }}
                            </x-primary-button>
                        </div>
                    </form>
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
                                <td>
                                    <a href="{{ route('redirect', ['shortUrl' => urlencode($url->short_url)]) }}">{{ $url->short_url }}</a>
                                </td>
                                <td class="text-center">{{ (isset($url->totalCount) && $url->totalCount != NULL) ? $url->totalCount : 0 }}</td>
                                <td>
                                    <form class="hit-count-form" action="" method="post">
                                        <input type="hidden" name="url_id" value="{{ $url->id }}">
                                        <input type="hidden" name="user_id" value="{{ Auth::id() }}">
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
    </div>
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
</x-app-layout>
