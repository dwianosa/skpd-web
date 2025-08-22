// Admin Dashboard JavaScript

// Simple toast notification function
function showToast(message, type = 'info') {
    // Remove existing toasts
    $('.toast-notification').remove();
    
    // Create toast element
    var toastHtml = `
        <div class="toast-notification position-fixed" style="top: 20px; right: 20px; z-index: 9999;">
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                <i class="fas fa-${type === 'success' ? 'check-circle' : type === 'error' ? 'exclamation-circle' : 'info-circle'}"></i>
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        </div>
    `;
    
    // Add toast to body
    $('body').append(toastHtml);
    
    // Auto remove after 3 seconds
    setTimeout(function() {
        $('.toast-notification').fadeOut(function() {
            $(this).remove();
        });
    }, 3000);
}

$(document).ready(function() {
    // Auto refresh dashboard data setiap 30 detik
    setInterval(function() {
        refreshDashboardStats();
    }, 30000);
    
    // Initialize tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
    
    // Smooth scroll untuk anchor links
    $('a[href^="#"]').on('click', function(event) {
        var target = $(this.getAttribute('href'));
        if (target.length) {
            event.preventDefault();
            $('html, body').stop().animate({
                scrollTop: target.offset().top - 100
            }, 1000);
        }
    });
    
    // Animate counters
    animateCounters();
    
    // Initialize chart animations
    initializeChartAnimations();
});

// Refresh dashboard statistics
function refreshDashboardStats() {
    $.ajax({
        url: '/admin/dashboard/stats',
        type: 'GET',
        success: function(response) {
            if (response.success) {
                updateDashboardStats(response.data);
            }
        },
        error: function() {
            console.log('Gagal refresh dashboard stats');
        }
    });
}

// Update dashboard statistics
function updateDashboardStats(data) {
    // Update counter cards dengan animasi
    animateCounter('#totalSurat', data.totalSurat);
    animateCounter('#suratPending', data.suratPending);
    animateCounter('#suratDisetujui', data.suratDisetujui);
    animateCounter('#suratDitolak', data.suratDitolak);
    
    // Update info bulan ini
    $('#suratBulanIni').text(data.suratBulanIni);
    
    // Update chart jika ada
    if (window.myChart && data.chartData) {
        updateChart(data.chartData);
    }
}

// Animate counter numbers
function animateCounter(selector, targetValue) {
    $({ Counter: 0 }).animate({
        Counter: targetValue
    }, {
        duration: 1000,
        easing: 'swing',
        step: function() {
            $(selector).text(Math.ceil(this.Counter));
        }
    });
}

// Animate all counters on page load
function animateCounters() {
    $('.h5.mb-0.font-weight-bold.text-gray-800').each(function() {
        var $this = $(this);
        var countTo = parseInt($this.text()) || 0;
        
        $({ countNum: 0 }).animate({
            countNum: countTo
        }, {
            duration: 2000,
            easing: 'linear',
            step: function() {
                $this.text(Math.floor(this.countNum));
            },
            complete: function() {
                $this.text(countTo);
            }
        });
    });
}

// Initialize chart animations
function initializeChartAnimations() {
    // Animate chart bars on scroll
    $(window).scroll(function() {
        var chartOffset = $('#chartSurat').offset().top;
        var scrollTop = $(window).scrollTop();
        var windowHeight = $(window).height();
        
        if (scrollTop + windowHeight > chartOffset && !window.chartAnimated) {
            window.chartAnimated = true;
            animateChartBars();
        }
    });
}

// Animate chart bars
function animateChartBars() {
    $('.progress-bar').each(function() {
        var $this = $(this);
        var width = $this.attr('style').match(/width:\s*(\d+%)/)[1];
        
        $this.css('width', '0%').animate({
            width: width
        }, 1500, 'easeOutQuart');
    });
}

// Update chart data
function updateChart(chartData) {
    if (window.myChart) {
        window.myChart.data.labels = chartData.labels;
        window.myChart.data.datasets[0].data = chartData.data;
        window.myChart.update();
    }
}

// Export dashboard data to CSV
function exportDashboardData() {
    var data = {
        totalSurat: $('#totalSurat').text(),
        suratPending: $('#suratPending').text(),
        suratDisetujui: $('#suratDisetujui').text(),
        suratDitolak: $('#suratDitolak').text(),
        suratBulanIni: $('#suratBulanIni').text(),
        exportDate: new Date().toLocaleString('id-ID')
    };
    
    var csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Statistik Dashboard SKPD Kominfo Bukittinggi\n";
    csvContent += "Tanggal Export," + data.exportDate + "\n";
    csvContent += "Total Surat," + data.totalSurat + "\n";
    csvContent += "Surat Pending," + data.suratPending + "\n";
    csvContent += "Surat Disetujui," + data.suratDisetujui + "\n";
    csvContent += "Surat Ditolak," + data.suratDitolak + "\n";
    csvContent += "Surat Bulan Ini," + data.suratBulanIni + "\n";
    
    var encodedUri = encodeURI(csvContent);
    var link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", "dashboard-stats-" + new Date().toISOString().split('T')[0] + ".csv");
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Download dashboard report as DOCX
function downloadDashboardDocx() {
    // Tampilkan loading message
    showToast('Mengunduh DOCX...', 'info');
    
    // Tutup modal jika terbuka
    var printModal = bootstrap.Modal.getInstance(document.getElementById('printOptionsModal'));
    if (printModal) {
        printModal.hide();
    }
    
    // Coba download DOCX
    setTimeout(function() {
        window.location.href = '/admin/dashboard/export-docx';
    }, 500);
}

// Download dashboard report as HTML (alternative)
function downloadDashboardHtml() {
    // Tampilkan loading message
    showToast('Mengunduh HTML...', 'info');
    
    // Tutup modal jika terbuka
    var printModal = bootstrap.Modal.getInstance(document.getElementById('printOptionsModal'));
    if (printModal) {
        printModal.hide();
    }
    
    // Download HTML sebagai alternatif
    setTimeout(function() {
        window.location.href = '/admin/dashboard/export-html';
    }, 500);
}

// Print dashboard with options
function printDashboard() {
    // Buat modal untuk pilihan cetak
    var modal = `
        <div class="modal fade" id="printOptionsModal" tabindex="-1" aria-labelledby="printOptionsModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="printOptionsModalLabel">
                            <i class="fas fa-print"></i> Pilihan Cetak Dashboard
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-print fa-3x text-primary"></i>
                                        </div>
                                        <h6 class="card-title">Cetak Dashboard</h6>
                                        <p class="card-text small text-muted">Cetak dashboard dalam format PDF</p>
                                        <button type="button" class="btn btn-primary btn-sm">
                                            <i class="fas fa-print"></i> Cetak PDF
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-file-word fa-3x text-success"></i>
                                        </div>
                                        <h6 class="card-title">Download DOCX</h6>
                                        <p class="card-text small text-muted">Download dashboard dalam format DOCX</p>
                                        <button type="button" class="btn btn-success btn-sm">
                                            <i class="fas fa-download"></i> Download DOCX
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="card border-0 shadow-sm h-100">
                                    <div class="card-body text-center">
                                        <div class="mb-3">
                                            <i class="fas fa-file-code fa-3x text-info"></i>
                                        </div>
                                        <h6 class="card-title">Download HTML</h6>
                                        <p class="card-text small text-muted">Download dashboard dalam format HTML (Alternatif)</p>
                                        <button type="button" class="btn btn-info btn-sm">
                                            <i class="fas fa-download"></i> Download HTML
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Hapus modal lama jika ada
    $('#printOptionsModal').remove();
    
    // Tambahkan modal ke body
    $('body').append(modal);
    
    // Tampilkan modal
    var printModal = new bootstrap.Modal(document.getElementById('printOptionsModal'));
    printModal.show();
    
    // Add event listeners after modal is shown
    setTimeout(function() {
        // DOCX button
        $('#printOptionsModal .btn-success').on('click', function() {
            downloadDashboardDocx();
        });
        
        // HTML button
        $('#printOptionsModal .btn-info').on('click', function() {
            downloadDashboardHtml();
        });
        
        // PDF button
        $('#printOptionsModal .btn-primary').on('click', function() {
            printDashboardPDF();
        });
    }, 100);
}

// Print dashboard as PDF
function printDashboardPDF() {
    // Tutup modal
    var printModal = bootstrap.Modal.getInstance(document.getElementById('printOptionsModal'));
    if (printModal) {
        printModal.hide();
    }
    
    // Buat tampilan cetak khusus yang compact
    var printContent = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>Dashboard Admin SKPD - Cetak</title>
            <style>
                @media print {
                    body { margin: 0; padding: 20px; font-family: Arial, sans-serif; }
                    .print-header { text-align: center; margin-bottom: 20px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                    .print-title { font-size: 24px; font-weight: bold; color: #333; margin: 0; }
                    .print-subtitle { font-size: 14px; color: #666; margin: 5px 0; }
                    .stats-grid { display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px; margin: 20px 0; }
                    .stat-card { border: 1px solid #ddd; padding: 15px; text-align: center; background: #f9f9f9; }
                    .stat-number { font-size: 28px; font-weight: bold; color: #333; margin: 5px 0; }
                    .stat-label { font-size: 12px; color: #666; text-transform: uppercase; }
                    .chart-section { margin: 20px 0; }
                    .chart-title { font-size: 18px; font-weight: bold; margin-bottom: 15px; color: #333; }
                    .chart-placeholder { height: 200px; border: 1px solid #ddd; background: #f9f9f9; display: flex; align-items: center; justify-content: center; color: #666; }
                    .footer { margin-top: 30px; text-align: center; font-size: 12px; color: #666; border-top: 1px solid #ddd; padding-top: 15px; }
                    .page-break { page-break-after: always; }
                    @page { size: A4; margin: 1cm; }
                }
            </style>
        </head>
        <body>
            <div class="print-header">
                <h1 class="print-title">DASHBOARD ADMIN SKPD</h1>
                <p class="print-subtitle">Kominfo Bukittinggi</p>
                <p class="print-subtitle">Tanggal Cetak: ${new Date().toLocaleDateString('id-ID')}</p>
            </div>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-number">${$('#totalSurat').text()}</div>
                    <div class="stat-label">Total Surat</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${$('#suratPending').text()}</div>
                    <div class="stat-label">Menunggu</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${$('#suratDisetujui').text()}</div>
                    <div class="stat-label">Disetujui</div>
                </div>
                <div class="stat-card">
                    <div class="stat-number">${$('#suratDitolak').text()}</div>
                    <div class="stat-label">Ditolak</div>
                </div>
            </div>
            
            <div class="chart-section">
                <h3 class="chart-title">Statistik Pengajuan Surat (6 Bulan Terakhir)</h3>
                <div class="chart-placeholder">
                    Grafik statistik surat - Data tersedia di dashboard online
                </div>
            </div>
            
            <div class="footer">
                <p>Dicetak dari Dashboard Admin SKPD Kominfo Bukittinggi</p>
                <p>Halaman 1 dari 1</p>
            </div>
        </body>
        </html>
    `;
    
    // Buat window baru untuk cetak
    var printWindow = window.open('', '_blank');
    printWindow.document.write(printContent);
    printWindow.document.close();
    
    // Tunggu sampai konten ter-load, lalu cetak
    printWindow.onload = function() {
        printWindow.print();
        // Tutup window setelah cetak
        setTimeout(function() {
            printWindow.close();
        }, 1000);
    };
}

// Toggle dark mode
function toggleDarkMode() {
    $('body').toggleClass('dark-mode');
    localStorage.setItem('darkMode', $('body').hasClass('dark-mode'));
}

// Check dark mode preference
function checkDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        $('body').addClass('dark-mode');
    }
}

// Initialize dark mode
$(document).ready(function() {
    checkDarkMode();
});

// Add export/print/docx buttons to dashboard
$(document).ready(function() {
    var actionButtons = `
        <div class="d-flex gap-2">
            <button onclick="exportDashboardData()" class="btn btn-outline-success btn-sm" title="Export Data (CSV)">
                <i class="fas fa-download"></i> Export CSV
            </button>
            <button onclick="downloadDashboardDocx()" class="btn btn-outline-info btn-sm" title="Unduh DOCX">
                <i class="fas fa-file-word"></i> Unduh DOCX
            </button>
            <button onclick="toggleDarkMode()" class="btn btn-outline-secondary btn-sm" title="Toggle Dark Mode">
                <i class="fas fa-moon"></i>
            </button>
        </div>
    `;
    
    $('.card-header:contains("Quick Actions")').append(actionButtons);
});

// Add loading states to buttons
$('.btn').on('click', function() {
    var $btn = $(this);
    var originalText = $btn.html();
    
    $btn.html('<i class="fas fa-spinner fa-spin"></i> Loading...');
    $btn.prop('disabled', true);
    
    // Re-enable button after 2 seconds (for demo purposes)
    setTimeout(function() {
        $btn.html(originalText);
        $btn.prop('disabled', false);
    }, 2000);
});

// Add smooth hover effects
$('.card').hover(
    function() {
        $(this).addClass('shadow-lg');
    },
    function() {
        $(this).removeClass('shadow-lg');
    }
);

// Add notification system
function showNotification(message, type = 'info') {
    var alertClass = 'alert-' + type;
    var icon = type === 'success' ? 'check-circle' : 
               type === 'warning' ? 'exclamation-triangle' : 
               type === 'danger' ? 'times-circle' : 'info-circle';
    
    var notification = `
        <div class="alert ${alertClass} alert-dismissible fade show position-fixed" 
             style="top: 20px; right: 20px; z-index: 9999; min-width: 300px;">
            <i class="fas fa-${icon}"></i> ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    `;
    
    $('body').append(notification);
    
    // Auto remove after 5 seconds
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}

// Show welcome notification
$(document).ready(function() {
    setTimeout(function() {
        showNotification('Selamat datang di Dashboard Admin SKPD Kominfo Bukittinggi!', 'success');
    }, 1000);
});
