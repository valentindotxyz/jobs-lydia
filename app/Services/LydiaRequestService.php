<?php

namespace App\Services;

use App\Enums\LydiaRequestStatuses;
use App\Models\LydiaRequest;
use Exception;
use Illuminate\Support\Collection;
use Requests;

class LydiaRequestService
{
    /**
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param int $amount
     * @return string
     * @throws Exception
     * Sends a request to Lydia API to initiate a payment request.
     * Firstname, lastname, email and amount (as integer) are required.
     */
    public static function send(string $firstname, string $lastname, string $email, float $amount)
    {
        $response = Requests::post('https://homologation.lydia-app.com/api/request/do.json', [], [
            'vendor_token' => env('LYDIA_VENDOR_TOKEN'),
            'amount' => number_format((float) $amount, 2, '.', ''),
            'currency' => 'EUR',
            'recipient' => $email,
            'type' => 'email',
            'confirm_url' => env('LYDIA_WEBKOOK_URL') . '/api/1/callbacks/requests/confirmed',
            'cancel_url' => env('LYDIA_WEBKOOK_URL') . '/api/1/callbacks/requests/cancelled',
            'expire_url' => env('LYDIA_WEBKOOK_URL') . '/api/1/callbacks/requests/expired',
        ]);

        $response = json_decode($response->body);

        if ($response->error !== "0") {
            throw new Exception($response->message);
        }

        if (!self::save($response->request_id, $response->request_uuid, $firstname, $lastname, $email, $amount)) {
            throw new Exception("Could not save Lydia request.");
        }

        return $response->mobile_url;
    }

    /**
     * @param string $lydia_request_id
     * @param string $lydia_request_uuid
     * @param string $firstname
     * @param string $lastname
     * @param string $email
     * @param float $amount
     * @return bool
     * Persists a successful Lydia request in database for later use.
     */
    public static function save(string $lydia_request_id, string $lydia_request_uuid, string $firstname, string $lastname, string $email, float $amount)
    {
        $lydiaRequest = new LydiaRequest();
        $lydiaRequest->lydia_request_id = $lydia_request_id;
        $lydiaRequest->lydia_request_uuid = $lydia_request_uuid;
        $lydiaRequest->firstname = $firstname;
        $lydiaRequest->lastname = $lastname;
        $lydiaRequest->email = $email;
        $lydiaRequest->amount = $amount;
        $lydiaRequest->status = LydiaRequestStatuses::INITIATED;

        return $lydiaRequest->save();
    }

    /**
     * @param string $request_id
     * @param string $status
     * @return bool
     * Update the status of a previous Lydia request made.
     */
    public static function updateStatus(string $request_id, string $status)
    {
        $request = LydiaRequest::where('lydia_request_id', $request_id)->first();
        $request->status = $status;
        return $request->save();
    }

    /**
     * @return Collection
     * Get all Lydia requests made, ordered by creating date descending.
     */
    public static function getAll()
    {
        return LydiaRequest::orderBy('created_at', 'desc')->get();
    }

    /**
     * @param array $arguments
     * @param string $signature
     * @return bool
     * Checks if an incoming request is signed by Lydia and as such, trustworthy.
     */
    public static function isRequestSigned(array $arguments, string $signature)
    {
        ksort($arguments);

        $computedSignature = md5(http_build_query($arguments) . "&" . env('LYDIA_PRIVATE_TOKEN'));

        return $computedSignature === $signature;
    }
}
