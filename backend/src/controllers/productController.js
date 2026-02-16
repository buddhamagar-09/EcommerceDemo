const ProductModel = require('../models/productModel');

class ProductController {
  static getAll(req, res) {
    try {
      const filters = {
        category: req.query.category,
        search: req.query.search,
        minPrice: req.query.minPrice,
        maxPrice: req.query.maxPrice,
        sortBy: req.query.sortBy
      };

      const products = ProductModel.getAll(filters);
      res.json({
        success: true,
        count: products.length,
        data: products
      });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }

  static getById(req, res) {
    try {
      const product = ProductModel.getById(req.params.id);
      if (!product) {
        return res.status(404).json({ error: 'Product not found' });
      }
      res.json({ success: true, data: product });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }

  static getCategories(req, res) {
    try {
      const categories = ProductModel.getCategories();
      res.json({ success: true, data: categories });
    } catch (error) {
      res.status(500).json({ error: error.message });
    }
  }
}

module.exports = ProductController;
