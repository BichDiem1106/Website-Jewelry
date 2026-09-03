document.addEventListener("DOMContentLoaded", () => {
    // 1. Tham chiếu các thành phần DOM
    const searchInput = document.getElementById("searchOrders");
    const paymentFilter = document.getElementById("filterPayment");
    const fulfillmentFilter = document.getElementById("filterFulfillment");
    const applyFilterBtn = document.getElementById("btnApplyFilters");
    const selectAllCheckbox = document.getElementById("selectAll");
    const tableRows = document.querySelectorAll("#ordersTable tbody tr");

    // Modal bootstrap cập nhật trạng thái
    const modalEl = document.getElementById("updateStatusModal");
    const updateModalInstance = modalEl ? new bootstrap.Modal(modalEl) : null;
    const modalHeading = document.getElementById("modalOrderId");
    const modalPaymentSelect = document.getElementById("modalSelectPayment");
    const modalFulfillmentSelect = document.getElementById("modalSelectFulfillment");
    const saveStatusBtn = document.getElementById("btnSaveStatus");

    let targetedOrderRow = null;

    // Từ điển trạng thái hiển thị
    const STATUS_MAP = {
        pending:          { css: "status-pending",    label: "Chờ xử lý" },
        processing:       { css: "status-processing", label: "Đang xử lý" },
        shipping:         { css: "status-processing", label: "Đang giao" },
        delivered:        { css: "status-shipped",    label: "Đã giao" },
        cancelled:        { css: "status-cancelled",  label: "Đã hủy" },
        return_requested: { css: "status-pending",    label: "Yêu cầu hoàn trả (Đang thu hồi)" },
        returned:         { css: "status-returned",   label: "Đã hoàn trả thành công" }
    };

    const PAYMENT_MAP = {
        cod:           `<i class="bi bi-cash status-paid me-1"></i> COD`,
        bank_transfer: `<i class="bi bi-credit-card status-pending me-1"></i> Chuyển khoản`
    };

    // ==========================================
    // 2. BỘ LỌC VÀ TÌM KIẾM ĐƠN HÀNG
    // ==========================================
    const executeFilter = () => {
        if (!searchInput) return;

        const term = searchInput.value.toLowerCase().trim();
        const selectedPayment = paymentFilter?.value ?? "all";
        const selectedFulfillment = fulfillmentFilter?.value ?? "all";

        tableRows.forEach(row => {
            const code = (row.getAttribute("data-id") || "").toLowerCase();
            const customer = (row.getAttribute("data-customer") || "").toLowerCase();
            const rowPay = row.getAttribute("data-payment") || "";
            const rowFulfill = row.getAttribute("data-fulfillment") || "";

            const isMatchedQuery = !term || code.includes(term) || customer.includes(term);
            const isMatchedPayment = selectedPayment === "all" || rowPay === selectedPayment;
            const isMatchedFulfillment = selectedFulfillment === "all" || rowFulfill === selectedFulfillment;

            if (isMatchedQuery && isMatchedPayment && isMatchedFulfillment) {
                row.style.removeProperty("display");
            } else {
                row.style.setProperty("display", "none", "important");
                const rowBox = row.querySelector(".form-check-input");
                if (rowBox) rowBox.checked = false;
            }
        });

        if (selectAllCheckbox) selectAllCheckbox.checked = false;
    };

    // Debounce tìm kiếm mượt mà
    let debounceTimer = null;
    searchInput?.addEventListener("input", () => {
        clearTimeout(debounceTimer);
        debounceTimer = setTimeout(executeFilter, 250);
    });

    applyFilterBtn?.addEventListener("click", executeFilter);
    paymentFilter?.addEventListener("change", executeFilter);
    fulfillmentFilter?.addEventListener("change", executeFilter);

    // ==========================================
    // 3. XỬ LÝ SỰ KIỆN CẬP NHẬT TRẠNG THÁI
    // ==========================================
    tableRows.forEach(row => {
        const triggerBtn = row.querySelector(".btn-update-status");
        if (!triggerBtn) return;

        triggerBtn.addEventListener("click", () => {
            if (!updateModalInstance) return;

            targetedOrderRow = row;
            const orderCode = row.getAttribute("data-id") || "";
            const currentPayment = row.getAttribute("data-payment") || "cod";
            const currentStatus = row.getAttribute("data-fulfillment") || "pending";

            if (modalHeading) modalHeading.textContent = `Đơn hàng #${orderCode}`;
            if (modalPaymentSelect) modalPaymentSelect.value = currentPayment;
            if (modalFulfillmentSelect) modalFulfillmentSelect.value = currentStatus;

            updateModalInstance.show();
        });
    });

    saveStatusBtn?.addEventListener("click", () => {
        if (!targetedOrderRow || !updateModalInstance) return;

        const updatedPayment = modalPaymentSelect?.value || "cod";
        const updatedStatus = modalFulfillmentSelect?.value || "pending";

        // Cập nhật thuộc tính data-*
        targetedOrderRow.setAttribute("data-payment", updatedPayment);
        targetedOrderRow.setAttribute("data-fulfillment", updatedStatus);

        // Cập nhật HTML trực tiếp trên các cell
        const paymentCell = targetedOrderRow.cells[5];
        const statusCell = targetedOrderRow.cells[6];

        if (paymentCell) {
            paymentCell.innerHTML = `
                <span class="status-badge">
                    ${PAYMENT_MAP[updatedPayment] || updatedPayment}
                </span>
            `;
        }

        if (statusCell) {
            const statusConfig = STATUS_MAP[updatedStatus] || { css: "status-pending", label: updatedStatus };
            statusCell.innerHTML = `
                <span class="status-badge">
                    <i class="bi bi-circle-fill font-xs ${statusConfig.css} me-1"></i> 
                    ${statusConfig.label}
                </span>
            `;
        }

        updateModalInstance.hide();
        executeFilter();
    });

    // ==========================================
    // 4. CHECKBOX "CHỌN TẤT CẢ" (SELECT ALL)
    // ==========================================
    if (selectAllCheckbox) {
        selectAllCheckbox.addEventListener("change", () => {
            const isChecked = selectAllCheckbox.checked;
            document.querySelectorAll("#ordersTable tbody .form-check-input").forEach(cb => {
                const tr = cb.closest("tr");
                if (tr && tr.style.display !== "none") {
                    cb.checked = isChecked;
                }
            });
        });

        // Bỏ check "Select All" nếu người dùng hủy chọn bất kỳ dòng nào
        document.querySelectorAll("#ordersTable tbody .form-check-input").forEach(cb => {
            cb.addEventListener("change", () => {
                const visibleBoxes = Array.from(document.querySelectorAll("#ordersTable tbody tr"))
                    .filter(tr => tr.style.display !== "none")
                    .map(tr => tr.querySelector(".form-check-input"))
                    .filter(Boolean);

                const allChecked = visibleBoxes.length > 0 && visibleBoxes.every(box => box.checked);
                selectAllCheckbox.checked = allChecked;
            });
        });
    }
});