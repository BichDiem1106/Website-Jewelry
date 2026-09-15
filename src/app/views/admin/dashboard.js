document.addEventListener("DOMContentLoaded", () => {
    // ==========================================
    // 1. KHỞI TẠO BIỂU ĐỒ DOANH SỐ (CHART.JS)
    // ==========================================
    const chartCanvas = document.getElementById("salesTrendsChart");
    if (chartCanvas && typeof Chart !== "undefined") {
        const ctx = chartCanvas.getContext("2d");

        // Hiệu ứng Gradient mờ sang trọng
        const fillGradient = ctx.createLinearGradient(0, 0, 0, 320);
        fillGradient.addColorStop(0, "rgba(197, 168, 128, 0.28)");
        fillGradient.addColorStop(1, "rgba(197, 168, 128, 0.0)");

        new Chart(ctx, {
            type: "line",
            data: {
                labels: ["Thg 1", "Thg 2", "Thg 3", "Thg 4", "Thg 5", "Thg 6"],
                datasets: [{
                    label: "Doanh thu (VNĐ)",
                    data: [65000000, 58000000, 81000000, 80000000, 55000000, 92000000],
                    borderColor: "#b39b7d",
                    borderWidth: 2.2,
                    backgroundColor: fillGradient,
                    fill: true,
                    tension: 0.38,
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#b39b7d",
                    pointBorderWidth: 2,
                    pointRadius: 4,
                    pointHoverRadius: 6.5,
                    pointHoverBackgroundColor: "#b39b7d",
                    pointHoverBorderColor: "#ffffff",
                    pointHoverBorderWidth: 2
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        callbacks: {
                            label: (context) => `Doanh số: ${Number(context.raw).toLocaleString('vi-VN')}₫`
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: (val) => `${(val / 1000000).toFixed(0)}Tr`,
                            font: { size: 11 },
                            color: "#8c827a"
                        },
                        grid: {
                            color: "#f2eee9"
                        },
                        border: { dash: [4, 4] }
                    },
                    x: {
                        grid: { display: false },
                        ticks: {
                            font: { size: 11 },
                            color: "#8c827a"
                        }
                    }
                }
            }
        });
    }

    // ==========================================
    // 2. DỮ LIỆU & RENDER GIAO DỊCH PHỤ TRỢ (NẾU CÓ WIDGET)
    // ==========================================
    const transactionsData = [
        { id: "#8921", item: '1x Nhẫn Kim Cương Solitaire', amount: "24.500.000₫", time: "2 phút trước", img: "https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=100&auto=format&fit=crop&q=60" },
        { id: "#8920", item: "1x Bông Tai Ngọc Trai South Sea", amount: "12.000.000₫", time: "15 phút trước", img: "https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=100&auto=format&fit=crop&q=60" },
        { id: "#8919", item: "1x Lắc Tay Tennis Kim Cương", amount: "48.000.000₫", time: "1 giờ trước", img: "https://images.unsplash.com/photo-1603561591411-07134e71a2a9?w=100&auto=format&fit=crop&q=60" },
        { id: "#8918", item: "2x Dây Chuyền Vàng Ý 18K", amount: "19.500.000₫", time: "3 giờ trước", img: "https://images.unsplash.com/photo-1535632066927-ab7c9ab60908?w=100&auto=format&fit=crop&q=60" }
    ];

    const formatTxHTML = (tx) => `
        <div class="activity-item d-flex justify-content-between align-items-center p-2 rounded row-hover-effect">
            <div class="d-flex align-items-center gap-3">
                <div class="item-img bg-light rounded" style="width:42px; height:42px; background: url('${tx.img}') center/cover;"></div>
                <div>
                    <h6 class="mb-0 small fw-bold">Đơn hàng ${tx.id}</h6>
                    <small class="text-muted font-xs">${tx.item}</small>
                </div>
            </div>
            <div class="text-end">
                <span class="d-block small fw-bold text-dark">${tx.amount}</span>
                <small class="text-muted font-xs">${tx.time}</small>
            </div>
        </div>
    `;

    const modalList = document.getElementById("modalTransactionsList");
    const widgetList = document.getElementById("widgetTransactionsList");

    if (modalList) modalList.innerHTML = transactionsData.map(formatTxHTML).join("");
    if (widgetList) widgetList.innerHTML = transactionsData.slice(0, 2).map(formatTxHTML).join("");

    // ==========================================
    // 3. XUẤT BÁO CÁO CSV (SALES & INVENTORY)
    // ==========================================
    const btnSales = document.getElementById("btnExportSales");
    const btnInventory = document.getElementById("btnExportInventory");
    const dropdownToggle = document.getElementById("dropdownExportReport");

    const exportToCSV = (csvContent, fileName) => {
        try {
            const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
            const downloadUrl = URL.createObjectURL(blob);
            const anchor = document.createElement("a");
            anchor.setAttribute("href", downloadUrl);
            anchor.setAttribute("download", fileName);
            document.body.appendChild(anchor);
            anchor.click();
            document.body.removeChild(anchor);
            URL.revokeObjectURL(downloadUrl);
        } catch (e) {
            console.error("Lỗi xuất file:", e);
            alert("Trình duyệt không cho phép tải file. Vui lòng thử lại!");
        }
    };

    const toggleButtonLoading = (isLoading) => {
        if (!dropdownToggle) return;
        if (isLoading) {
            dropdownToggle.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Đang xuất...';
            dropdownToggle.disabled = true;
        } else {
            dropdownToggle.innerHTML = '<i class="bi bi-check2 me-2"></i>Đã xuất!';
            dropdownToggle.classList.replace("btn-gold", "btn-success");
            setTimeout(() => {
                dropdownToggle.innerHTML = '<i class="bi bi-download me-2"></i>Export Report';
                dropdownToggle.classList.replace("btn-success", "btn-gold");
                dropdownToggle.disabled = false;
            }, 1800);
        }
    };

    // Báo cáo doanh số
    btnSales?.addEventListener("click", (e) => {
        e.preventDefault();
        toggleButtonLoading(true);

        setTimeout(() => {
            const dateStr = new Date().toISOString().slice(0, 10);
            let csv = "\uFEFF"; // UTF-8 BOM hiển thị tiếng Việt trên Excel
            csv += "--- LUMIERE FINE JEWELRY - BÁO CÁO DOANH THU & DOANH SỐ ---\n";
            csv += `Ngày xuất báo cáo: ${dateStr}\n\n`;
            csv += "[1. CHỈ SỐ QUAN TRỌNG]\n";
            csv += "Chỉ số,Giá trị,Ghi chú\n";
            csv += "\"Tổng doanh thu gộp\",\"482.950.000₫\",\"Doanh thu ghi nhận trước chiết khấu.\"\n";
            csv += "\"Doanh thu thuần\",\"458.800.000₫\",\"Sau khi khấu trừ hoàn trả và giảm giá voucher.\"\n";
            csv += "\"Tổng số đơn hàng\",\"1.284\",\"Tổng đơn thành công và đang giao.\"\n";
            csv += "\"Giá trị đơn trung bình (AOV)\",\"3.760.000₫\",\"Phân khúc trang sức cao cấp.\"\n\n";

            csv += "[2. TỶ TRỌNG DOANH THU DANH MỤC]\n";
            csv += "Danh mục,Tỷ trọng (%),Doanh thu ước tính\n";
            csv += "\"Dây Chuyền Vàng (Necklaces)\",\"45%\",\"217.327.500₫\"\n";
            csv += "\"Nhẫn Kim Cương (Rings)\",\"32%\",\"154.544.000₫\"\n";
            csv += "\"Bông Tai Ngọc Trai (Earrings)\",\"23%\",\"111.078.500₫\"\n";

            exportToCSV(csv, `Lumiere_BaoCao_DoanhThu_${dateStr}.csv`);
            toggleButtonLoading(false);
        }, 600);
    });

    // Báo cáo tồn kho
    btnInventory?.addEventListener("click", (e) => {
        e.preventDefault();
        toggleButtonLoading(true);

        setTimeout(() => {
            const dateStr = new Date().toISOString().slice(0, 10);
            let csv = "\uFEFF";
            csv += "--- LUMIERE FINE JEWELRY - BÁO CÁO SẢN PHẨM & TỒN KHO ---\n";
            csv += `Ngày xuất báo cáo: ${dateStr}\n\n`;
            csv += "[1. TOP SẢN PHẨM BÁN CHẠY]\n";
            csv += "Tên sản phẩm,Mã SKU,Số lượng bán,Doanh thu mang lại,Xếp loại\n";
            csv += "\"Nhẫn Kim Cương Solitaire\",\"LUM-RG-001\",\"420\",\"1.029.000.000₫\",\"Doanh thu cao nhất\"\n";
            csv += "\"Bông Tai Ngọc Trai\",\"LUM-ER-012\",\"310\",\"372.000.000₫\",\"Số lượng nhiều nhất\"\n";
            csv += "\"Lắc Tay Tennis Diamond\",\"LUM-BR-009\",\"150\",\"720.000.000₫\",\"Sản phẩm cao cấp\"\n\n";

            csv += "[2. CẢNH BÁO HÀNG TỒN KHO THẤP CẦN NHẬP]\n";
            csv += "Tên sản phẩm,Mã SKU,Số lượng còn,Mức tối thiểu,Mức độ ưu tiên\n";
            csv += "\"Nhẫn Đá Mặt Trăng Moonstone\",\"LUM-RG-008\",\"2\",\"5\",\"KHẨN CẤP - NHẬP NGAY\"\n";
            csv += "\"Bông Tai Kim Cương Petite\",\"LUM-ER-045\",\"5\",\"5\",\"CẢNH BÁO - CẦN SẢN XUẤT THÊM\"\n";

            exportToCSV(csv, `Lumiere_BaoCao_TonKho_${dateStr}.csv`);
            toggleButtonLoading(false);
        }, 600);
    });
});