const { v4: uuidv4 } = require('uuid');

// In-memory cart storage (in production, use database)
const carts = {};

class CartModel {
  static getCart(userId) {
    if (!carts[userId]) {
      carts[userId] = { items: [] };
    }
    return carts[userId];
  }

  static addItem(userId, productId, quantity = 1) {
    const cart = this.getCart(userId);
    const existingItem = cart.items.find(item => item.productId === productId);

    if (existingItem) {
      existingItem.quantity += quantity;
    } else {
      cart.items.push({ productId, quantity });
    }

    return cart;
  }

  static updateItem(userId, productId, quantity) {
    const cart = this.getCart(userId);
    const item = cart.items.find(item => item.productId === productId);

    if (item) {
      if (quantity <= 0) {
        cart.items = cart.items.filter(item => item.productId !== productId);
      } else {
        item.quantity = quantity;
      }
    }

    return cart;
  }

  static removeItem(userId, productId) {
    const cart = this.getCart(userId);
    cart.items = cart.items.filter(item => item.productId !== productId);
    return cart;
  }

  static clearCart(userId) {
    carts[userId] = { items: [] };
    return carts[userId];
  }
}

module.exports = CartModel;
