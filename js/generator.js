function ngvCopySelected(id_string) {

    let from = document.getElementById(id_string);
    console.log(from);
    from.select();
    document.execCommand("copy");
}

// Generates numbers
function ngvGeneratorFunction() {

    let quantity_input = document.getElementById('quantity');
    let first_number_input = document.getElementById('firstnumber');
    let second_number_input = document.getElementById('lastnumber');
    let show_numbers = document.getElementById("show-numbers");
    let first_number = Math.abs(first_number_input.value);
    let second_number = Math.abs(second_number_input.value);
    let quantity = Math.abs(quantity_input.value);
    let sort = document.getElementById("sort");
    let sortrandom = sort.options[sort.selectedIndex].value;
    let diffrence = (second_number - first_number);

    // Reset 
    quantity_input.style.border = "unset";
    first_number_input.style.border = "unset";
    second_number_input.style.border = "unset";
    show_numbers.innerHTML = "";

    // Rules
    if (first_number > second_number) {
        first_number_input.style.border = "2px solid #c41212";
        show_numbers.innerHTML = "The first number is bigger then the last number!";
        return false;
    }

    if (first_number == second_number) {
        first_number_input.style.border = "2px solid #c41212";
        second_number_input.style.border = "2px solid #c41212";
        show_numbers.innerHTML = "The first number is the same as the last number!";
        return false;
    }
    if (diffrence < quantity) {
        show_numbers.innerHTML = "The quantity of numbers you want is higher then the range diffrence!<br>The range diffrence is: " + diffrence;
        quantity_input.value = diffrence;
        quantity = diffrence;
    }

    if (quantity < 1) {

        document.getElementById("show-numbers").innerHTML = "Quantity cant be empty, using all available numbers!";
        quantity_input.value = diffrence;
        quantity = diffrence;
    }

    let arr = [];
    let track = [];
    let ii = 0;
    let rnd = "";

    // Main generator
    while (ii < quantity) {
        rnd = Math.floor(Math.random() * (second_number - first_number + 1)) + first_number;
        if (!track[rnd]) {
            arr[ii] = track[rnd] = rnd;
            ii += 1;
        }
    }
    // Check if sort is specificed
    if (sortrandom == 1) {
        arr.sort(function (a, b) {
            return a - b
        });
    }

    // Output numbers
    document.getElementById("show-numbers").innerHTML = (arr.join(' '));
}


function generateASerial(the_length, alphabet, uppercase, lowercase, use_digits) {

    let serial = "";
    for (let i = 0; i < the_length; i++) {

        let random_int = Math.floor(Math.random() * alphabet.length);
        let random_char = "a";

        if (uppercase) {

            if (!lowercase) {

                random_char = alphabet[random_int].toUpperCase();

            } else {

                let f_f = Math.floor(Math.random() * 2);

                if (f_f === 0) {

                    random_char = alphabet[random_int].toUpperCase();

                } else {
                    random_char = alphabet[random_int].toLowerCase();
                }
            }
        } else if (lowercase) {

            random_char = alphabet[random_int].toLowerCase();

        } else {

            random_char = alphabet[random_int].toLowerCase();
        }

        if (use_digits) {

            let f_f = Math.floor(Math.random() * 2);

            if (f_f === 0) {

                random_char = Math.floor(Math.random() * 10);
            }

        }

        serial += random_char;
    }

    return serial;
}

function searchForDublicates(serials, index) {

    let string_to_search = serials[index];

    for (let x = (index + 1); x < serials.length; x++) {

        if (string_to_search === serials[x]) {
            return true;
        }
    }

    return false;
}

// Generates serials
function ngvSerialFunction() {

    let inputs = document.querySelectorAll('.serial-charset-input');
    let the_length = 0;
    let alphabet = 'abcdefghijklmnopqrstuvwxyz'.split('');
    let show_serials = document.getElementById("show-serials");
    let uppercase = false;
    let lowercase = false;
    let use_digits = false;
    let quantity = 0;
    let serials = [];

    // Reset 
    show_serials.innerHTML = "";

    // Check if first input is checked else return false
    for (let i = 0; i < inputs.length; i++) {

        if (inputs[i].getAttribute('data') === 'charset') {

            if (inputs[i].checked === false) {

                show_serials.innerHTML = "Select at least one option";
                return false;
            }
        }

        // If digits value is smaller then 1 return false
        if (inputs[i].getAttribute('data') === 'length') {

            if (inputs[i].value < 1) {

                return false;

            } else {

                the_length = inputs[i].value;
            }

        }

        // If quantity value is smaller then 1 return false
        if (inputs[i].getAttribute('data') === 'qty') {

            if (inputs[i].value < 1) {

                return false;

            } else {

                quantity = inputs[i].value;
            }

        }

        if (inputs[i].getAttribute('data') === 'uppercase') {

            uppercase = inputs[i].checked;

        }

        if (inputs[i].getAttribute('data') === 'lowercase') {

            lowercase = inputs[i].checked;

        }

        if (inputs[i].getAttribute('data') === 'digits') {

            use_digits = inputs[i].checked;

        }
    }

    // Add serials to list
    for (let count = 0; count < quantity; count++) {

        serials.push(generateASerial(the_length, alphabet, uppercase, lowercase, use_digits));
    }

    // Search for dublicates
    for (let i = 0; i < serials.length; i++) {

        if (searchForDublicates(serials, i)) {

            let dublicate = true;
            let trys = 0;

            while (dublicate) {

                if (trys > 1000) {
                    show_serials.innerHTML = "Could not generate " + quantity + " unique serials. Either you change the serial options or decrease the quantity.";
                    return false;
                }

                trys += 1;
                serials[i] = generateASerial(the_length, alphabet, uppercase, lowercase, use_digits);

                if (!searchForDublicates(serials, i)) {
                    dublicate = false;
                }
            }

        }

    }

    show_serials.innerHTML = (serials.join(' '));
}

// Hides checkboxes if charset is not checked
function checkboxControl() {

    let charset_inputs = document.querySelectorAll('.serial-charset-input');
    let checked = false;

    for (let i = 0; i < charset_inputs.length; i++) {

        let charset_input = charset_inputs[i];

        if (charset_input.getAttribute('data') === 'charset') {

            charset_input.onclick = function () {
                checkboxControl();
            }

            if (charset_input.checked) {

                charset_input.style.border = "unset";
                checked = true;

            } else {
                charset_input.style.border = "2px solid #c41212";
            }

        } else if (charset_input.getAttribute('data') !== 'charset') {

            if (!checked) {
                charset_input.disabled = true;

            } else {
                charset_input.disabled = false;
            }
        }
    }

    return checked;
}

window.onload = function () {

    document.getElementById("gen-numbers-button").onclick = function () {
        ngvGeneratorFunction();
    };

    document.getElementById("gen-serials-button").onclick = function () {
        ngvSerialFunction();
    };

    document.getElementById("cpy-numbers-button").onclick = function () {
        ngvCopySelected('show-numbers');
    };

    document.getElementById("cpy-serials-button").onclick = function () {
        ngvCopySelected('show-serials');
    };

    checkboxControl();
}