<?php

namespace App\Http\Controllers;

use App\Models\Url;
use App\Service\RegexValidator;
use App\Service\Util;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Carbon\Carbon;


/**
 * Class UrlController
 * @package App\Http\Controllers
 */
class UrlController extends Controller
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function store(Request $request)
    {
        $validator = new RegexValidator("/Test/");

        $data = json_decode($request->getContent(), true);
        if ($validator->isMatch($data['origin'])) {
            return Util::buildResponse("FAIL", $data['origin'] . " is not allow.", [], 410);
        }

        $url = new Url();
        $newURl = $url->setFromStoreRequestData($data);
        $code = $newURl->code;
        $result = $newURl->save();

        if (!$result) {
            Util::buildResponse(
                "FAIL",
                "Something went wrong",
                [],
                422
            );
        }

        return Util::buildResponse(
            "SUCCESS",
            null,
            ["shortenedUrl" => \Illuminate\Support\Facades\URL::to('/' . $code)]
        );
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $sortList = ["hit", "expiry"];
        $searchTerm = urldecode($request->input('searchTerm'));
        $sortBy = $request->input('sortBy');
        $order = $request->input('order');


        $urls = Url::where('origin', 'like', '%' . $searchTerm . '%')
            ->orWhere('code', 'like', '%' . $searchTerm . '%');


        if (in_array($sortBy, $sortList))   //Ensure protected from sql injection.
        {
            $urls->orderBy($sortBy, $order);
        }


        $perPage = $request->input('perPage');

        return Util::buildResponse(
            "SUCCESS",
            "",
            [
                "urls" => $urls->paginate($perPage ? $perPage : 10)
            ],
            200
        );
    }

    /**
     * @param Url $url
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Url $url)
    {
        //Remove item from cache if exist.
        if (Cache::has($url->code)) {
            Cache::forget($url->code);
        }
        $result = $url->delete();
        if ($result) {
            return Util::buildResponse("SUCCESS", "Url has been deleted", [], 200);
        }

        return Util::buildResponse("FAIL", "Something wrong", [], 410);
    }

    public function redirect($code)
    {
        $url = null;
        $errorMessage = null;
        if (Cache::has($code)) {
            $url = Cache::get($code);
        } else {
            $url = \App\Models\Url::where('code', $code)->first();
            if ($url) {
                Cache::put($code, $url, $minute = 60);
            }
        }


        if (!$url) {
            return response("Url is not existed.", 410);
        };

        if ($url->expiry->lt(Carbon::now())) {
            return response("Url is expired.", 410);
        }

        if ($errorMessage) {
            return response($errorMessage, 410);
        }

        //ensure that we always get the latest hit.
        $url->increment('hit');

        return redirect($url->origin);
    }


}
