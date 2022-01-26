<?php $this->layout('layout', ['title' => 'Security']) ?>
<body>
<main id="js-page-content" role="main" class="page-content mt-3">
    <div class="subheader">
        <h1 class="subheader-title">
            <i class='subheader-icon fal fa-lock'></i> Безопасность
        </h1>

    </div>
    <form action="/security/<?= $this->e($user['id']) ?>" method="post"
    ">
    <div class="row">
        <div class="col-xl-6">
            <div id="panel-1" class="panel">
                <div class="panel-container">
                    <div class="panel-hdr">
                        <h2>Обновление эл. адреса и пароля</h2>
                    </div>
                    <div class="panel-content">
                        <!-- email -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Email</label>
                            <input type="text" name="email" id="simpleinput" class="form-control"
                                   value="<?= $this->e($user['email']) ?>">
                            <div class="help-block">Если меняете эл. адрес, письмо будет отправлено на новый эл. адрес</div>
                        </div>

                        <!-- old password -->
                        <?php if (!\App\User::isAdmin()) : ?>
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Старый пароль</label>
                            <input type="password" name="password" id="simpleinput" class="form-control">
                        </div>
                        <?php endif; ?>


                        <!-- password -->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Новый пароль</label>
                            <input type="password" name="newPassword" id="simpleinput" class="form-control">
                        </div>

                        <!-- password confirmation-->
                        <div class="form-group">
                            <label class="form-label" for="simpleinput">Подтверждение нового пароля</label>
                            <input type="password" name="newPasswordAgain" id="simpleinput" class="form-control">
                        </div>


                        <div class="col-md-12 mt-3 d-flex flex-row-reverse">
                            <button class="btn btn-warning">Изменить</button>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    </form>
</main>