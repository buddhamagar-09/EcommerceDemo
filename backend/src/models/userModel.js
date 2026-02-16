const bcrypt = require('bcryptjs');
const { v4: uuidv4 } = require('uuid');

// In-memory user database
const users = [];

class UserModel {
  static async create(userData) {
    const { email, password, name } = userData;

    // Check if user already exists
    if (users.find(u => u.email === email)) {
      throw new Error('User already exists');
    }

    // Hash password
    const hashedPassword = await bcrypt.hash(password, 10);

    const user = {
      id: uuidv4(),
      email,
      password: hashedPassword,
      name,
      createdAt: new Date().toISOString()
    };

    users.push(user);
    return this.sanitizeUser(user);
  }

  static async findByEmail(email) {
    return users.find(u => u.email === email);
  }

  static async findById(id) {
    return users.find(u => u.id === id);
  }

  static async validatePassword(password, hashedPassword) {
    return bcrypt.compare(password, hashedPassword);
  }

  static sanitizeUser(user) {
    const { password, ...sanitizedUser } = user;
    return sanitizedUser;
  }
}

module.exports = UserModel;
