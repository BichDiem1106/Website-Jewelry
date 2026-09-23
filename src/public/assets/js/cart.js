const CART_KEY = "LUMIÈRE_cart";

function isUserLoggedIn() {
  return window.USER_LOGGED_IN === true || window.IS_LOGGED_IN === true;
}

async function syncDB(action, data = {}) {
  if (!isUserLoggedIn()) {
    return {
      status: "guest",
    };
  }

  try {
    const form = new FormData();

    form.append("action", action);

    Object.entries(data).forEach(([key, value]) => {
      if (value !== null && value !== undefined) {
        form.append(key, value);
      }
    });

    const response = await fetch(
      "/index.php?page=cart&action=" + encodeURIComponent(action),
      {
        method: "POST",
        body: form,
      }
    );

    const result = await response.json();

    if (result.status !== "ok") {
      console.error("Lỗi đồng bộ giỏ hàng:", result);
    }

    return result;
  } catch (error) {
    console.error("Cart sync lỗi:", error);

    return {
      status: "error",
      message: error.message,
    };
  }
}

const Cart = {
  getAll() {
    try {
      return JSON.parse(localStorage.getItem(CART_KEY)) || [];
    } catch (error) {
      console.error("Không đọc được giỏ hàng:", error);
      return [];
    }
  },

  save(items) {
    localStorage.setItem(CART_KEY, JSON.stringify(items));
    window.dispatchEvent(new Event("cart-updated"));
  },

  async add(product) {
    const items = this.getAll();

    const existing = items.find(
      (item) =>
        String(item.id) === String(product.id) &&
        item.metal === product.metal
    );

    if (existing) {
      existing.quantity += product.quantity || 1;
    } else {
      items.push({
        ...product,
        quantity: product.quantity || 1,
      });
    }

    this.save(items);

    const result = await syncDB("add", {
      product_id: product.id,
      quantity: product.quantity || 1,
      selected_material:
        product.metal !== "default" ? product.metal : null,
    });

    return {
      items,
      result,
    };
  },

  async remove(id, metal) {
    const items = this.getAll().filter(
      (item) =>
        !(
          String(item.id) === String(id) &&
          item.metal === metal
        )
    );

    this.save(items);

    await syncDB("remove", {
      product_id: id,
    });

    return items;
  },

  async updateQuantity(id, metal, delta) {
    const items = this.getAll();

    const item = items.find(
      (item) =>
        String(item.id) === String(id) &&
        item.metal === metal
    );

    if (!item) {
      return items;
    }

    item.quantity += delta;

    if (item.quantity <= 0) {
      return this.remove(id, metal);
    }

    this.save(items);

    await syncDB("update", {
      product_id: id,
      quantity: item.quantity,
    });

    return items;
  },

  async setQuantity(id, metal, qty) {
    const items = this.getAll();

    const item = items.find(
      (item) =>
        String(item.id) === String(id) &&
        item.metal === metal
    );

    if (!item) {
      return items;
    }

    if (qty <= 0) {
      return this.remove(id, metal);
    }

    item.quantity = qty;

    this.save(items);

    await syncDB("update", {
      product_id: id,
      quantity: qty,
    });

    return items;
  },

  getTotalQty() {
    return this.getAll().reduce(
      (total, item) => total + Number(item.quantity || 0),
      0
    );
  },

  getTotalPrice() {
    return this.getAll().reduce(
      (total, item) =>
        total +
        Number(item.price || 0) * Number(item.quantity || 0),
      0
    );
  },

  clear() {
    this.save([]);
    syncDB("clear");
  },
};

function formatVND(amount) {
  return Number(amount || 0).toLocaleString("vi-VN") + "₫";
}