<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Enums\LydiaRequestStatuses;
use App\Services\LydiaRequestService;
use Illuminate\Http\Request;

/**
 * Class ApiController
 * @package App\Http\Controllers
 * Handle the methods for the /api routes (return JSON).
 */
class ApiController extends BaseController
{
    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     * This method is called when a user submits a new Lydia request (via AJAX for instance).
     * Body of the request must contains a firstname, lastname, email address and amount.
     */
    public function postRequest(Request $request)
    {
        $this->validate($request, [
            'lastname' => 'required|string',
            'firstname' => 'required|string',
            'email' => 'required|email',
            'amount' => 'required|numeric'
        ]);

        $amount = str_replace([','], '.', $request->input('amount'));

        try {
            $url = LydiaRequestService::send($request->input('firstname'), $request->input('lastname'), $request->input('email'), $amount);
            return response()->json(['status' => 'success', 'url' => $url]);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * This method is called by the Lydia API once a previous request has been paid.
     * Once the request's signature has been checked, we update the status in the DB.
     */
    public function requestConfirmedWebhook(Request $request)
    {
        if (!LydiaRequestService::isRequestSigned([
            'currency' => $request->input('currency'),
            'request_id' => $request->input('request_id'),
            'amount' => $request->input('amount'),
            'signed' => $request->input('signed'),
            'transaction_identifier' => $request->input('transaction_identifier'),
            'vendor_token' => $request->input('vendor_token'),
        ], $request->input('sig')))
        {
            return abort(401);
        }

        $statusUpdated = LydiaRequestService::updateStatus($request->input('request_id'), LydiaRequestStatuses::PAID);

        if (!$statusUpdated) {
            return abort(500, 'Could not update status to ' . LydiaRequestStatuses::PAID);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * This method is called by the Lydia API once a previous request has been cancelled.
     * Once the request's signature has been checked, we update the status in the DB.
     */
    public function requestCancelledWebhook(Request $request)
    {
        if (!LydiaRequestService::isRequestSigned([
            'currency' => $request->input('currency'),
            'request_id' => $request->input('request_id'),
            'amount' => $request->input('amount'),
            'signed' => $request->input('signed'),
            'vendor_token' => $request->input('vendor_token'),
        ], $request->input('sig'))) {
            return abort(401);
        }

        $statusUpdated = LydiaRequestService::updateStatus($request->input('request_id'), LydiaRequestStatuses::CANCELLED);

        if (!$statusUpdated) {
            return abort(500, 'Could not update status to ' . LydiaRequestStatuses::CANCELLED);
        }

        return response()->json(['status' => 'success']);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     * This method is called by the Lydia API once a previous request has expired.
     * Once the request's signature has been checked, we update the status in the DB.
     */
    public function requestExpiredWebhook(Request $request)
    {
        if (!LydiaRequestService::isRequestSigned([
            'currency' => $request->input('currency'),
            'request_id' => $request->input('request_id'),
            'amount' => $request->input('amount'),
            'signed' => $request->input('signed'),
            'vendor_token' => $request->input('vendor_token'),
        ], $request->input('sig'))) {
            return abort(401);
        }

        $statusUpdated = LydiaRequestService::updateStatus($request->input('request_id'), LydiaRequestStatuses::EXPIRED);

        if (!$statusUpdated) {
            return abort(500, 'Could not update status to ' . LydiaRequestStatuses::EXPIRED);
        }

        return response()->json(['status' => 'success']);
    }
}
