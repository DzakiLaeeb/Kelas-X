import axios from 'axios';

const API_URL = import.meta.env.VITE_API_URL || '';

const menuService = {
  /**
   * Get all menu items
   */
  getAllMenu: async () => {
    try {
      const response = await axios.get(`${API_URL}/api/get_menu.php`);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to fetch menu items');
    }
  },

  /**
   * Get menu item by ID
   */
  getMenuById: async (id) => {
    try {
      const response = await axios.get(`${API_URL}/api/get_menu_by_id.php?id=${id}`);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to fetch menu item');
    }
  },

  /**
   * Add new menu item with image upload support
   */
  addMenu: async (formData) => {
    try {
      const config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      };
      const response = await axios.post(`${API_URL}/api/add_menu.php`, formData, config);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to add menu item');
    }
  },

  /**
   * Update menu item with image upload support
   */
  updateMenu: async (id, formData) => {
    try {
      const config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      };
      const response = await axios.post(`${API_URL}/api/update_menu.php?id=${id}`, formData, config);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to update menu item');
    }
  },

  /**
   * Delete menu item
   */
  deleteMenu: async (id) => {
    try {
      const response = await axios.delete(`${API_URL}/api/delete_menu.php?id=${id}`);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to delete menu item');
    }
  },

  /**
   * Upload menu image
   */
  uploadImage: async (file) => {
    try {
      const formData = new FormData();
      formData.append('image', file);

      const config = {
        headers: {
          'Content-Type': 'multipart/form-data'
        }
      };

      const response = await axios.post(`${API_URL}/api/upload_image.php`, formData, config);
      return response.data;
    } catch (error) {
      throw new Error(error.response?.data?.message || 'Failed to upload image');
    }
  }
};

export default menuService;
