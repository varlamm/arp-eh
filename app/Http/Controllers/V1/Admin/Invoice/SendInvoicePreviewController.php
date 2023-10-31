<?php

namespace Xcelerate\Http\Controllers\V1\Admin\Invoice;

use Xcelerate\Http\Controllers\Controller;
use Xcelerate\Http\Requests\SendInvoiceRequest;
use Xcelerate\Models\Invoice;
use Illuminate\Mail\Markdown;

class SendInvoicePreviewController extends Controller
{
    /**
     * Mail a specific invoice to the corresponding customer's email address.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(SendInvoiceRequest $request, Invoice $invoice)
    {
        $this->authorize('send invoice', $invoice);

        $markdown = new Markdown(view(), config('mail.markdown'));

        $data = $invoice->sendInvoiceData($request->all());
        $data['url'] = $invoice->invoicePdfUrl;

        return $markdown->render('emails.send.invoice', ['data' => $data]);
    }
}
