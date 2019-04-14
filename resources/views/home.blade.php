<!DOCTYPE html>
<html>
<head>
    <title>Lydia</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="/resources/css/uikit.min.css" />
    <link rel="stylesheet" href="/resources/css/main.css" />
    <script src="/resources/js/uikit.min.js"></script>
    <script src="/resources/js/uikit-icons.min.js"></script>
</head>
<body>
<div class="uk-flex-center" uk-grid>
    <div class="uk-width-1-3@m">
        <div class="uk-card uk-card-default uk-card-body request-lydia">
            <h1 class="uk-heading-line uk-text-center"><span>Request a Lydia</span></h1>
            <div id="request-lydia--success" class="uk-alert-success" uk-alert>
                <a class="uk-alert-close" uk-close></a>
                <p>
                    <strong>Your payment request has been sent!</strong><br />
                    Share this <a href="" target="_blank" id="request-lydia--link">link</a> with the recipient.
                </p>
            </div>
            <form class="uk-form-stacked uk-grid-small" uk-grid>
                <div class="uk-width-1-2@s">
                    <label class="uk-form-label" for="request-lydia--form--firstname">Firstname:</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="request-lydia--form--firstname" type="text" name="firstname" placeholder="John" required>
                    </div>
                </div>
                <div class="uk-width-1-2@s">
                    <label class="uk-form-label" for="request-lydia--form--lastname">Lastname:</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="request-lydia--form--lastname" type="text" name="lastname" placeholder="Doe" required>
                    </div>
                </div>
                <div class="uk-width-1-1">
                    <label class="uk-form-label" for="request-lydia--form--email">E-mail address:</label>
                    <div class="uk-form-controls">
                        <input class="uk-input" id="request-lydia--form--email" type="text" name="email" placeholder="john@doe.net" required>
                    </div>
                </div>
                <div class="uk-width-1-1">
                    <label class="uk-form-label" for="request-lydia--form--amount">Amount:</label>
                    <div class="uk-form-controls">
                        <input class="uk-input uk-form-large" id="request-lydia--form--amount" name="amount" type="text" placeholder="42.00" required>
                    </div>
                </div>
                <p class="uk-width-1-1 request-lydia--btn-wrapper" uk-margin>
                    <button id="request-lydia--submit" type="submit" class="uk-button uk-width-1-1 uk-button-primary uk-button-large">Send Lydia Request</button>
                </p>
            </form>
        </div>
    </div>
</div>
<script type="text/javascript" src="/resources/js/home.js"></script>
</body>
</html>