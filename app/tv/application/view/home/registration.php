<div class=" h-100 d-flex flex-column ">
    <div class="container-fluid bg-gray my-5">
        <div class="container m-auto">
            <!-- Outer Row -->
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class=" o-hidden border-0  my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row p-4">
                                <div class="col-lg-6 d-flex flex-column bg-white justify-content-center align-items-center green-border  py-0">
                                    <figure>
                                        <img src="<?php echo ROOT_PATH.LOGO; ?>" alt="">
                                    </figure>
                                    <h3 class="text-center text-md-left pt-3 pt-md-0">ВКЛЮЧИ СЕ!</h3>

                                    <ul class="fifth-dot list-group text-fifth">
                                        <li class="my-3 ">
                                            <span class="text-green font-weight-bold">Свържете се нас на тел. 359 897 830 803 или на имейл office@mboxbg.net за подписване на договор.</span>
                                        </li>
                                        <li class="my-3">
                                            <span class="text-green font-weight-bold">Получавате подписан договор за публично изпълнение, музикалния каталог, както и лицензионен стикер удостоверяващ ползването на легална музика.</span>
                                        </li>
                                        <li class="my-3">
                                            <span class="text-green font-weight-bold">Готови сте да озвучавате легално търговския обект</span>
                                        </li>

                                    </ul>
                                </div>
                                <div class="col-lg-6 p-3 d-flex flex-column justify-content-center">

                                    <div class="text-center mb-5">
                                        <h1 class="h4 text-gray-900 mb-4">Добре дошли в <?php echo BUSSINES; ?>!</h1>

                                        <h4 class="mt-3 text-fifth">Регистрация</h4>
                                        <?php if(isset( $_SESSION['msg'])) { ?>
                                        <h4 class="p-3 rounded bg-danger text-light"><?php echo  $_SESSION['msg']; session_unset($_SESSION['msg']); ?></h4>
                                        <?php } ?>
                                    </div>
                                    <form class="user" method="post" action="<?php echo URL; ?>register/registration" autocomplete="off">
                                        <div class="form-group">
                                            <input type="text" required
                                                   class="bg-dark-gray border-fifth-2 form-control form-control-user"
                                                   name="realname"
                                                   aria-describedby="realName" placeholder="Име и фамилия">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" required
                                                   class="bg-dark-gray form-control border-fifth-2 form-control-user"
                                                   name="email"
                                                   aria-describedby="userName" placeholder="Вашият Email">
                                        </div>
                                        <div class="form-group">
                                            <input type="text" required
                                                   class="bg-dark-gray form-control border-fifth-2 form-control-user"
                                                   name="phone"
                                                   aria-describedby="userName" placeholder="Вашият телефон">
                                        </div>
                                        <div class="form-group">
                                            <input type="password" autocomplete="off"  autocomplete="new-password" required
                                                   class="bg-dark-gray form-control border-fifth-2 form-control-user"
                                                   name="password" placeholder="Вашата парола">
                                        </div>
                                        <div class="form-group">
                                            <button class="bg-fifth text-light form-control btn">
                                                Регистрирай се
                                            </button>
                                        </div>
                                    </form>
                                        <div class="row my-5">
                                            <div class="col-md-6 col-sm-12">
                                                <small class="text-center d-block" >Вече притежавате акаунт?</small>
                                                <a href="login" class="btn bg-fifth font-weight-bold text-light btn-user btn-block">
                                                    Към вход
                                                </a>
                                            </div>
                                            <div class="col-md-6 col-sm-12">
                                                <small class="text-center d-block">Загубена парола?</small>
                                                <a href="register/lostpassword" class="btn bg-fifth text-light font-weight-bold btn-user btn-block">
                                                    Създай нова!
                                                </a>
                                            </div>
                                        </div>

                                        <a href="<?php echo URL; ?>" class="btn bg-fifth text-light font-weight-bold btn-user btn-block">
                                            Към уебсайта
                                        </a>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
