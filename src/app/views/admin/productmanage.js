/**
 * LUMIÈRE Fine Jewelry - Product Management Logic
 */

// Bộ dữ liệu khởi tạo mặc định (Initial Mock Dataset)
const INITIAL_CATALOG = [
    { name: "Aurum Solitaire", desc: "Vàng 18K thủ công đính kim cương", category: "Necklace", sku: "AUR-SOL-001", price: 28500000, status: "IN STOCK", image: "https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=150&auto=format&fit=crop&q=60", dateAdded: '2026-06-01' },
    { name: "Emerald Eternal", desc: "Kim cương giác cắt Emerald", category: "Ring", sku: "EME-ETR-042", price: 95000000, status: "LOW STOCK", image: "https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=150&auto=format&fit=crop&q=60", dateAdded: '2026-06-05' },
    { name: "Lumina Drops", desc: "Ngọc trai South Sea cao cấp", category: "Earrings", sku: "LUM-DRP-881", price: 18500000, status: "OUT OF STOCK", image: "https://images.unsplash.com/photo-1630019852942-f89202989a59?w=150&auto=format&fit=crop&q=60", dateAdded: '2026-06-08' },
    { name: "Celestial Cuff", desc: "Vàng hồng 14K đính đá quý", category: "Bracelets", sku: "CEL-CUF-099", price: 46000000, status: "IN STOCK", image: "https://images.unsplash.com/photo-1611591437281-460bfbe1220a?w=150&auto=format&fit=crop&q=60", dateAdded: '2026-06-02' },
    { name: "Sapphire Tears", desc: "Vàng trắng 18K gắn Sapphire xanh", category: "Earrings", sku: "SAP-TEA-102", price: 68000000, status: "IN STOCK", image: "https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=150&auto=format&fit=crop&q=60", dateAdded: '2026-06-09' },
    { name: "Ruby Heart", desc: "Đá Ruby huyết bồ câu tự nhiên", category: "Necklace", sku: "RUB-HRT-005", price: 42000000, status: "LOW STOCK", image: "https://images.unsplash.com/photo-1605100804763-247f67b3557e?w=150&auto=format&fit=crop&q=60", dateAdded: '2026-06-03' }
];

const STORAGE_KEY = 'luxury_products';

// Quản lý LocalStorage
const StorageManager = {
    getProducts() {
        try {
            const raw = localStorage.getItem(STORAGE_KEY);
            if (!raw) {
                localStorage.setItem(STORAGE_KEY, JSON.stringify(INITIAL_CATALOG));
                return [...INITIAL_CATALOG];
            }
            return JSON.parse(raw);
        } catch (e) {
            console.error("Lỗi đọc dữ liệu localStorage:", e);
            return [...INITIAL_CATALOG];
        }
    },
    saveProducts(list) {
        localStorage.setItem(STORAGE_KEY, JSON.stringify(list));
    }
};

// Quản lý trạng thái lọc & phân trang
const ProductStore = {
    state: {
        rawList: [],
        filteredList: [],
        searchQuery: "",
        sortOption: "newest",
        selectedCategory: null,
        temporaryCategory: null,
        currentPage: 1,
        pageSize: 4
    },

    init() {
        this.state.rawList = StorageManager.getProducts();
        this.applyFilters();
    },

    applyFilters() {
        let result = [...this.state.rawList];

        // 1. Lọc theo nhóm danh mục
        if (this.state.selectedCategory) {
            result = result.filter(item => item.category === this.state.selectedCategory);
        }

        // 2. Tìm kiếm theo tên hoặc mã SKU
        if (this.state.searchQuery) {
            const keyword = this.state.searchQuery.toLowerCase().trim();
            result = result.filter(item => 
                (item.name || "").toLowerCase().includes(keyword) || 
                (item.sku || "").toLowerCase().includes(keyword)
            );
        }

        // 3. Sắp xếp
        if (this.state.sortOption === 'price-asc') {
            result.sort((a, b) => Number(a.price) - Number(b.price));
        } else if (this.state.sortOption === 'price-desc') {
            result.sort((a, b) => Number(b.price) - Number(a.price));
        } else {
            result.sort((a, b) => new Date(b.dateAdded || 0) - new Date(a.dateAdded || 0));
        }

        this.state.filteredList = result;
    }
};

// Xử lý DOM & Giao diện
const ProductUI = {
    dom: {},

    cacheDOM() {
        this.dom = {
            searchInput: document.getElementById('searchInput'),
            sortSelect: document.getElementById('sortSelect'),
            filterBtns: document.querySelectorAll('.filter-item'),
            applyFiltersBtn: document.getElementById('applyFilters'),
            clearFiltersBtn: document.getElementById('clearFilters'),
            tableBody: document.getElementById('productTableBody'),
            paginationInfo: document.getElementById('paginationInfo'),
            paginationControls: document.getElementById('paginationControls'),
            addProductForm: document.getElementById('addProductForm'),
            editModalEl: document.getElementById('editProductModal'),
            editProductForm: document.getElementById('editProductForm'),
            editOldSku: document.getElementById('editOldSku'),
            editName: document.getElementById('editProductName'),
            editDesc: document.getElementById('editProductDesc'),
            editCategory: document.getElementById('editProductCategory'),
            editSku: document.getElementById('editProductSku'),
            editPrice: document.getElementById('editProductPrice'),
            editStock: document.getElementById('editProductStock')
        };
        this.editModal = this.dom.editModalEl ? new bootstrap.Modal(this.dom.editModalEl) : null;
    },

    formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN').format(amount) + '₫';
    },

    escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str || '';
        return div.innerHTML;
    },

    getStatusBadge(status) {
        const normalized = (status || "").toUpperCase();
        if (normalized === "LOW STOCK") {
            return { css: "badge-lowstock", text: "Sắp hết hàng" };
        }
        if (normalized === "OUT OF STOCK") {
            return { css: "badge-outofstock", text: "Hết hàng" };
        }
        return { css: "badge-instock", text: "Còn hàng" };
    },

    render() {
        this.renderTable();
        this.renderPagination();
    },

    renderTable() {
        if (!this.dom.tableBody) return;
        this.dom.tableBody.innerHTML = "";

        const { currentPage, pageSize, filteredList } = ProductStore.state;
        const startIndex = (currentPage - 1) * pageSize;
        const pageItems = filteredList.slice(startIndex, startIndex + pageSize);

        if (pageItems.length === 0) {
            this.dom.tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-5 text-muted small"><i class="bi bi-inbox fs-3 d-block mb-1"></i>Không tìm thấy sản phẩm nào.</td></tr>`;
            return;
        }

        pageItems.forEach(item => {
            const badge = this.getStatusBadge(item.status);
            const tr = document.createElement('tr');
            tr.className = "border-bottom row-hover";
            tr.innerHTML = `
                <td class="ps-4 py-3">
                    <div class="d-flex align-items-center gap-3">
                        <img src="${this.escapeHtml(item.image)}" class="product-thumb object-fit-cover" alt="${this.escapeHtml(item.name)}">
                        <div>
                            <h6 class="mb-1 fw-bold text-dark font-xs">${this.escapeHtml(item.name)}</h6>
                            <p class="text-muted font-xs mb-0 text-truncate" style="max-width: 180px;">${this.escapeHtml(item.desc)}</p>
                        </div>
                    </div>
                </td>
                <td class="font-xs text-muted align-middle">${this.escapeHtml(item.category)}</td>
                <td class="font-xs text-muted font-monospace align-middle">${this.escapeHtml(item.sku)}</td>
                <td class="font-numeric fw-medium align-middle">${this.formatCurrency(item.price)}</td>
                <td class="align-middle"><span class="badge badge-custom ${badge.css}">${badge.text}</span></td>
                <td class="pe-4 text-end align-middle">
                    <button class="btn btn-sm btn-icon border-0 edit-btn" data-sku="${this.escapeHtml(item.sku)}" title="Chỉnh sửa">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <button class="btn btn-sm btn-icon border-0 text-danger delete-btn" data-sku="${this.escapeHtml(item.sku)}" title="Xóa">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            `;
            this.dom.tableBody.appendChild(tr);
        });
    },

    renderPagination() {
        if (!this.dom.paginationInfo || !this.dom.paginationControls) return;

        const { currentPage, pageSize, filteredList } = ProductStore.state;
        const total = filteredList.length;
        const totalPages = Math.ceil(total / pageSize) || 1;

        const from = total === 0 ? 0 : ((currentPage - 1) * pageSize) + 1;
        const to = Math.min(currentPage * pageSize, total);
        this.dom.paginationInfo.textContent = `Hiển thị từ ${from} đến ${to} trên tổng ${total} sản phẩm`;

        this.dom.paginationControls.innerHTML = "";

        // Nút Prev
        const prevLi = document.createElement('li');
        prevLi.className = `page-item ${currentPage === 1 ? 'disabled' : ''}`;
        prevLi.innerHTML = `<a class="page-link border-0 ${currentPage === 1 ? 'text-muted' : 'text-dark'}" href="#" data-page="prev"><i class="bi bi-chevron-left"></i></a>`;
        this.dom.paginationControls.appendChild(prevLi);

        // Các số trang
        for (let i = 1; i <= totalPages; i++) {
            const numLi = document.createElement('li');
            numLi.className = `page-item ${i === currentPage ? 'active' : ''}`;
            numLi.innerHTML = `<a class="page-link border-0 ${i === currentPage ? 'rounded-1' : 'text-dark'}" href="#" data-page="${i}">${i}</a>`;
            this.dom.paginationControls.appendChild(numLi);
        }

        // Nút Next
        const nextLi = document.createElement('li');
        nextLi.className = `page-item ${currentPage === totalPages || total === 0 ? 'disabled' : ''}`;
        nextLi.innerHTML = `<a class="page-link border-0 ${currentPage === totalPages || total === 0 ? 'text-muted' : 'text-dark'}" href="#" data-page="next"><i class="bi bi-chevron-right"></i></a>`;
        this.dom.paginationControls.appendChild(nextLi);
    },

    bindEvents() {
        // Tìm kiếm
        this.dom.searchInput?.addEventListener('input', (e) => {
            ProductStore.state.searchQuery = e.target.value;
            ProductStore.state.currentPage = 1;
            ProductStore.applyFilters();
            this.render();
        });

        // Sắp xếp
        this.dom.sortSelect?.addEventListener('change', (e) => {
            ProductStore.state.sortOption = e.target.value;
            ProductStore.state.currentPage = 1;
            ProductStore.applyFilters();
            this.render();
        });

        // Bộ lọc danh mục
        this.dom.filterBtns.forEach(btn => {
            btn.addEventListener('click', (e) => {
                this.dom.filterBtns.forEach(b => {
                    b.classList.remove('active');
                    b.classList.add('text-muted');
                });
                const target = e.currentTarget;
                target.classList.add('active');
                target.classList.remove('text-muted');
                ProductStore.state.temporaryCategory = target.getAttribute('data-category');
            });
        });

        this.dom.applyFiltersBtn?.addEventListener('click', () => {
            ProductStore.state.selectedCategory = ProductStore.state.temporaryCategory;
            ProductStore.state.currentPage = 1;
            ProductStore.applyFilters();
            this.render();
        });

        this.dom.clearFiltersBtn?.addEventListener('click', (e) => {
            e.preventDefault();
            ProductStore.state.temporaryCategory = null;
            ProductStore.state.selectedCategory = null;
            ProductStore.state.searchQuery = "";
            ProductStore.state.sortOption = "newest";
            ProductStore.state.currentPage = 1;

            if (this.dom.searchInput) this.dom.searchInput.value = "";
            if (this.dom.sortSelect) this.dom.sortSelect.value = "newest";

            this.dom.filterBtns.forEach(b => {
                b.classList.remove('active');
                b.classList.add('text-muted');
            });

            ProductStore.applyFilters();
            this.render();
        });

        // Click phân trang
        this.dom.paginationControls?.addEventListener('click', (e) => {
            e.preventDefault();
            const link = e.target.closest('a');
            if (!link || link.parentElement.classList.contains('disabled')) return;

            const action = link.getAttribute('data-page');
            const totalPages = Math.ceil(ProductStore.state.filteredList.length / ProductStore.state.pageSize);

            if (action === 'prev' && ProductStore.state.currentPage > 1) {
                ProductStore.state.currentPage--;
            } else if (action === 'next' && ProductStore.state.currentPage < totalPages) {
                ProductStore.state.currentPage++;
            } else if (!isNaN(action)) {
                ProductStore.state.currentPage = parseInt(action, 10);
            }

            this.render();
        });

        // Hành động sửa / xóa trên bảng
        this.dom.tableBody?.addEventListener('click', (e) => {
            const delBtn = e.target.closest('.delete-btn');
            const editBtn = e.target.closest('.edit-btn');

            // Xóa sản phẩm
            if (delBtn) {
                const sku = delBtn.getAttribute('data-sku');
                const target = ProductStore.state.rawList.find(p => p.sku === sku);
                if (target && confirm(`Bạn có chắc muốn xóa sản phẩm "${target.name}"?`)) {
                    ProductStore.state.rawList = ProductStore.state.rawList.filter(p => p.sku !== sku);
                    StorageManager.saveProducts(ProductStore.state.rawList);
                    ProductStore.applyFilters();
                    this.render();
                }
            }

            // Mở modal sửa sản phẩm
            if (editBtn && this.editModal) {
                const sku = editBtn.getAttribute('data-sku');
                const p = ProductStore.state.rawList.find(item => item.sku === sku);
                if (p) {
                    this.dom.editOldSku.value = p.sku;
                    this.dom.editName.value = p.name;
                    this.dom.editDesc.value = p.desc || '';
                    this.dom.editCategory.value = p.category;
                    this.dom.editSku.value = p.sku;
                    this.dom.editPrice.value = p.price;

                    if (p.status === "OUT OF STOCK") this.dom.editStock.value = 0;
                    else if (p.status === "LOW STOCK") this.dom.editStock.value = 2;
                    else this.dom.editStock.value = 15;

                    this.editModal.show();
                }
            }
        });

        // Submit form chỉnh sửa
        this.dom.editProductForm?.addEventListener('submit', (e) => {
            e.preventDefault();
            const targetSku = this.dom.editOldSku.value;
            const index = ProductStore.state.rawList.findIndex(p => p.sku === targetSku);

            if (index !== -1) {
                const stockQty = parseInt(this.dom.editStock.value, 10) || 0;
                let status = "IN STOCK";
                if (stockQty === 0) status = "OUT OF STOCK";
                else if (stockQty <= 3) status = "LOW STOCK";

                ProductStore.state.rawList[index] = {
                    ...ProductStore.state.rawList[index],
                    name: this.dom.editName.value.trim(),
                    desc: this.dom.editDesc.value.trim() || "Chưa có mô tả.",
                    category: this.dom.editCategory.value,
                    sku: this.dom.editSku.value.trim().toUpperCase(),
                    price: parseFloat(this.dom.editPrice.value) || 0,
                    status
                };

                StorageManager.saveProducts(ProductStore.state.rawList);
                this.editModal.hide();
                ProductStore.applyFilters();
                this.render();
            }
        });

        // Submit form tạo sản phẩm mới (nếu trang có nhúng form)
        this.dom.addProductForm?.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(this.dom.addProductForm);
            const stockQty = parseInt(formData.get('stock_quantity'), 10) || 0;

            let status = "IN STOCK";
            if (stockQty === 0) status = "OUT OF STOCK";
            else if (stockQty <= 3) status = "LOW STOCK";

            const commitAdd = (imgUrl) => {
                const newObj = {
                    name: formData.get('product_name') || 'Trang sức mới',
                    desc: formData.get('description') || "Chưa có mô tả.",
                    category: formData.get('category') || 'Ring',
                    sku: (formData.get('sku') || 'SKU-000').toUpperCase(),
                    price: parseFloat(formData.get('price')) || 0,
                    status,
                    image: imgUrl,
                    dateAdded: new Date().toISOString().split('T')[0]
                };

                ProductStore.state.rawList.unshift(newObj);
                StorageManager.saveProducts(ProductStore.state.rawList);
                this.dom.addProductForm.reset();

                if (this.dom.tableBody) {
                    ProductStore.state.currentPage = 1;
                    ProductStore.applyFilters();
                    this.render();
                } else {
                    window.location.href = "/index.php?page=admin_products";
                }
            };

            const imgFile = formData.get('product_images[]');
            if (imgFile && imgFile.size > 0) {
                const reader = new FileReader();
                reader.onload = (evt) => commitAdd(evt.target.result);
                reader.readAsDataURL(imgFile);
            } else {
                commitAdd("https://images.unsplash.com/photo-1599643478518-a784e5dc4c8f?w=150&auto=format&fit=crop&q=60");
            }
        });
    }
};

// Khởi chạy khi DOM sẵn sàng
document.addEventListener('DOMContentLoaded', () => {
    ProductUI.cacheDOM();
    ProductStore.init();
    ProductUI.bindEvents();
    ProductUI.render();
});