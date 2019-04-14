<?php

// API – Requests
$router->post('/api/1/requests', 'ApiController@postRequest');
$router->get('/api/1/requests', 'ApiController@getAllRequests');

// API – Server webhooks
$router->post('/api/1/callbacks/requests/confirmed', 'ApiController@requestConfirmedWebhook');
$router->post('/api/1/callbacks/requests/cancelled', 'ApiController@requestCancelledWebhook');
$router->post('/api/1/callbacks/requests/expired', 'ApiController@requestExpiredWebhook');

// Web – Admin view
$router->get('/admin', 'WebController@getAdmin');

// Web – Home view
$router->get('/', 'WebController@getIndex');