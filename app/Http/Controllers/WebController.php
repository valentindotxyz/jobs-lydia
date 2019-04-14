<?php

namespace App\Http\Controllers;

use Laravel\Lumen\Routing\Controller as BaseController;
use App\Services\LydiaRequestService;

/**
 * Class WebController
 * @package App\Http\Controllers
 * Handle the methods for the / routes (return views).
 */
class WebController extends BaseController
{
    /**
     * @return \Illuminate\View\View
     * This method returns the home view, that contains the Lydia request form.
     */
    public function getIndex()
    {
        return view('home');
    }

    /**
     * @return \Illuminate\View\View
     * This method returns the admin view, that lists all the Lydia requests made.
     */
    public function getAdmin()
    {
        $requests = LydiaRequestService::getAll();

        return view('admin', ['requests' => $requests]);
    }
}
