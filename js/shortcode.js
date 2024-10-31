const d = document;
const id_name = 'ngv-validation-response-wrapper';


// add the text node to the newly created div
function ngvInsertResponse($response) {
    document.getElementById(id_name).innerHTML = '<div id="validator-response" style="background-color:#3c3c3c;padding:10px;margin-top:10px;display:inline-block;border-radius:2px;color:#fff;">' + $response + '</div>';
}


function ngvFetchResponse(submitEvent) {
    submitEvent.preventDefault();
    const value = submitEvent.srcElement.querySelector('#ngv-validator-input').value;

    if (!value) {
        return null;
    }

    const wrapper = document.getElementById(id_name);

    if (wrapper) {
        wrapper.remove();
    }

    const validatorBtn = document.querySelector('.ngv-call-validator');
    const oldValue = validatorBtn.value;

    const newDiv = document.createElement("div");

    newDiv.setAttribute("id", id_name);
    submitEvent.srcElement.parentNode.append(newDiv);
    ngvInsertResponse('Validating...');

    fetch(ngv_settings.api_url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(value),
    })
        .then(response => response.json())
        .then(data => {
            validatorBtn.value = oldValue;
            ngvInsertResponse(data);
        })
        .catch((error) => {
            validatorBtn.value = oldValue;
            console.error('Error:', error);
        });

}


window.onload = function () {

    try {
        if (!ngv_settings) { return null; }
    } catch (error) {
        return null;
    }

    if (ngv_settings.use_fetch && ngv_settings.use_fetch === "true") {

        const form = d.querySelector('#ngv-validation-form');
        form.addEventListener('submit', ngvFetchResponse);

    }
}