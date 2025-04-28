/** @format */
const base_url = (path) => {
  const origin = window.location.origin;
  const pathname = window.location.pathname;

  // Ambil folder project (hanya bagian pertama setelah domain)
  const pathParts = pathname.split('/').filter((part) => part !== '');
  const basePath = pathParts.length > 0 ? `/${pathParts[0]}` : '';

  return origin + basePath + (path.startsWith('/') ? path : `/${path}`);
};

((window, document, $) => {
  /**
   * number only
   *
   * @format
   */
  const numOnly = (string) => {
    if (!string) return null;
    return string.replace(/[^\d]/g, '');
  };

  $(document).on('input', 'input.numberOnly', (e) => {
    const _this = e.currentTarget;
    _this.value = numOnly(_this.value);
  });

  /**
   * Toastr From Sweetalert2
   */
  const Toastr = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
  });

  /**
   * Modal Dynamic
   */
  $(document).on('click', '.open-modal', (event) => {
    event.preventDefault();
    const modalName = '.modal-dynamic';
    const modals = $(modalName);
    const _this = event.currentTarget;
    const data = _this.dataset;

    /* Ajax */
    $.ajax({
      cache: false,
      method: 'post',
      url: data.url,
      data: data,
      success: (result) => {
        const { classDialog, title, body, footer } = result.data;
        const modalDialog = modals.find('#modal-dialog');
        modalDialog.addClass(
          `modal-dialog modal-dialog-scrollable ${classDialog || 'modal-lg'}`,
        );
        modals.find('.modal-title').html(title);
        modals.find('.modal-body').html(body);
        if (footer) modals.find('.modal-footer').removeClass('d-none');
      }, //success
      complete: (result, status) => {
        const { code, option = { backdrop: true, keyboard: true } } = result.responseJSON;
        if (code == 200) {
          const newModal = new bootstrap.Modal(modalName, option);
          newModal.show();
        }

        modals.on('hidden.bs.modal', (event) => {
          const _this = $(event.currentTarget);
          _this.find('#modal-dialog').removeClass();
          _this.find('.modal-title').html('');
          _this.find('.modal-body').html('');
          _this.find('.modal-footer').addClass('d-none');
        });

        initDatepickers();
        initValidates();
        initMask();
      },
    });
  });

  /**
   * Swal Confirm
   */
  $(document).on('click', '.swal-confirm', (event) => {
    event.preventDefault();

    const {
      id,
      url,
      title = 'Konfirmasi',
      message = 'Yakin mau dihapus?',
      icon = 'warning',
    } = event.currentTarget.dataset;

    Swal.fire({
      title: title,
      html: `<p>${message}</p>`,
      icon: icon,
      showConfirmButton: true,
      showCancelButton: true,
      confirmButtonText: 'Ya',
      cancelButtonText: 'Tidak',
      confirmButtonColor: '#dc3545',
      cancelButtonColor: '#6c757d',
    }).then((result) => {
      if (result.isConfirmed) {
        // Handle Confirm
        if (id && url) {
          $.ajax({
            cache: false,
            url: url,
            method: 'post',
            data: { id: id },
            success: (result) => {
              userDatatable.ajax.reload();

              const { code, message } = result;
              const icon = code == 200 ? 'success' : 'error';
              Toastr.fire({
                icon: icon,
                title: message,
              });
            },
          });
        }
      }
    });
  });

  /**
   * Initialize DataTable
   */
  const tableUser = $('table.datatables#user');
  const userDatatable = new DataTable(tableUser, {
    autoWidth: false,
    responsive: true,
    processing: true,
    serverSide: true,
    info: true,
    pageLength: 10,
    ajax: {
      url: tableUser.data('url'),
      type: 'post',
    },
    order: [],
    ordering: false,
    columns: [
      { data: 'nama' },
      { data: 'tanggal_lahir' },
      { data: 'alamat' },
      {
        data: 'email',
        render: (data, type, row) => {
          return `<a href="mailto:${data}">${data}</a>`;
        },
      },
      {
        data: 'no_telp',
        render: (data, type, row) => {
          return `<div class="no_telp">${data}</div>`;
        },
      },
      {
        data: 'id',
        render: (data, type, row) => {
          return `
            <div class="d-flex justify-content-center flex-wrap gap-1">
              <button
                class="btn btn-primary btn-sm open-modal"
                data-url="${base_url('ajax/edit_user.php')}"
                data-id="${data}"
              >
                <i class="fas fa-edit"></i>
              </button>
              <button
                class="btn btn-danger btn-sm swal-confirm"
                data-url="${base_url('action/delete_user.php')}"
                data-id="${data}"
                data-title="Konfirmasi"
                data-icon="warning"
                data-message="Apakah anda yakin ingin menghapus user dengan nama <b>${
                  row.nama
                }</b> ?"
              >
                <i class="fas fa-trash"></i>
              </button>
            </div>
          `;
        },
      },
    ],
    columnDefs: [
      { responsivePriority: 3, targets: 0 },
      { responsivePriority: 2, targets: 1 },
      { responsivePriority: 1, targets: -1 },
    ],
    language: {
      sLengthMenu: 'Show _MENU_',
      search: '',
      searchPlaceholder: 'Search',
      paginate: {
        first: '<i class="fas fa-angles-left"></i>',
        last: '<i class="fas fa-angles-right"></i>',
        previous: '<i class="fas fa-chevron-left"></i>',
        next: '<i class="fas fa-chevron-right"></i>',
      },
      zeroRecords: 'Nothing Found - Sorry',
      info: 'Showing page _PAGE_ of _PAGES_',
      infoFiltered: '',
      infoEmpty: 'No records available',
      sLoadingRecords: null,
    },
    responsive: { details: false },
    drawCallback: function (result) {
      const { json: data } = result;

      const dataTablesPaginate = $('.dataTables_paginate');
      dataTablesPaginate.hide();
      if (data.recordsFiltered) {
        dataTablesPaginate.show();
        initMask();
      }
    },
    initComplete: function () {
      tableUser.find('thead th').addClass('text-center');
    },
  });

  /**
   * Initialize Bootstrap Datetimepicker
   */
  const initDatepickers = (selector = '.tanggal') => {
    document.querySelectorAll(selector).forEach((el) => {
      if (!el.classList.contains('td-initialized')) {
        new tempusDominus.TempusDominus(el, {
          display: {
            components: {
              calendar: true,
              date: true,
              month: true,
              year: true,
              clock: false,
              hours: false,
              minutes: false,
              seconds: false,
            },
            buttons: {
              today: true,
              clear: true,
              close: true,
            },
            theme: 'light',
          },
          localization: {
            format: 'yyyy-MM-dd',
          },
        });

        el.classList.add('td-initialized');
      }
    });
  };

  /**
   * Initialize Jquery Validate Submit With Ajax
   */
  const initValidates = (selector = 'form#formValidate') => {
    const rules = {};

    /**
     * rules maxlength menggunakan attribute data-maxlength
     */
    $(selector)
      .find(':input[name]')
      .each(function () {
        const $el = $(this);
        const name = $el.attr('name');
        rules[name] = {};
        if ($el.data('maxlength')) {
          rules[name].maxlength = parseInt($el.data('maxlength'));
        }
      });

    $(selector).validate({
      rules: rules,
      submitHandler: function (form) {
        $.ajax({
          cache: false,
          url: form.action,
          method: form.method,
          data: new FormData(form),
          contentType: false,
          processData: false,
          success: function (result) {
            $('.modal').modal('hide');
            userDatatable.ajax.reload();

            const { code, message } = result;
            const icon = code == 200 ? 'success' : 'error';
            Toastr.fire({
              icon: icon,
              title: message,
            });
          },
        });
      },
    });
  };

  /**
   * Initialize Jquery Mask
   */
  const initMask = (selector = '.no_telp') => {
    $(selector).mask('0000-0000-0000', { reverse: true });
  };

  $(() => {
    initDatepickers();
    initMask();
  });
})(window, document, jQuery);
