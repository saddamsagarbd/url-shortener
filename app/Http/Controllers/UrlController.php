<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url; // Import the Url model
use App\Models\Click; // Import the Url model
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class UrlController extends Controller
{
    public function index(){
        try {
            $loggedInUserId = Auth::id();
            $urls = Url::leftJoin("clicks", "clicks.url_id", "=", "urls.id")
                ->leftJoin("users", "users.id", "=", "clicks.user_id")
                ->select('urls.*', 'users.id as user_id', DB::raw('SUM(CASE WHEN clicks.user_id = '. $loggedInUserId .' THEN clicks.count ELSE 0 END) as totalCount'))
                ->groupBy("clicks.user_id", "clicks.url_id")
                ->get();
            return view('dashboard')->with("urls", $urls);
        } catch (\Throwable $th) {
            //throw $th;
            echo $th;
        }
    }
    function generateShortUrl($longUrl)
    {
        // Generate a unique hash for the long URL
        $hash = md5($longUrl);

        // Convert the hash to a short alphanumeric string
        $shortUrl = substr($hash, 0, 8);

        return $shortUrl;
    }
    public function SubmitForm(Request $request){
        $request->validate([
            'long_url' => 'required|string|min:10',
        ]);
        try {
            $longUrl = $request->long_url;
            $shortUrl = $this->generateShortUrl($longUrl);

            $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https://" : "http://";
            $currentDomain = $_SERVER['SERVER_NAME'];
            $currentUrl = $protocol . $currentDomain;

            $fullShortUrl = "$currentUrl/$shortUrl";

            $url = new Url();
            // Set the attributes
            $url->long_url = $longUrl;
            $url->short_url = $fullShortUrl;
            $url->created_at = Carbon::now(); // Set the created_at field to current date and time

            // Save the data to the database
            $url->save();
            return redirect()->back()->with('success', 'Short Url Created successfully!');
        } catch (\Throwable $th) {
            //throw $th;
        }
    }
    public function HitCount(Request $request){
        try {
            $formdata = array_column($request->formData, "value", "name");
            $click = new Click;
            $click->count = 1;
            $click->url_id = $formdata["url_id"];
            $click->user_id = $formdata["user_id"];
            $click->created_at = Carbon::now(); // Set the created_at field to current date and time
            // dd($click);
            $click->save();
            return Response::json(['success' => true, 'message' => 'Clicked!']);

        } catch (\Throwable $th) {
            //throw $th;
        }
    }

    public function RedirectShortUrl($shortUrl){
        $shortUrl = urldecode($shortUrl);
        // Perform a database lookup to find the corresponding long URL
        $url = Url::where('short_url', $shortUrl)->first();

        // If the URL is found, redirect to the corresponding long URL
        if ($url) {
            return Redirect::away($url->long_url);
        }

        // If the URL is not found, you can handle the response accordingly (e.g., show a 404 page)
        abort(404);
    }
}
