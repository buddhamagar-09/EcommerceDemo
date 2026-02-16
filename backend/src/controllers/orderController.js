const OrderModel = require('../models/orderModel');
const CartModel = require('../models/cartModel');
const ProductModel = require('../models/productModel');

class OrderController {
  static createOrder(req, res) {
    try {
      const userId = req.user.userId;
      const { shippingAddress, paymentMethod } = req.body;

      if (!shippingAddress || !paymentMethod) {
        return res.status(400).json({ 
          error: 'Shipping address and payment method are required' 
        });
      }

      // Get cart items
      const cart = CartModel.getCart(userId);
      if (!cart.items || cart.items.length === 0) {
        return res.status(400).json({ error: 'Cart is empty' });
      }

      // Calculate order details
      const orderItems = cart.items.map(item => {
        const product = ProductModel.getById(item.productId);
        return {
          productId: item.productId,
          name: product.name,
          price: product.price,
          quantity: item.quantity,
          subtotal: product.price * item.quantity
        };
      });

      const total = orderItems.reduce((sum, item) => sum + item.subtotal, 0);

      // Create order
      const order = OrderModel.create({
        userId,
        items: orderItems,
        total: parseFloat(total.toFixed(2)),
        shippingAddress,
        paymentMethod
      });

      // Clear cart
      CartModel.clearCart(userId);

      res.status(201).json({
        success: true,
        data: order
      });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }

  static getOrders(req, res) {
    try {
      const userId = req.user.userId;
      const orders = OrderModel.getByUserId(userId);

      res.json({
        success: true,
        data: orders
      });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }

  static getOrderById(req, res) {
    try {
      const { id } = req.params;
      const order = OrderModel.getById(id);

      if (!order) {
        return res.status(404).json({ error: 'Order not found' });
      }

      // Check if order belongs to user
      if (order.userId !== req.user.userId) {
        return res.status(403).json({ error: 'Access denied' });
      }

      res.json({
        success: true,
        data: order
      });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }
}

module.exports = OrderController;
