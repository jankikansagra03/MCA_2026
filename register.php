<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script>
        function validate() {
            var fname = document.getElementById('fn');
            var m_number = document.getElementById('mn');
            var fn_err = document.getElementById('fn_err');
            var mn_err = document.getElementById('mn_err');
            var valid_fn = false;
            var valid_mn = false;
            if (fname.value == "") {
                fn_err.style.color = "red";
                fn_err.innerHTML = "Fullname is a required field";
                fname.style.border = "2px solid red";

            } else {
                if (fname.value.length < 2) {
                    fn_err.style.color = "red";
                    fn_err.innerHTML = "Fullname must have minimum 2 characters";

                } else {
                    if (fname.value.length > 25) {
                        fn_err.style.color = "red";
                        fn_err.innerHTML = "Fullname must have maximum 25 characters";

                    } else {
                        fname_regex = /^[a-zA-Z/s]{2,25}$/;
                        if (fname_regex.test(fname.value)) {
                            fn_err.innerHTML = "";
                            fname.style.border = "2px solid green";
                            valid_fn = true;

                        } else {
                            fn_err.style.color = "red";
                            fn_err.innerHTML = "Fullname must have letters and spaces";

                        }
                    }
                }
            }
            if (m_number.value == "") {
                mn_err.style.color = "red";
                mn_err.innerHTML = "Mobile number cannot be Empty";

            } else {
                if (m_number.value.length != 10) {
                    mn_err.style.color = "red";
                    mn_err.innerHTML = "Mobile number must be of 10 digits"
                } else {
                    mn_regex = /^[0-9]{10}$/
                    if (mn_regex.test(m_number.value)) {
                        mn_err.innerHTML = ""
                        valid_mn = true
                    } else {
                        mn_err.style.color = "red";
                        mn_err.innerHTML = "Mobile must contain only digits"
                    }
                }
            }
            if (valid_fn == true && valid_mn == true) {
                return true
            } else {
                return false
            }
        }
    </script>
</head>

<body>
    <form action="register_action.php" method="get" onsubmit="return(validate())">
        Enter Fullname:
        <input type="text" name="" id="fn">
        <span id="fn_err"></span>
        <br>
        Enter Mobile Number:
        <input type="text" name="" id="mn">
        <span id="mn_err"></span>
        <br>
        <input type="submit" value="Register">

    </form>
</body>

</html>