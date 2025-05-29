import React, { useState, useEffect } from 'react';
import PropTypes from 'prop-types';
import { menuService, categoryService } from '../../services/api';
import { Button, Alert, Card } from '../ui';
import { validateRequired, isValidPrice } from '../../utils/validators';
import { getErrorMessage } from '../../utils/errorHandler';

/**
 * MenuForm Component
 * 
 * Form for adding or editing menu items.
 */
const MenuForm = ({ menuItem, onSave, onCancel }) => {
  // State for form data
  const [formData, setFormData] = useState({
    menu: '',
    deskripsi: '',
    harga: '',
    kategori_id: '',
    gambar: ''
  });
  
  // State for form validation and submission
  const [errors, setErrors] = useState({});
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);
  const [categories, setCategories] = useState([]);
  const [loadingCategories, setLoadingCategories] = useState(true);
  const [imagePreview, setImagePreview] = useState(null);
  const [uploadProgress, setUploadProgress] = useState(0);
  
  // Initialize form with menu item data if editing
  useEffect(() => {
    if (menuItem) {
      setFormData({
        menu: menuItem.menu || '',
        deskripsi: menuItem.deskripsi || '',
        harga: menuItem.harga || '',
        kategori_id: menuItem.kategori_id || '',
        gambar: menuItem.gambar || ''
      });
    }
  }, [menuItem]);
  
  // Fetch categories on component mount
  useEffect(() => {
    const fetchCategories = async () => {
      try {
        setLoadingCategories(true);
        const response = await categoryService.getAllCategories();
        
        if (response.success) {
          setCategories(response.data);
        } else {
          setError(response.message || 'Failed to fetch categories');
        }
      } catch (err) {
        setError(getErrorMessage(err));
      } finally {
        setLoadingCategories(false);
      }
    };
    
    fetchCategories();
  }, []);
  
  // Handle input changes
  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({ ...prev, [name]: value }));
    
    // Clear error for this field
    if (errors[name]) {
      setErrors(prev => ({ ...prev, [name]: null }));
    }
  };
  
  // Handle image file selection
  const handleImageChange = (e) => {
    const file = e.target.files[0];
    if (!file) return;

    // Validate file type
    const validTypes = ['image/jpeg', 'image/png', 'image/webp'];
    if (!validTypes.includes(file.type)) {
      setError('Please select a valid image file (JPG, PNG, or WebP)');
      return;
    }

    // Validate file size (5MB max)
    if (file.size > 5 * 1024 * 1024) {
      setError('Image size should be less than 5MB');
      return;
    }

    // Create preview
    const reader = new FileReader();
    reader.onloadend = () => {
      setImagePreview(reader.result);
    };
    reader.readAsDataURL(file);

    setFormData(prev => ({
      ...prev,
      imageFile: file
    }));
  };

  // Validate form
  const validateForm = () => {
    const newErrors = {};
    
    // Required fields
    const requiredFields = ['menu', 'deskripsi', 'harga', 'kategori_id'];
    requiredFields.forEach(field => {
      if (!formData[field]) {
        newErrors[field] = `${field.charAt(0).toUpperCase() + field.slice(1)} is required`;
      }
    });
    
    // Price validation
    if (formData.harga && !isValidPrice(formData.harga)) {
      newErrors.harga = 'Price must be a valid number';
    }
    
    setErrors(newErrors);
    return Object.keys(newErrors).length === 0;
  };
  
  // Handle form submission
  const handleSubmit = async (e) => {
    e.preventDefault();
    
    if (!validateForm()) {
      return;
    }
    
    try {
      setLoading(true);
      setError(null);
      
      // Format data for API
      const menuData = {
        ...formData,
        harga: parseFloat(formData.harga),
        kategori_id: parseInt(formData.kategori_id)
      };
      
      // Create FormData for multipart/form-data submission
      const submitData = new FormData();
      submitData.append('menu', formData.menu);
      submitData.append('deskripsi', formData.deskripsi);
      submitData.append('harga', formData.harga);
      submitData.append('kategori_id', formData.kategori_id);
      
      // If there's a new image file, append it
      if (formData.imageFile) {
        submitData.append('image', formData.imageFile);
      } else if (formData.gambar) {
        // If using existing image
        submitData.append('gambar', formData.gambar);
      }

      // Submit the form with progress tracking
      const xhr = new XMLHttpRequest();
      xhr.upload.onprogress = (event) => {
        if (event.lengthComputable) {
          const progress = (event.loaded / event.total) * 100;
          setUploadProgress(progress);
        }
      };

      xhr.onload = async () => {
        if (xhr.status === 200) {
          const response = JSON.parse(xhr.responseText);
          if (response.success) {
            onSave(response.data);
          } else {
            throw new Error(response.message || 'Failed to save menu item');
          }
        } else {
          throw new Error('Network error occurred');
        }
      };

      xhr.onerror = () => {
        throw new Error('Network error occurred');
      };

      // Send to the appropriate endpoint
      const url = menuItem ? 
        `/api/update_menu.php?id=${menuItem.id}` : 
        '/api/add_menu.php';
      
      xhr.open('POST', url);
      xhr.send(submitData);

    } catch (err) {
      setError(getErrorMessage(err));
    } finally {
      setLoading(false);
      setUploadProgress(0);
    }
  };
  
  return (
    <Card title={menuItem ? 'Edit Menu Item' : 'Add Menu Item'}>
      {error && (
        <Alert type="error" className="mb-4">
          {error}
        </Alert>
      )}
      
      <form onSubmit={handleSubmit}>
        <div className="mb-4">
          <label htmlFor="menu" className="block text-sm font-medium text-gray-700 mb-1">
            Menu Name *
          </label>
          <input
            type="text"
            id="menu"
            name="menu"
            value={formData.menu}
            onChange={handleChange}
            className={`w-full px-3 py-2 border rounded-md ${errors.menu ? 'border-red-500' : 'border-gray-300'}`}
            placeholder="Enter menu name"
          />
          {errors.menu && (
            <p className="mt-1 text-sm text-red-500">{errors.menu}</p>
          )}
        </div>
        
        <div className="mb-4">
          <label htmlFor="deskripsi" className="block text-sm font-medium text-gray-700 mb-1">
            Description *
          </label>
          <textarea
            id="deskripsi"
            name="deskripsi"
            value={formData.deskripsi}
            onChange={handleChange}
            rows="3"
            className={`w-full px-3 py-2 border rounded-md ${errors.deskripsi ? 'border-red-500' : 'border-gray-300'}`}
            placeholder="Enter menu description"
          ></textarea>
          {errors.deskripsi && (
            <p className="mt-1 text-sm text-red-500">{errors.deskripsi}</p>
          )}
        </div>
        
        <div className="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
          <div>
            <label htmlFor="kategori_id" className="block text-sm font-medium text-gray-700 mb-1">
              Category *
            </label>
            <select
              id="kategori_id"
              name="kategori_id"
              value={formData.kategori_id}
              onChange={handleChange}
              className={`w-full px-3 py-2 border rounded-md ${errors.kategori_id ? 'border-red-500' : 'border-gray-300'}`}
              disabled={loadingCategories}
            >
              <option value="">Select a category</option>
              {categories.map(category => (
                <option key={category.id} value={category.id}>
                  {category.name}
                </option>
              ))}
            </select>
            {errors.kategori_id && (
              <p className="mt-1 text-sm text-red-500">{errors.kategori_id}</p>
            )}
          </div>
          
          <div>
            <label htmlFor="harga" className="block text-sm font-medium text-gray-700 mb-1">
              Price *
            </label>
            <input
              type="text"
              id="harga"
              name="harga"
              value={formData.harga}
              onChange={handleChange}
              className={`w-full px-3 py-2 border rounded-md ${errors.harga ? 'border-red-500' : 'border-gray-300'}`}
              placeholder="Enter price"
            />
            {errors.harga && (
              <p className="mt-1 text-sm text-red-500">{errors.harga}</p>
            )}
          </div>
        </div>
        
        <div className="mb-4">
          <label htmlFor="gambar" className="block text-sm font-medium text-gray-700 mb-1">
            Image URL
          </label>
          <input
            type="text"
            id="gambar"
            name="gambar"
            value={formData.gambar}
            onChange={handleChange}
            className="w-full px-3 py-2 border border-gray-300 rounded-md"
            placeholder="Enter image URL"
          />
          <p className="mt-1 text-xs text-gray-500">
            Enter a URL for the menu item image. Leave blank to use a default image.
          </p>
        </div>
        
        <div className="mb-4">
          <label htmlFor="image" className="block text-sm font-medium text-gray-700 mb-1">
            Menu Image
          </label>
          <input
            type="file"
            id="image"
            name="image"
            onChange={handleImageChange}
            accept="image/jpeg,image/png,image/webp"
            className="form-input"
          />
          {uploadProgress > 0 && uploadProgress < 100 && (
            <div className="progress-bar">
              <div 
                className="progress-bar-fill" 
                style={{ width: `${uploadProgress}%` }}
              />
            </div>
          )}
          {imagePreview && (
            <div className="image-preview mt-2">
              <img 
                src={imagePreview} 
                alt="Preview" 
                className="max-w-xs rounded-lg shadow-md"
              />
            </div>
          )}
          {formData.gambar && !imagePreview && (
            <div className="image-preview mt-2">
              <img 
                src={`/uploads/${formData.gambar}`} 
                alt="Current" 
                className="max-w-xs rounded-lg shadow-md"
              />
            </div>
          )}
        </div>
        
        <div className="flex justify-end space-x-2 mt-6">
          <Button
            type="button"
            variant="light"
            onClick={onCancel}
          >
            Cancel
          </Button>
          <Button
            type="submit"
            variant="primary"
            loading={loading}
          >
            {menuItem ? 'Update Menu Item' : 'Add Menu Item'}
          </Button>
        </div>
      </form>
    </Card>
  );
};

MenuForm.propTypes = {
  menuItem: PropTypes.object,
  onSave: PropTypes.func.isRequired,
  onCancel: PropTypes.func.isRequired
};

export default MenuForm;
