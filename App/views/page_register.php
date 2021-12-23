<?php $this->layout('layout', ['title' => 'Registration']) ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="description" content="Login">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no, user-scalable=no, minimal-ui">
    <!-- Call App Mode on ios devices -->
    <meta name="apple-mobile-web-app-capable" content="yes" />
    <!-- Remove Tap Highlight on Windows Phone IE -->
    <meta name="msapplication-tap-highlight" content="no">
    <!-- base css -->
    <link id="vendorsbundle" rel="stylesheet" media="screen, print" href="/App/views/css/vendors.bundle.css">
    <link id="appbundle" rel="stylesheet" media="screen, print" href="/App/views/css/app.bundle.css">
    <link id="mytheme" rel="stylesheet" media="screen, print" href="#">
    <link id="myskin" rel="stylesheet" media="screen, print" href="/App/views/css/skins/skin-master.css">
    <!-- Place favicon.ico in the root directory -->
    <link rel="apple-touch-icon" sizes="180x180" href="/App/views/img/favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="img/favicon/favicon-32x32.png">
    <link rel="mask-icon" href="/App/views/img/favicon/safari-pinned-tab.svg" color="#5bbad5">
    <link rel="stylesheet" media="screen, print" href="/App/views/css/fa-brands.css">
</head>
<body>
    <div class="page-wrapper auth">
        <div class="page-inner bg-brand-gradient">
            <div class="page-content-wrapper bg-transparent m-0">
                <div class="flex-1" style="background: url(img/svg/pattern-1.svg) no-repeat center bottom fixed; background-size: cover;">
                    <div class="container py-4 py-lg-5 my-lg-5 px-4 px-sm-0">
                        <div class="row">
                            <div class="col-xl-12">
                                <h2 class="fs-xxl fw-500 mt-4 text-white text-center">
                                    Registration
                                </h2>
                            </div>
                            <div class="col-xl-6 ml-auto mr-auto">
                                <div class="card p-4 rounded-plus bg-faded">
                                        <?php echo flash()->display(); ?>
                                    <form id="js-login" method="post" novalidate="" action="registration">
                                        <div class="form-group">
                                            <label class="form-label">Username</label>
                                            <input type="text" name="username" class="form-control" placeholder="Username" required>
                                            <div class="invalid-feedback">Заполните поле.</div>
                                            <div class="help-block">Эл. адрес будет вашим логином при авторизации</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="emailverify">Email</label>
                                            <input type="email" name="email" id="emailverify" class="form-control" placeholder="Email" required>
                                            <div class="invalid-feedback">Заполните поле.</div>
                                            <div class="help-block">Эл. адрес будет вашим логином при авторизации</div>
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label" for="userpassword">Password<br></label>
                                            <input type="password" name="password" id="userpassword" class="form-control" placeholder="" required>
                                            <div class="invalid-feedback">Заполните поле.</div>
                                        </div>

                                        <div class="row no-gutters">
                                            <div class="col-md-4 ml-auto text-right">
                                                <button id="js-login-btn" type="submit" class="btn btn-block btn-danger btn-lg mt-3">Registration</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="/App/views/js/vendors.bundle.js"></script>
    <script>
        $("#js-login-btn").click(function(event)
        {

            // Fetch form to apply custom Bootstrap validation
            var form = $("#js-login")

            if (form[0].checkValidity() === false)
            {
                event.preventDefault()
                event.stopPropagation()
            }

            form.addClass('was-validated');
            // Perform ajax submit here...
        });

    </script>
</body>
</html>
