document.addEventListener("DOMContentLoaded", function () {

    const forms = document.querySelectorAll("form");

    forms.forEach(function (form) {

        form.addEventListener("submit", function (event) {

            let valid = true;

            /* Remove old errors */

            const oldErrors =
                form.querySelectorAll(".error-message");

            oldErrors.forEach(function (error) {
                error.remove();
            });


            /* Reset borders */

            const allFields =
                form.querySelectorAll(
                    "input, textarea, select"
                );

            allFields.forEach(function (field) {
                field.style.borderColor = "";
            });



            allFields.forEach(function (field) {

                if (
                    field.type !== "hidden" &&
                    field.type !== "radio" &&
                    field.type !== "checkbox" &&
                    field.type !== "file" &&
                    field.type !== "submit" &&
                    field.type !== "button"
                ) {

                    if (field.value.trim() === "") {

                        showError(
                            field,
                            "This field is required."
                        );

                        valid = false;
                    }
                }

            });



            const selects =
                form.querySelectorAll("select");

            selects.forEach(function (select) {

                if (
                    select.value === "" ||
                    select.value === "Select" ||
                    select.value === "select"
                ) {

                    showError(
                        select,
                        "Please select an option."
                    );

                    valid = false;
                }

            });



            const email =
                form.querySelector(
                    'input[name="email"]'
                );

            if (
                email &&
                email.value.trim() !== ""
            ) {

                const value =
                    email.value.trim();

                const atPosition =
                    value.indexOf("@");

                const secondAt =
                    value.indexOf(
                        "@",
                        atPosition + 1
                    );

                const dotPosition =
                    value.indexOf(
                        ".",
                        atPosition + 1
                    );


                if (value.includes(" ")) {

                    showError(
                        email,
                        "Email cannot contain spaces."
                    );

                    valid = false;

                }
                else if (atPosition <= 0) {

                    showError(
                        email,
                        "Enter a valid email address."
                    );

                    valid = false;

                }
                else if (secondAt !== -1) {

                    showError(
                        email,
                        "Email can contain only one @."
                    );

                    valid = false;

                }
                else if (dotPosition === -1) {

                    showError(
                        email,
                        "Email must contain a dot after @."
                    );

                    valid = false;

                }
                else if (
                    dotPosition === atPosition + 1
                ) {

                    showError(
                        email,
                        "Invalid email format."
                    );

                    valid = false;

                }
                else if (
                    dotPosition === value.length - 1
                ) {

                    showError(
                        email,
                        "Email cannot end with a dot."
                    );

                    valid = false;
                }
            }


            /* =====================================
               PASSWORD
               ===================================== */

            const password =
                form.querySelector(
                    'input[name="password"]'
                );

            if (
                password &&
                password.value !== ""
            ) {

                if (password.value.length < 6) {

                    showError(
                        password,
                        "Password must contain at least 6 characters."
                    );

                    valid = false;
                }
            }



            const confirm =
                form.querySelector(
                    'input[name="confirm"]'
                );

            if (
                confirm &&
                password &&
                confirm.value !== ""
            ) {

                if (
                    confirm.value !==
                    password.value
                ) {

                    showError(
                        confirm,
                        "Passwords do not match."
                    );

                    valid = false;
                }
            }



            const name =
                form.querySelector(
                    'input[name="name"]'
                );

            if (
                name &&
                name.value.trim() !== ""
            ) {

                if (
                    name.value.trim().length < 3
                ) {

                    showError(
                        name,
                        "Name must contain at least 3 characters."
                    );

                    valid = false;
                }
            }


            const phone =
                form.querySelector(
                    'input[name="phone"], input[name="contact"]'
                );

            if (
                phone &&
                phone.value.trim() !== ""
            ) {

                const phonePattern =
                    /^[0-9]{11}$/;

                if (
                    !phonePattern.test(
                        phone.value.trim()
                    )
                ) {

                    showError(
                        phone,
                        "Phone number must contain exactly 11 digits."
                    );

                    valid = false;
                }
            }



            const price =
                form.querySelector(
                    'input[name="price"]'
                );

            if (
                price &&
                price.value !== ""
            ) {

                if (
                    isNaN(price.value) ||
                    Number(price.value) <= 0
                ) {

                    showError(
                        price,
                        "Please enter a valid price."
                    );

                    valid = false;
                }
            }



            const location =
                form.querySelector(
                    'input[name="location"]'
                );

            if (
                location &&
                location.value.trim() !== ""
            ) {

                if (
                    location.value.trim().length < 3
                ) {

                    showError(
                        location,
                        "Location must contain at least 3 characters."
                    );

                    valid = false;
                }
            }



            const description =
                form.querySelector(
                    'textarea[name="description"]'
                );

            if (
                description &&
                description.value.trim() !== ""
            ) {

                if (
                    description.value.trim().length < 10
                ) {

                    showError(
                        description,
                        "Description must contain at least 10 characters."
                    );

                    valid = false;
                }
            }


            const roomDetails =
                form.querySelectorAll(
                    'input[name="room_details"], input[name="room_details[]"]'
                );


            if (roomDetails.length > 0) {

                let selectedRoom = false;


                roomDetails.forEach(
                    function (checkbox) {

                        if (checkbox.checked) {
                            selectedRoom = true;
                        }

                    }
                );


                if (!selectedRoom) {

                    showError(
                        roomDetails[0],
                        "Please select at least one room detail."
                    );

                    valid = false;
                }
            }


            const roomTypes =
                form.querySelectorAll(
                    'input[name="room"]'
                );


            if (roomTypes.length > 0) {

                let selectedRoomType = false;


                roomTypes.forEach(
                    function (radio) {

                        if (radio.checked) {
                            selectedRoomType = true;
                        }

                    }
                );


                if (!selectedRoomType) {

                    showError(
                        roomTypes[0],
                        "Please select a room type."
                    );

                    valid = false;
                }
            }


            const roles =
                form.querySelectorAll(
                    'input[name="role"]'
                );


            if (roles.length > 0) {

                let selectedRole = false;


                roles.forEach(
                    function (radio) {

                        if (radio.checked) {
                            selectedRole = true;
                        }

                    }
                );


                if (!selectedRole) {

                    showError(
                        roles[0],
                        "Please select a role."
                    );

                    valid = false;
                }
            }


            const reportType =
                form.querySelector(
                    'select[name="report_type"]'
                );


            if (
                reportType &&
                (
                    reportType.value === "" ||
                    reportType.value === "Select"
                )
            ) {

                showError(
                    reportType,
                    "Please select a report type."
                );

                valid = false;
            }


            const fromDate =
                form.querySelector(
                    'input[name="from"]'
                );

            const toDate =
                form.querySelector(
                    'input[name="to"]'
                );


            if (
                fromDate &&
                toDate &&
                fromDate.value !== "" &&
                toDate.value !== ""
            ) {

                if (
                    fromDate.value >
                    toDate.value
                ) {

                    showError(
                        fromDate,
                        "Starting date cannot be after ending date."
                    );

                    valid = false;
                }
            }


            const message =
                form.querySelector(
                    'textarea[name="message"]'
                );


            if (
                message &&
                message.value.trim() !== ""
            ) {

                if (
                    message.value.trim().length < 10
                ) {

                    showError(
                        message,
                        "Message must contain at least 10 characters."
                    );

                    valid = false;
                }
            }


            const image =
                form.querySelector(
                    'input[name="image"]'
                );


            if (image) {

                if (image.files.length === 0) {

                    showError(
                        image,
                        "Please select an image."
                    );

                    valid = false;

                }
                else {

                    const fileName =
                        image.files[0].name.toLowerCase();

                    const extension =
                        fileName.substring(
                            fileName.lastIndexOf(".") + 1
                        );


                    if (
                        extension !== "jpg" &&
                        extension !== "jpeg" &&
                        extension !== "png" &&
                        extension !== "webp"
                    ) {

                        showError(
                            image,
                            "Only JPG, JPEG, PNG and WEBP images are allowed."
                        );

                        valid = false;
                    }
                }
            }



            if (!valid) {

                event.preventDefault();

            }

        });

    });



    function showError(field, message) {

        field.style.borderColor = "red";


        const error =
            document.createElement("p");

        error.className =
            "error-message";

        error.textContent =
            message;

        error.style.color =
            "red";

        error.style.fontSize =
            "14px";

        error.style.marginTop =
            "5px";


        field.parentNode.appendChild(error);

    }
    

});
    document.addEventListener("DOMContentLoaded", function () {

    const loginForm = document.querySelector(
        'form[action*="login.php"]'
    );

    if (!loginForm) {
        return;
    }

    const params = new URLSearchParams(window.location.search);

    const error = params.get("error");

    if (error === "email_not_found") {

        loginForm.reset();

        alert("There is no account with this email.");

    

    } else if (error === "email_required") {

        loginForm.reset();

        alert("Please enter your email.");

    } else if (error === "password_required") {

        loginForm.reset();

        alert("Please enter your password.");

    }

    if (error) {

        window.history.replaceState(
            {},
            document.title,
            window.location.pathname
        );

    }

});