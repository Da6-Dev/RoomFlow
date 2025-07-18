<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="apple-touch-icon" sizes="76x76" href="/RoomFlow/public/assets/img/icons/Roomflow.svg">
    <link rel="icon" type="image/png" href="/RoomFlow/public/assets/img/icons/Roomflow.svg">

    <title>
        <?php echo isset($Title) ? htmlspecialchars($Title) . ' - RoomFlow' : 'RoomFlow'; ?>
    </title>

    <link rel="stylesheet" type="text/css" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700,900" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Rounded:opsz,wght,FILL,GRAD@24,400,0,0" />
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0&icon_names=home" />

    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <link href="https://cdn.jsdelivr.net/npm/simple-datatables@latest/dist/style.css" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link href="https://unpkg.com/filepond/dist/filepond.css" rel="stylesheet">

    <link id="pagestyle" href="/RoomFlow/public/assets/css/material-dashboard.min.css" rel="stylesheet" />
    <link href="/RoomFlow/public/assets/css/nucleo-icons.css" rel="stylesheet" />
    <link href="/RoomFlow/public/assets/css/nucleo-svg.css" rel="stylesheet" />
</head>

<body class="g-sidenav-show  bg-gray-100">
    <aside class="sidenav navbar navbar-vertical navbar-expand-xs border-radius-lg fixed-start ms-2  bg-white my-2" id="sidenav-main">
        <div class="sidenav-header">
            <i class="fas fa-times p-3 cursor-pointer text-dark opacity-5 position-absolute end-0 top-0 d-none d-xl-none" aria-hidden="true" id="iconSidenav"></i>
            <a class="navbar-brand px-4 py-3 m-0" href="/RoomFlow">
                <img src="/RoomFlow/public/assets/img/icons/Roomflow.svg" class="navbar-brand-img" width="26" height="26" alt="main_logo">
                <span class="ms-1 text-sm text-dark">RoomFlow</span>
            </a>
        </div>
        <hr class="horizontal dark mt-0 mb-2">
        <div class="collapse navbar-collapse  w-auto " id="sidenav-collapse-main">
            <ul class="navbar-nav">
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Home</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/RoomFlow/">
                        <i class="material-symbols-rounded opacity-5">home</i>
                        <span class="nav-link-text ms-1">Home</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Hospedes</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/Roomflow/Hospedes/Cadastrar/">
                        <i class="material-symbols-rounded opacity-5">person_add</i>
                        <span class="nav-link-text ms-1">Cadastrar</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/RoomFlow/Hospedes/">
                        <i class="material-symbols-rounded opacity-5">group</i>
                        <span class="nav-link-text ms-1">Listar</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Acomodações</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/RoomFlow/Comodidades/">
                        <i class="material-symbols-rounded opacity-5">widgets</i>
                        <span class="nav-link-text ms-1">Comodidades</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/Roomflow/Acomodacoes/Cadastrar">
                        <i class="material-symbols-rounded opacity-5">add_home</i>
                        <span class="nav-link-text ms-1">Cadastrar Acomodação</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/RoomFlow/Acomodacoes/">
                        <i class="material-symbols-rounded opacity-5">apartment</i>
                        <span class="nav-link-text ms-1">Listar Acomodações</span>
                    </a>
                </li>
                <li class="nav-item mt-3">
                    <h6 class="ps-4 ms-2 text-uppercase text-xs text-dark font-weight-bolder opacity-5">Reservas</h6>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/RoomFlow/Reservas/Cadastrar/">
                        <i class="material-symbols-rounded opacity-5">edit_calendar</i>
                        <span class="nav-link-text ms-1">Fazer Reserva</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/RoomFlow/Reservas/">
                        <i class="material-symbols-rounded opacity-5">book_online</i>
                        <span class="nav-link-text ms-1">Listar Reservas</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link text-dark" href="/RoomFlow/Reservas/Historico/">
                        <i class="material-symbols-rounded opacity-5">history</i>
                        <span class="nav-link-text ms-1">Histórico</span>
                    </a>
                </li>
            </ul>
        </div>
    </aside>
    <main class="main-content position-relative max-height-vh-100 h-100 border-radius-lg ">
        <nav class="navbar navbar-main navbar-expand-lg px-0 mx-3 shadow-none border-radius-xl" id="navbarBlur" data-scroll="true">
            <div class="container-fluid py-1 px-3">
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb bg-transparent mb-0 pb-0 pt-1 px-0 me-sm-6 me-5">
                        <li class="breadcrumb-item text-sm"><a class="opacity-5 text-dark" href="javascript:;"><?php echo $father ?></a></li>
                        <li class="breadcrumb-item text-sm text-dark active" aria-current="page"><?php echo $page ?></li>
                    </ol>
                </nav>
                <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
                    <div class="ms-md-auto pe-md-3 d-flex align-items-center">
                        <div class="input-group input-group-outline">
                        </div>
                    </div>
                    <ul class="navbar-nav d-flex align-items-center  justify-content-end">
                        <li class="nav-item d-xl-none ps-3 d-flex align-items-center">
                            <a href="javascript:;" class="nav-link text-body p-0" id="iconNavbarSidenav">
                                <div class="sidenav-toggler-inner">
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                    <i class="sidenav-toggler-line"></i>
                                </div>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>