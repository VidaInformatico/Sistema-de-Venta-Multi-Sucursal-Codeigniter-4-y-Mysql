document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('select-sucursal')) {
      getSucursales();
    }
})

function getSucursales() {
    const url = base_url + 'sucursal/show';
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        let html = '';
        res.sucursales.forEach(row => {
          html += `<option value="${row.id}" ${row.id == res.sucursal ? 'selected' : ''}>${row.nombre}</option>`;
        });
        document.querySelector('#select-sucursal').innerHTML = html;
      }
    };
  }
  
  function cambiarSucursal(e) {
    const url = base_url + 'superadmin/cambiarSucursal/' + e.target.value;
    const http = new XMLHttpRequest();
    http.open("GET", url, true);
    http.send();
    http.onreadystatechange = function () {
      if (this.readyState == 4 && this.status == 200) {
        const res = JSON.parse(this.responseText);
        Swal.fire({
          position: "top-end",
          icon: res.icono,
          title: res.msg,
          showConfirmButton: false,
          timer: 1500
        });
        if (res.icono == 'success') {
          setTimeout(() => {
            window.location.reload();
          }, 1500);
        }
      }
    };
  }