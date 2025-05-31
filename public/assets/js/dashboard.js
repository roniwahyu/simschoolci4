/**
 * School Information System Dashboard JavaScript
 */

// Initialize DataTables
function initDataTables() {
    if ($.fn.DataTable) {
        $('.datatable').DataTable({
            responsive: true,
            language: {
                search: "_INPUT_",
                searchPlaceholder: "Search..."
            },
            dom: '<"top"<"left-col"l><"center-col"B><"right-col"f>>rtip',
            buttons: [
                {
                    extend: 'excel',
                    className: 'btn btn-sm btn-success'
                },
                {
                    extend: 'pdf',
                    className: 'btn btn-sm btn-danger'
                },
                {
                    extend: 'print',
                    className: 'btn btn-sm btn-info'
                }
            ],
            lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]]
        });
    }
}

// Initialize Select2
function initSelect2() {
    if ($.fn.select2) {
        $('.select2').select2({
            placeholder: "Select an option",
            allowClear: true,
            width: '100%'
        });
    }
}

// Initialize SweetAlert for confirmations
function initSweetAlert() {
    // Delete confirmation
    $(document).on('click', '.btn-delete', function(e) {
        e.preventDefault();
        const deleteUrl = $(this).attr('href');
        
        Swal.fire({
            title: 'Are you sure?',
            text: "You won't be able to revert this!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#e74a3b',
            cancelButtonColor: '#858796',
            confirmButtonText: 'Yes, delete it!'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = deleteUrl;
            }
        });
    });
}

// Initialize Toasts
function initToasts() {
    $('.toast').toast('show');
}

// Room Management Specific Functions
function initRoomManagement() {
    // Room type selection changes
    $('#room_type_id').on('change', function() {
        const roomTypeId = $(this).val();
        if (roomTypeId) {
            // You can add AJAX call here to load room type specific data
            console.log('Room type selected:', roomTypeId);
        }
    });
    
    // Room status toggle
    $('.room-status-toggle').on('change', function() {
        const roomId = $(this).data('room-id');
        const isActive = $(this).prop('checked');
        
        // AJAX call to update room status
        $.ajax({
            url: baseUrl + 'room/updateStatus',
            type: 'POST',
            data: {
                id: roomId,
                is_active: isActive ? 1 : 0
            },
            success: function(response) {
                const data = JSON.parse(response);
                if (data.success) {
                    showToast('Success', data.message, 'success');
                } else {
                    showToast('Error', data.message, 'danger');
                }
            },
            error: function() {
                showToast('Error', 'Failed to update room status', 'danger');
            }
        });
    });
}

// Show toast message
function showToast(title, message, type = 'info') {
    const toast = `
        <div class="toast toast-${type}" role="alert" aria-live="assertive" aria-atomic="true" data-delay="5000">
            <div class="toast-header">
                <strong class="mr-auto">${title}</strong>
                <button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="toast-body">
                ${message}
            </div>
        </div>
    `;
    
    $('#toastContainer').append(toast);
    $('.toast:last').toast('show');
    
    // Remove toast after it's hidden
    $('.toast:last').on('hidden.bs.toast', function() {
        $(this).remove();
    });
}

// Document Ready
$(document).ready(function() {
    // Initialize all components
    initDataTables();
    initSelect2();
    initSweetAlert();
    initToasts();
    
    // Initialize module specific functions
    if ($('#roomManagement').length) {
        initRoomManagement();
    }
    
    // Toggle sidebar
    $('#sidebarToggle').on('click', function(e) {
        e.preventDefault();
        $('body').toggleClass('sidebar-toggled');
        $('.sidebar').toggleClass('toggled');
    });
    
    // Close sidebar when window is less than 768px
    if ($(window).width() < 768) {
        $('.sidebar').addClass('toggled');
    }
    
    // Prevent the content wrapper from scrolling when the fixed side navigation hovered over
    $('body.fixed-nav .sidebar').on('mousewheel DOMMouseScroll wheel', function(e) {
        if ($(window).width() > 768) {
            const e0 = e.originalEvent;
            const delta = e0.wheelDelta || -e0.detail;
            this.scrollTop += (delta < 0 ? 1 : -1) * 30;
            e.preventDefault();
        }
    });
    
    // Scroll to top button
    $(document).on('scroll', function() {
        const scrollDistance = $(this).scrollTop();
        if (scrollDistance > 100) {
            $('.scroll-to-top').fadeIn();
        } else {
            $('.scroll-to-top').fadeOut();
        }
    });
    
    $(document).on('click', 'a.scroll-to-top', function(e) {
        const anchor = $(this);
        $('html, body').stop().animate({
            scrollTop: ($(anchor.attr('href')).offset().top)
        }, 1000, 'easeInOutExpo');
        e.preventDefault();
    });
});