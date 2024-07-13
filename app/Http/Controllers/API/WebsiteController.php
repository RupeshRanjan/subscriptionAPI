<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\API\BaseController;
use App\Models\Website;
use App\Http\Resources\WebsiteResource;
use App\Mail\WebsitePost;
use App\Models\Subscription;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;

class WebsiteController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $websites = Website::with('posts')->get();
    
        return $this->sendResponse($websites, 'websites retrieved successfully.');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'url' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $product = Website::create($input);
   
        return $this->sendResponse(new WebsiteResource($product), 'Website created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Website $website)
    {  
        if (is_null($website)) {
            return $this->sendError('Website not found.');
        }
   
        return $this->sendResponse(new WebsiteResource($website), 'Website retrieved successfully.');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Website $website)
    {
        $input = $request->all();
   
        $validator = Validator::make($input, [
            'name' => 'required',
            'url' => 'required'
        ]);
   
        if($validator->fails()){
            return $this->sendError('Validation Error.', $validator->errors());       
        }
   
        $website->name = $input['name'];
        $website->url = $input['url'];
        $website->save();
   
        return $this->sendResponse(new WebsiteResource($website), 'Website updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Website $website)
    {
        $website->delete();
        return $this->sendResponse([], 'Website deleted successfully.');
    }

    /**
     * User subscription added for website.
     */
    public function subscribe(Website $website)
    {
        $subscription = Subscription::updateOrCreate([
            "user_id" => Auth::id(),
            "website_id" => $website->id
        ]);
        return $this->sendResponse($subscription, 'Subscription added successfully.');
    }
}
