<div class="videoWrapper">
    <video playsinline autoplay muted loop  src="<?php echo URL;?>bg.mp4"></video>
</div>

<div class=" h-100 d-flex flex-column ">
    <div class="container-fluid bg-gray my-5">
        <div class="container m-auto">
            <!-- Outer Row -->
            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-12 col-md-9">
                    <div class=" o-hidden border-0  my-5">
                        <div class="card-body p-0">
                            <!-- Nested Row within Card Body -->
                            <div class="row box-dark p-4">
                                <div class="col-12 p-3 d-flex flex-column justify-content-center">

                                    <img style="max-width: 200px" class="img-fluid mb-3 mx-auto"
                                         src="<?php echo URL . LOGO; ?>" alt="">

                                    <div class="text-center mb-5">
                                        <h1 class="h4 text-gray-900 mb-4">Добре дошли в <?php echo BUSSINES; ?>!</h1>

                                        <h4 class="mt-3 text-fifth">Вход в профила Ви</h4>
										<?php if ( isset( $_SESSION['msg'] ) ) { ?>
                                            <h4 class="p-3 rounded bg-danger text-light"><?php echo $_SESSION['msg'];
												session_unset( $_SESSION['msg'] ); ?></h4>
										<?php } ?>
                                    </div>
                                    <form id="loginForm" class="user" method="post"
                                          action="<?php echo URL; ?>login/clientLogin">
                                        <div class="form-group">
                                            <input id="user_name" type="text"
                                                   class="bg-dark-gray form-control form-control-user  border-fifth-2"
                                                   name="user_name" aria-describedby="userName"
                                                   placeholder="Въведете потребителско име">
                                        </div>
                                        <div class="form-group">
                                            <input id="password" type="password"
                                                   class="bg-dark-gray form-control form-control-user  border-fifth-2"
                                                   name="password" placeholder="Въведете парола">
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" id="startOnEnter" name="player"
                                                   value="<?php echo $_GET['video'] ?? ''; ?>">
                                            <button id="enter" class="bg-fifth text-light form-control btn">
                                                Вход
                                            </button>
                                        </div>
                                    </form>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <small class="text-center d-block"></small>
                                            <a href="register/lostpassword"
                                               class="btn bg-fifth text-light font-weight-bold btn-user btn-block">
                                                Загубена парола?
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
    </div>
</div>
<?php
$search = explode('/', $_GET['url']);
//echo '123'.$search[0];
//var_dump($search[0]);
if ( $search[0] == 'fail' ) { ?>
<script>
    const checkSavedLogin = localStorage.getItem('credentials');
    if(checkSavedLogin){
        localStorage.removeItem('credentials');
    }

</script>
<?php }
if ( $search[0] != 'user' ) { ?>
    <script>
        const checkSavedLogin = localStorage.getItem('credentials');
        if(!checkSavedLogin){
            document.getElementById('enter').onclick = _ =>{
                const userName = document.getElementById('user_name').value;
                const userPass = document.getElementById('password').value;
                if(userName.trim() != '' && userPass.trim() != '' ){
                    localStorage.setItem('credentials', [userName, userPass ]);
                }
            }
        } else {
            let receivedCredentials = localStorage.getItem('credentials').split(',');
            document.getElementById('user_name').value = receivedCredentials[0];
            document.getElementById('password').value = receivedCredentials[1];

           // document.getElementById('startOnEnter').value = 'start';
          document.getElementById("loginForm").submit();
        }

    </script>
<?php } ?>

</body>
</html>

