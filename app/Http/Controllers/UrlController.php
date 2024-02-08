<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Url; // Import the Url model
use App\Models\Click; // Import the Url model
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

class UrlController extends Controller
{
    public function index(){
        try {
            $urls = Url::leftJoin("clicks", "clicks.url_id", "=", "urls.id")
                ->leftJoin("users", "users.id", "=", "clicks.user_id")
                ->select('urls.*', 'users.id as user_id', DB::raw('SUM(clicks.count) as totalCount'))
                ->groupBy("clicks.user_id", "clicks.url_id",)
                ->get();
            return view('urls.url-form')->with("urls", $urls);
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
}
