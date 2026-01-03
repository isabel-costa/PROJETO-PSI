<?php
use yii\helpers\Html;
$labels = $labels ?? [];
$values = $values ?? [];
/* Register external CSS/JS via Yii view methods */
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css');
$this->registerCssFile('https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css');
$this->registerCssFile('https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.0/css/all.min.css');
$this->registerJsFile('https://code.jquery.com/jquery-3.6.0.min.js', ['position' => \yii\web\View::POS_END]);
$this->registerJsFile('https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js', ['position' => \yii\web\View::POS_END]);
// Per-page CSS
$this->registerCssFile('@web/css/site.css');
// Main template stylesheet (registered instead of static link)
$this->registerCssFile('@web/css/style.css');

$user = Yii::$app->user->identity;
$auth = Yii::$app->authManager;
$userRoles = $user ? $auth->getRolesByUser($user->id) : [];
$roleNames = array_keys($userRoles);

?>
<html>

<head>
    <meta charset="utf-8">
    <title>MEDINOVA - Hospital Website Template</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@400;700&family=Roboto:wght@400;700&display=swap" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">
    <link href="lib/tempusdominus/css/tempusdominus-bootstrap-4.min.css" rel="stylesheet" />

    <!-- Template Stylesheet is registered via registerCssFile -->
</head>

<body>
    <?php if (in_array('doctor', $roleNames)): ?>

        <!-- DASHBOARD DO MÉDICO -->
        <div class="container py-5">
            <h3 class="mb-4">
                Bem-vindo(a), Dr(a). <?= Html::encode($user->username) ?> 👨‍⚕️
            </h3>

            <div class="container py-5">

                <div class="row mt-4">

                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center shadow-sm card-dashboard">
                            <div class="card-body">
                                <i class="fas fa-calendar-day fa-2x mb-2"></i>
                                <h6>Consultas hoje</h6>
                                <h3><?= $consultasHoje ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center shadow-sm card-dashboard">
                            <div class="card-body">
                                <i class="fas fa-clock fa-2x mb-2"></i>
                                <h6>Agendadas</h6>
                                <h3><?= $consultasAgendadas ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center shadow-sm card-dashboard">
                            <div class="card-body">
                                <i class="fas fa-check fa-2x mb-2"></i>
                                <h6>Concluídas</h6>
                                <h3><?= $consultasConcluidas ?></h3>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-3 col-sm-6">
                        <div class="card text-center shadow-sm card-dashboard">
                            <div class="card-body">
                                <i class="fas fa-clipboard-list fa-2x mb-2"></i>
                                <h6>Prescrições</h6>
                                <h3><?= $prescricoesCount ?></h3>
                            </div>
                        </div>
                    </div>

                </div>


                <br>
                <hr class="mt-5">

        <!-- SERVIÇOS DO MÉDICO -->
        <div class="container my-5">
            <h2 class="text-center mb-5">Serviços</h2>

            <div class="d-flex justify-content-center gap-5 flex-wrap servicos-medico">

                <a href="<?= \yii\helpers\Url::to(['consulta/index']) ?>" class="servico-medico text-center">
                    <div class="servico-icon">
                        <i class="bi bi-calendar-event"></i>
                    </div>
                    <span>Agenda</span>
                </a>

                <a href="<?= \yii\helpers\Url::to(['paciente/view']) ?>" class="servico-medico text-center">
                    <div class="servico-icon">
                        <i class="bi bi-journal-medical"></i>
                    </div>
                    <span>Histórico clínico</span>
                </a>

                <a href="<?= \yii\helpers\Url::to(['prescricao/index']) ?>" class="servico-medico text-center">
                    <div class="servico-icon">
                        <i class="bi bi-file-earmark-medical"></i>
                    </div>
                    <span>Prescrições</span>
                </a>

                <a href="<?= \yii\helpers\Url::to(['paciente/index']) ?>" class="servico-medico text-center">
                    <div class="servico-icon">
                        <i class="bi bi-people-fill"></i>
                    </div>
                    <span>Lista Pacientes</span>
                </a>

            </div>

            <hr class="mt-5">
        </div>

            <div class="card mt-5">
                <div class="card-header">
                    <h5>Consultas por mês</h5>
                </div>
                <div class="card-body">
                    <canvas id="graficoConsultasMedico"></canvas>
                </div>
            </div>
        </div>

    <?php else: ?>

        <!-- Banner topo Inicio -->
        <div class="container-fluid bg-primary py-5 mb-5 hero-header">
            <div class="container py-5">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-8">
                        <h1 class="display-1 text-white mb-md-4">Bem-vindo ao SisMedCare</h1>
                        <p class="text-white-50 mb-4 fs-5">Plataforma para auxiliar na gestão de consultas e medicações</p>
                        <div class="pt-2">
                            <a href="#conhecer-app-mobile" class="btn btn-dark rounded-pill py-md-3 px-md-5 mx-2">App Mobile</a>
                            <a href="#conhecer-profissionais" class="btn btn-outline-light btn-cor-secundaria-smc rounded-pill py-md-3 px-md-5 mx-2">Profissionais</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Banner topo Fim -->

        <!-- Sobre Nós Inicio -->
        <div class="container-fluid py-5">
            <div class="container">
                <div class="row gx-5">
                    <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
                        <div class="position-relative h-100">
                            <img class="position-absolute w-100 h-100 rounded" src="img/about.jpg" alt="Sobre nós" style="object-fit: cover;">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <div class="mb-4">
                            <h2 class="display-4">Sobre nós</h2>
                        </div>
                        <p class="fs-6">Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam ipsum et, tempor voluptua sit consetetur sit. Aliquyam diam amet diam et eos sadipscing labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit. Sanctus clita duo justo et tempor consetetur takimata eirmod, dolores takimata consetetur invidunt magna dolores aliquyam dolores dolore. Amet erat amet et magna</p>
                    </div>
                </div>
            </div>
        </div>
        <!-- Sobre Nós Fim -->


        <!-- App Mobile Inicio -->
        <div id="conhecer-app-mobile" class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                    <h2 class="display-4">Conheça a nossa App</h2>
                </div>
                <div class="row gx-5">
                    <div class="col-lg-5 mb-5 mb-lg-0" style="min-height: 500px;">
                        <div class="position-relative h-100">
                            <img class="position-absolute w-100 h-100 rounded" src="img/prototipo_mobile.png" alt="Exemplo de mobile" style="object-fit: contain; object-position: right center;">
                        </div>
                    </div>
                    <div class="col-lg-7">
                        <p class="fs-6">Tempor erat elitr at rebum at at clita aliquyam consetetur. Diam dolor diam ipsum et, tempor voluptua sit consetetur sit.</p>
                        <p class="fs-6">Aliquyam diam amet diam et eos sadipscing labore. Clita erat ipsum et lorem et sit, sed stet no labore lorem sit. Sanctus clita duo justo et tempor consetetur takimata eirmod, dolores takimata consetetur invidunt magna dolores aliquyam dolores dolore.</p>
                        <p class="fs-6">Amet erat amet et magna.</p>
                        <ul class="list-unstyled mt-4">
                            <li class="d-flex align-items-start mb-2">
                                <i class="fa fa-check cor-primaria-smc me-3 mt-1" aria-hidden="true"></i>
                                <span>Consulta de dados clínicos</span>
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <i class="fa fa-check cor-primaria-smc me-3 mt-1" aria-hidden="true"></i>
                                <span>Precrições</span>
                            </li>
                            <li class="d-flex align-items-start mb-2">
                                <i class="fa fa-check cor-primaria-smc me-3 mt-1" aria-hidden="true"></i>
                                <span>Consultas</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
        <!-- App Mobile Fim -->

        <!-- Profissionais Inicio -->
        <div id="conhecer-profissionais" class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5" style="max-width: 800px;">
                    <h2 class="display-4">Os nossos profissionais de saúde</h2>
                </div>
                <div class="owl-carousel team-carousel position-relative">
                    <div class="team-item">
                        <div class="row g-0 bg-light rounded overflow-hidden">
                            <div class="col-12 col-sm-5 h-100">
                                <img class="img-fluid h-100" src="img/team-1.jpg" alt="Profissional 1" style="object-fit: cover;">
                            </div>
                            <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                                <div class="mt-auto p-4">
                                    <h3>Doctor Name</h3>
                                    <h6 class="fw-normal fst-italic cor-secundaria-smc mb-4">Cardiology Specialist</h6>
                                    <p class="m-0">Dolor lorem eos dolor duo eirmod sea. Dolor sit magna rebum clita rebum dolor</p>
                                </div>
                                <div class="d-flex mt-auto border-top p-4">
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle" href="#"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="team-item">
                        <div class="row g-0 bg-light rounded overflow-hidden">
                            <div class="col-12 col-sm-5 h-100">
                                <img class="img-fluid h-100" src="img/team-2.jpg" alt="Profissional 2" style="object-fit: cover;">
                            </div>
                            <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                                <div class="mt-auto p-4">
                                    <h3>Doctor Name</h3>
                                    <h6 class="fw-normal fst-italic cor-secundaria-smc mb-4">Cardiology Specialist</h6>
                                    <p class="m-0">Dolor lorem eos dolor duo eirmod sea. Dolor sit magna rebum clita rebum dolor</p>
                                </div>
                                <div class="d-flex mt-auto border-top p-4">
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle" href="#"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="team-item">
                        <div class="row g-0 bg-light rounded overflow-hidden">
                            <div class="col-12 col-sm-5 h-100">
                                <img class="img-fluid h-100" src="img/team-3.jpg" alt="Profissional 3" style="object-fit: cover;">
                            </div>
                            <div class="col-12 col-sm-7 h-100 d-flex flex-column">
                                <div class="mt-auto p-4">
                                    <h3>Doctor Name</h3>
                                    <h6 class="fw-normal fst-italic cor-secundaria-smc mb-4">Cardiology Specialist</h6>
                                    <p class="m-0">Dolor lorem eos dolor duo eirmod sea. Dolor sit magna rebum clita rebum dolor</p>
                                </div>
                                <div class="d-flex mt-auto border-top p-4">
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-twitter"></i></a>
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-3" href="#"><i class="fab fa-facebook-f"></i></a>
                                    <a class="btn btn-lg btn-dark btn-lg-square rounded-circle" href="#"><i class="fab fa-linkedin-in"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Profissionais Fim -->

        <!-- Pricing Plan Start
        <div class="container-fluid py-5">
        <div class="container">
            <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Medical Packages</h5>
                <h1 class="display-4">Awesome Medical Programs</h1>
            </div>
            <div class="owl-carousel price-carousel position-relative" style="padding: 0 45px 45px 45px;">
                <div class="bg-light rounded text-center">
                    <div class="position-relative">
                        <img class="img-fluid rounded-top" src="img/price-1.jpg" alt="">
                        <div class="position-absolute w-100 h-100 top-50 start-50 translate-middle rounded-top d-flex flex-column align-items-center justify-content-center" style="background: rgba(29, 42, 77, .8);">
                            <h3 class="text-white">Pregnancy Care</h3>
                            <h1 class="display-4 text-white mb-0">
                                <small class="align-top fw-normal" style="font-size: 22px; line-height: 45px;">$</small>49<small class="align-bottom fw-normal" style="font-size: 16px; line-height: 40px;">/ Year</small>
                            </h1>
                        </div>
                    </div>
                    <div class="text-center py-5">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 my-2">Apply Now</a>
                    </div>
                </div>
                <div class="bg-light rounded text-center">
                    <div class="position-relative">
                        <img class="img-fluid rounded-top" src="img/price-2.jpg" alt="">
                        <div class="position-absolute w-100 h-100 top-50 start-50 translate-middle rounded-top d-flex flex-column align-items-center justify-content-center" style="background: rgba(29, 42, 77, .8);">
                            <h3 class="text-white">Health Checkup</h3>
                            <h1 class="display-4 text-white mb-0">
                                <small class="align-top fw-normal" style="font-size: 22px; line-height: 45px;">$</small>99<small class="align-bottom fw-normal" style="font-size: 16px; line-height: 40px;">/ Year</small>
                            </h1>
                        </div>
                    </div>
                    <div class="text-center py-5">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 my-2">Apply Now</a>
                    </div>
                </div>
                <div class="bg-light rounded text-center">
                    <div class="position-relative">
                        <img class="img-fluid rounded-top" src="img/price-3.jpg" alt="">
                        <div class="position-absolute w-100 h-100 top-50 start-50 translate-middle rounded-top d-flex flex-column align-items-center justify-content-center" style="background: rgba(29, 42, 77, .8);">
                            <h3 class="text-white">Dental Care</h3>
                            <h1 class="display-4 text-white mb-0">
                                <small class="align-top fw-normal" style="font-size: 22px; line-height: 45px;">$</small>149<small class="align-bottom fw-normal" style="font-size: 16px; line-height: 40px;">/ Year</small>
                            </h1>
                        </div>
                    </div>
                    <div class="text-center py-5">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 my-2">Apply Now</a>
                    </div>
                </div>
                <div class="bg-light rounded text-center">
                    <div class="position-relative">
                        <img class="img-fluid rounded-top" src="img/price-4.jpg" alt="">
                        <div class="position-absolute w-100 h-100 top-50 start-50 translate-middle rounded-top d-flex flex-column align-items-center justify-content-center" style="background: rgba(29, 42, 77, .8);">
                            <h3 class="text-white">Operation & Surgery</h3>
                            <h1 class="display-4 text-white mb-0">
                                <small class="align-top fw-normal" style="font-size: 22px; line-height: 45px;">$</small>199<small class="align-bottom fw-normal" style="font-size: 16px; line-height: 40px;">/ Year</small>
                            </h1>
                        </div>
                    </div>
                    <div class="text-center py-5">
                        <p>Emergency Medical Treatment</p>
                        <p>Highly Experienced Doctors</p>
                        <p>Highest Success Rate</p>
                        <p>Telephone Service</p>
                        <a href="" class="btn btn-primary rounded-pill py-3 px-5 my-2">Apply Now</a>
                    </div>
                </div>
            </div>
        </div>
        </div>
        Pricing Plan End -->

        <!-- Blog Start
        <div class="container-fluid py-5">
            <div class="container">
                <div class="text-center mx-auto mb-5" style="max-width: 500px;">
                    <h5 class="d-inline-block text-primary text-uppercase border-bottom border-5">Blog Post</h5>
                    <h1 class="display-4">Our Latest Medical Blog Posts</h1>
                </div>
                <div class="row g-5">
                    <div class="col-xl-4 col-lg-6">
                        <div class="bg-light rounded overflow-hidden">
                            <img class="img-fluid w-100" src="img/blog-1.jpg" alt="">
                            <div class="p-4">
                                <a class="h3 d-block mb-3" href="">Dolor clita vero elitr sea stet dolor justo  diam</a>
                                <p class="m-0">Dolor lorem eos dolor duo et eirmod sea. Dolor sit magna
                                    rebum clita rebum dolor stet amet justo</p>
                            </div>
                            <div class="d-flex justify-content-between border-top p-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                                    <small>John Doe</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>12345</small>
                                    <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>123</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <div class="bg-light rounded overflow-hidden">
                            <img class="img-fluid w-100" src="img/blog-2.jpg" alt="">
                            <div class="p-4">
                                <a class="h3 d-block mb-3" href="">Dolor clita vero elitr sea stet dolor justo  diam</a>
                                <p class="m-0">Dolor lorem eos dolor duo et eirmod sea. Dolor sit magna
                                    rebum clita rebum dolor stet amet justo</p>
                            </div>
                            <div class="d-flex justify-content-between border-top p-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                                    <small>John Doe</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>12345</small>
                                    <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>123</small>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-6">
                        <div class="bg-light rounded overflow-hidden">
                            <img class="img-fluid w-100" src="img/blog-3.jpg" alt="">
                            <div class="p-4">
                                <a class="h3 d-block mb-3" href="">Dolor clita vero elitr sea stet dolor justo  diam</a>
                                <p class="m-0">Dolor lorem eos dolor duo et eirmod sea. Dolor sit magna
                                    rebum clita rebum dolor stet amet justo</p>
                            </div>
                            <div class="d-flex justify-content-between border-top p-4">
                                <div class="d-flex align-items-center">
                                    <img class="rounded-circle me-2" src="img/user.jpg" width="25" height="25" alt="">
                                    <small>John Doe</small>
                                </div>
                                <div class="d-flex align-items-center">
                                    <small class="ms-3"><i class="far fa-eye text-primary me-1"></i>12345</small>
                                    <small class="ms-3"><i class="far fa-comment text-primary me-1"></i>123</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        Blog End -->


        <!-- Footer Inicio -->
        <div class="container-fluid footer-smc text-dark mt-5 py-2">
            <div class="container py-5">
                <div class="row g-5">
                    <div class="col-lg-3 col-md-6">
                        <div class="col-lg-3 col-md-6 text-center">
                            <img src="img/SisMedCare_Logo.png" class="img-fluid footer-img" alt="Logo SisMedCare">
                        </div>
                        <br>
                        <p class="mb-4">No dolore ipsum accusam no lorem. Invidunt sed clita kasd clita et et dolor sed dolor</p>
                        <h6 class="cor-secundaria-smc text-uppercase mt-4 mb-3">Ipsum</h6>
                        <div class="d-flex">
                            <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-twitter"></i></a>
                            <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-facebook-f"></i></a>
                            <a class="btn btn-lg btn-dark btn-lg-square rounded-circle me-2" href="#"><i class="fab fa-linkedin-in"></i></a>
                            <a class="btn btn-lg btn-dark btn-lg-square rounded-circle" href="#"><i class="fab fa-instagram"></i></a>
                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6 separador-centrar-footer">
                        <h4 class="d-inline-block cor-secundaria-smc text-uppercase mb-4">SisMedCare</h4>
                        <div class="d-flex flex-column justify-content-start">
                            <a class="text-dark mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Home</a>
                            <a class="text-dark mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Contact Us</a>
                            <a class="text-dark mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Lorem</a>
                            <a class="text-dark mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Invidunt</a>
                            <a class="text-dark mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Dolor</a>
                            <a class="text-dark mb-2" href="#"><i class="fa fa-angle-right me-2"></i>Clita</a>

                        </div>
                    </div>
                    <div class="col-lg-3 col-md-6">
                        <h4 class="d-inline-block cor-secundaria-smc text-uppercase mb-4">Contacte-nos</h4>
                        <p class="mb-2"><i class="fa fa-map-marker-alt cor-primaria-smc me-3"></i>Rua 123, Leiria, Portugal</p>
                        <p class="mb-2"><i class="fa fa-envelope cor-primaria-smc me-3"></i>estudante@exemplo.com</p>
                        <p class="mb-0"><i class="fa fa-phone-alt cor-primaria-smc me-3"></i>+351 912121212</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="container-fluid footer-smc text-dark py-4">
            <div class="container text-center">
                <div class="row g-5">
                    <div class="separator-rights-reserved md-3"></div>
                    <p class="mb-md-0 mt-3">&copy; <a class="cor-secundaria-smc" href="#" style="margin-top: 50px;">2025</a> SisMedCare</p>
                </div>
            </div>
        </div>
        <!-- Footer Fim -->

    <?php endif; ?>

    <!-- Back to Top -->
    <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a>

    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>
    <script src="lib/tempusdominus/js/moment.min.js"></script>
    <script src="lib/tempusdominus/js/moment-timezone.min.js"></script>
    <script src="lib/tempusdominus/js/tempusdominus-bootstrap-4.min.js"></script>

    <!-- Template Javascript -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('graficoConsultasMedico').getContext('2d');

        new Chart(ctx, {
            type: 'bar', // Alterado de 'line' para 'bar'
            data: {
                labels: <?= json_encode($labels) ?>,
                datasets: [{
                    label: 'Consultas',
                    data: <?= json_encode($values) ?>,
                    backgroundColor: 'rgba(54, 162, 235, 0.7)', // cor das barras
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                }
            }
        });
    </script>
    </body>
</html>