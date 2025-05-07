            // nhấn vào con mắt sẽ hiện ra password
            document.getElementById('toggle_password').addEventListener('click', function() {
                var passwordInput = document.getElementById('password');
                if (passwordInput.type == 'password') {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            }); 
            document.getElementById('toggle_confirm_password').addEventListener('click', function() {
                var passwordInput = document.getElementById('confirm_password');
                if (passwordInput.type == 'password') {
                    passwordInput.type = 'text';
                } else {
                    passwordInput.type = 'password';
                }
            });

            // kiểm tra email
            function checkEmail() {
                var email = document.getElementById('email').value;
                var emailPattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
                var checkEmailDiv = document.getElementById('check_email').querySelector('span');

                if (emailPattern.test(email) && emailPattern !== '') {
                    checkEmailDiv.innerText = 'Valid email address';
                    checkEmailDiv.style.color = 'green';
                    checkEmailDiv.style.fontFamily = "Lexend";
                    return true;
                } else {
                    checkEmailDiv.innerText = 'Invalid email address. Please enter again!';
                    checkEmailDiv.style.color = 'red';
                    checkEmailDiv.style.fontFamily = "Lexend";
                    return false;
                }
            }
            function handleFocus() {
                var checkEmailDiv = document.getElementById('check_email').querySelector('span');
                checkEmailDiv.innerHTML = ''; // Xóa thông báo khi phần tử nhận được focus
            }
            document.getElementById('email').addEventListener('blur', checkEmail);
            document.getElementById('email').addEventListener('focus', handleFocus);

            // Kiểm tra mật khẩu
            function check() {
                var password = document.getElementById("password").value;
                var confirm_password = document.getElementById("confirm_password").value;
                var trigger_password = document.getElementById("check_password").querySelector('span');
                var trigger_confirm_password = document.getElementById("check_confirm_password");

                if ((password.length >= 8) && (password.match(/[0-9]/)) && (password.match(/[A-Z]/)) && (password.match(/[a-z]/)) && password !== '') {
                    trigger_password.style.color = "green";
                    trigger_password.style.fontFamily = "Lexend";
                    trigger_password.innerText = "Valid password.";
                    return true;
                } else {
                    trigger_password.style.color = "red";
                    trigger_password.style.fontFamily = "Lexend";
                    trigger_password.innerText = "Invalid password. Please enter again!";
                    return false;
                }
            }
            function handlePass() {
                var checkpassword = document.getElementById('check_password').querySelector('span');
                checkpassword.innerText = ''; // Xóa thông báo khi phần tử nhận được focus
            }
            document.getElementById('password').addEventListener('blur', check);
            document.getElementById('password').addEventListener('focus', handlePass);

            // Kiểm tra xác nhận mật khẩu
            function check_confirm_password() {
                var password = document.getElementById("password").value;
                var confirm_password = document.getElementById("confirm_password").value;
                var trigger_confirm_password = document.getElementById("check_confirm_password").querySelector('span');

                if (confirm_password === password && password !== '') {
                    trigger_confirm_password.style.color = "green";
                    trigger_confirm_password.style.fontFamily = "Lexend";
                    trigger_confirm_password.innerText = "Valid password reconfirmation.";
                    return true;
                } else {
                    trigger_confirm_password.style.color = "red";
                    trigger_confirm_password.style.fontFamily = "Lexend";
                    trigger_confirm_password.innerText = "Invalid password reconfirmation!";
                    return false;
                }
            }
            function handleConfirm() {
                var checkconfirmpassword = document.getElementById('check_confirm_password').querySelector('span');
                checkconfirmpassword.innerText = ''; // Xóa thông báo khi phần tử nhận được focus
            }
            document.getElementById('confirm_password').addEventListener('blur', check_confirm_password);
            document.getElementById('confirm_password').addEventListener('focus', handleConfirm);

            // kiểm tra form khi nhấn submit
            function checkForm(event) 
            {
                event.preventDefault();
                var emailValid = checkEmail();
                var passwordValid = check();
                var confirmPasswordValid = check_confirm_password();

                if (emailValid && passwordValid && confirmPasswordValid) {
                    // All validation checks pass, submit the form
                    document.getElementById("myForm").submit();
                    return true;
                } else {
                    // Validation failed, prevent form submission
                    return false;
                }
            }
