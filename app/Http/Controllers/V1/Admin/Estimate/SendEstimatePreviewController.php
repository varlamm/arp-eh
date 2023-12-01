<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Estimate;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\SendEstimatesRequest;
use Xcelerate\Models\Estimate;
use Illuminate\Mail\Markdown;

class SendEstimatePreviewController extends Controller
{
    /**
    * Handle the incoming request.
    *
    * @param  \Xcelerate\Http\Requests\SendEstimatesRequest  $request
    * @return \Illuminate\Http\JsonResponse
    */
    public function __invoke(SendEstimatesRequest $request, Estimate $estimate)
    {
        $this->authorize('send estimate', $estimate);

        $markdown = new Markdown(view(), config('mail.markdown'));

        $data = $estimate->sendEstimateData($request->all());
        $data['url'] = $estimate->estimatePdfUrl;

        return $markdown->render('emails.send.estimate', ['data' => $data]);
    }
}
