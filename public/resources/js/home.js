getInputValueForField = (field) => document.getElementById(`request-lydia--form--${field}`).value;

toggleButton = (enabled, text) => {
    const btn = document.getElementById('request-lydia--submit');
    btn.disabled = !enabled;
    btn.innerHTML = text;
};

resetInputs = () => {
    const allInputs = document.getElementsByClassName('uk-input');

    for (let input of allInputs) {
        input.classList.remove('uk-form-danger');
        input.classList.add('uk-form-success');
    }
};

setInputError = (field) => {
    const inputElement = document.getElementById(`request-lydia--form--${field}`);
    inputElement.classList.remove('uk-form-success');
    inputElement.classList.add('uk-form-danger');
};

displaySuccessBanner = paymentUrl => {
    const successLink = document.getElementById('request-lydia--link');
    successLink.href = paymentUrl;

    const successBanner = document.getElementById('request-lydia--success');
    successBanner.style.display = 'block';
};

document.getElementById('request-lydia--submit').onclick = (e) => {
    e.preventDefault();

    resetInputs();

    toggleButton(false, 'Sending request…');

    fetch('/api/1/requests', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            firstname: getInputValueForField('firstname'),
            lastname: getInputValueForField('lastname'),
            email: getInputValueForField('email'),
            amount: getInputValueForField('amount')
        })
    })
        .then(res => {
            if (!res.ok) {
                throw res;
            }

            return res.json();
        })
        .then(res => {
            setTimeout(() => { // Response is too quick, we give the user a false sense of action behind the scene…
                toggleButton(true, 'Send Lydia request');

                if (res.status !== "success") {
                    return;
                }

                displaySuccessBanner(res.url);
            }, 800);
        })
        .catch(res => {
            toggleButton(true, 'Send Lydia request');

            if (res.status !== 422) {
                return;
            }

            res.json().then(errors => {
                Object.keys(errors).forEach(error => setInputError(error));
            });
        });
};