const CartModel = require('../models/cartModel');
const ProductModel = require('../models/productModel');

class CartController {
  static getCart(req, res) {
    try {
      const userId = req.user.userId;
      const cart = CartModel.getCart(userId);

      // Enrich cart items with product details
      const enrichedItems = cart.items.map(item => {
        const product = ProductModel.getById(item.productId);
        return {
          ...item,
          product: product || null
        };
      });

      // Calculate total
      const total = enrichedItems.reduce((sum, item) => {
        if (item.product) {
          return sum + (item.product.price * item.quantity);
        }
        return sum;
      }, 0);

      res.json({
        success: true,
        data: {
          items: enrichedItems,
          total: parseFloat(total.toFixed(2))
        }
      });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }

  static addItem(req, res) {
    try {
      const userId = req.user.userId;
      const { productId, quantity } = req.body;

      if (!productId) {
        return res.status(400).json({ error: 'Product ID is required' });
      }

      const product = ProductModel.getById(productId);
      if (!product) {
        return res.status(404).json({ error: 'Product not found' });
      }

      const cart = CartModel.addItem(userId, productId, quantity || 1);
      res.json({ success: true, data: cart });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }

  static updateItem(req, res) {
    try {
      const userId = req.user.userId;
      const { productId, quantity } = req.body;

      if (!productId || quantity === undefined) {
        return res.status(400).json({ error: 'Product ID and quantity are required' });
      }

      const cart = CartModel.updateItem(userId, productId, quantity);
      res.json({ success: true, data: cart });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }

  static removeItem(req, res) {
    try {
      const userId = req.user.userId;
      const { productId } = req.params;

      const cart = CartModel.removeItem(userId, productId);
      res.json({ success: true, data: cart });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }

  static clearCart(req, res) {
    try {
      const userId = req.user.userId;
      const cart = CartModel.clearCart(userId);
      res.json({ success: true, data: cart });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }
}

module.exports = CartController;
