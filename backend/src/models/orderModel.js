const { v4: uuidv4 } = require('uuid');

// In-memory order storage
const orders = [];

class OrderModel {
  static create(orderData) {
    const order = {
      id: uuidv4(),
      ...orderData,
      status: 'pending',
      createdAt: new Date().toISOString()
    };

    orders.push(order);
    return order;
  }

  static getById(id) {
    return orders.find(o => o.id === id);
  }

  static getByUserId(userId) {
    return orders.filter(o => o.userId === userId);
  }

  static updateStatus(id, status) {
    const order = orders.find(o => o.id === id);
    if (order) {
      order.status = status;
      order.updatedAt = new Date().toISOString();
    }
    return order;
  }

  static getAll() {
    return orders;
  }
}

module.exports = OrderModel;
