</main>
<div class="fixed-plugin">
  <a class="fixed-plugin-button text-dark position-fixed px-3 py-2">
    <i class="material-symbols-rounded py-2">settings</i>
  </a>
  <div class="card shadow-lg">
    <div class="card-header pb-0 pt-3">
      <div class="float-start">
        <h5 class="mt-3 mb-0">RoomFlow Configurações</h5>
        <p>Veja as configurações disponíveis.</p>
      </div>
      <div class="float-end mt-4">
        <button class="btn btn-link text-dark p-0 fixed-plugin-close-button">
          <i class="material-symbols-rounded">clear</i>
        </button>
      </div>
    </div>
    <hr class="horizontal dark my-1">
    <div class="card-body pt-sm-3 pt-0">
      <div class="mt-3">
        <h6 class="mb-0">Tipo de Sidenav</h6>
        <p class="text-sm">Escolha diferente tipos de sidenav.</p>
      </div>
      <div class="d-flex">
        <button class="btn bg-gradient-dark px-3 mb-2" data-class="bg-gradient-dark" onclick="sidebarType(this)">Escuro</button>
        <button class="btn bg-gradient-dark px-3 mb-2 ms-2" data-class="bg-transparent" onclick="sidebarType(this)">Transparente</button>
        <button class="btn bg-gradient-dark px-3 mb-2  active ms-2" data-class="bg-white" onclick="sidebarType(this)">Claro</button>
      </div>
      <div class="mt-3 d-flex">
        <h6 class="mb-0">Navbar Fixed</h6>
        <div class="form-check form-switch ps-0 ms-auto my-auto">
          <input class="form-check-input mt-1 ms-auto" type="checkbox" id="navbarFixed" onclick="navbarFixed(this)">
        </div>
      </div>
      <hr class="horizontal dark my-3">
      <div class="mt-2 d-flex">
        <h6 class="mb-0">Light / Dark</h6>
        <div class="form-check form-switch ps-0 ms-auto my-auto">
          <input class="form-check-input mt-1 ms-auto" type="checkbox" id="dark-version" onclick="darkMode(this)">
        </div>
      </div>
    </div>
  </div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/simple-datatables@latest" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="/RoomFlow/Public/assets/js/core/popper.min.js"></script>
<script src="/RoomFlow/Public/assets/js/core/bootstrap.min.js"></script>
<script src="/RoomFlow/Public/assets/js/plugins/perfect-scrollbar.min.js"></script>
<script src="/RoomFlow/Public/assets/js/plugins/smooth-scrollbar.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://unpkg.com/imask"></script>
<script src="https://unpkg.com/filepond/dist/filepond.min.js"></script>
<script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sortablejs@latest/Sortable.min.js"></script>

<script>
  var win = navigator.platform.indexOf('Win') > -1;
  if (win && document.querySelector('#sidenav-scrollbar')) {
    var options = {
      damping: '0.5'
    }
    Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
  }
</script>

<?php
$caminho = rtrim(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH), '/');
?>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script async defer src="https://buttons.github.io/buttons.js"></script>
<script src="/RoomFlow/Public/assets/js/material-dashboard.min.js?v=3.2.0"></script>
<script src="/RoomFlow/Public/assets/js/preferences.js"></script>
<script src="/RoomFlow/Public/assets/js/alerts.js"></script>
<script src="/RoomFlow/Public/assets/js/reservas.js"></script>
<script src="https://unpkg.com/imask"></script>
<script src="/RoomFlow/Public/assets/js/masks.js"></script>
<script src="/RoomFlow/Public/assets/js/buscacep.js"></script>
</body>

</html>