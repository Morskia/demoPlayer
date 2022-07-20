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
                                <div class="col-12 p-3 d-flex flex-column justify-content-center">

                                    <img style="max-width: 200px" class="img-fluid mb-3 mx-auto"
                                         src="<?php echo URL . LOGO; ?>" alt="">

                                    <div class="text-center mb-5">
                                        <h1 class="h4 text-gray-900 mb-4">Добре дошли в <?php echo BUSSINES; ?>!</h1>

                                        <h4 class="mt-3 text-fifth">На този екран може да инициализирате нов клиент в
                                            системата на MBOX STUDIOS</h4>

                                        <?php if (isset($_SESSION['msg'])) {
                                            echo "<p class='text-danger my-3'>" . $_SESSION['msg'] . "</p>";
                                            unset($_SESSION['msg']);
                                        } ?>

                                    </div>
                                    <div id="installation" class="text-center " style="display: none">
                                        <div class="lds-roller mx-auto">
                                            <div></div><div></div><div></div><div></div><div></div><div></div><div></div><div></div>
                                        </div>
                                        <p class="d-block mx-auto my-2">Системата се конфигурира</p>
                                        <p class="d-block mx-auto my-2">Моля изчакайте...</p>
                                    </div>
                                    <form class="user" method="post" action="<?php echo URL; ?>complete">
                                        <div class="form-group">
                                            <input type="text"
                                                   class="bg-dark-gray form-control form-control-user  border-fifth-2"
                                                   name="username" aria-describedby="userName"
                                                   placeholder="Въведете потребителско име">
                                        </div>
                                        <div class="form-group">
                                            <input type="text"
                                                   class="bg-dark-gray form-control form-control-user  border-fifth-2"
                                                   name="ip" aria-describedby="ip"
                                                   placeholder="Въведете IP на клиента">
                                        </div>
                                        <div class="form-group">
                                            <input type="hidden" name="player"
                                                   value="<?php echo $_GET['video'] ?? ''; ?>">
                                            <button id="enter" class="bg-fifth text-light form-control btn">
                                                Стартирай акаунт
                                            </button>
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
</div>
<script>
    document.getElementById('enter').onclick = _ => document.getElementById('installation').style.display = 'block';
</script>
</body>
</html>
