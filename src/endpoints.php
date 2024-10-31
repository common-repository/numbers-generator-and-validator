<?php

function validate_serial($data)
{
    $value = "";
    try {
        $value = $data->get_params()[0];
    } catch (\Throwable $th) {
        return null;
    }

    return ngvValidateSerial($data->get_params()[0]);
}

add_action('rest_api_init', function () {
    register_rest_route('ngv', 'validate', array(
        'methods' => 'POST',
        'callback' => 'validate_serial',
    ));
});
