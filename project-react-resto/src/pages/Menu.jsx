import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Badge, Button, Modal, Form, InputGroup } from 'react-bootstrap';
import { menuCategories, formatPrice } from '../data/menuData';
import { useAuth } from '../contexts/AuthContext';
import axios from 'axios';
import '../styles/Menu.css';
import '../styles/MenuEnhancements.css';

const Menu = () => {
  const [selectedCategory, setSelectedCategory] = useState('all');
  const [menuItems, setMenuItems] = useState([]);
  const [filteredItems, setFilteredItems] = useState([]);
  const [showModal, setShowModal] = useState(false);
  const [selectedItem, setSelectedItem] = useState(null);
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('name');
  const [cart, setCart] = useState([]);
  const [imageError, setImageError] = useState({});
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const { user, isAuthenticated } = useAuth();

  // Fetch menu data from database
  useEffect(() => {
    fetchMenuData();
  }, []);

  useEffect(() => {
    filterItems();
  }, [selectedCategory, searchTerm, sortBy, menuItems]);

  const fetchMenuData = async () => {
    try {
      setLoading(true);
      setError(null);

      // Use the proxy configuration from vite.config.js
      const response = await axios.get('/api/get_menu.php');

      if (response.data.success) {
        setMenuItems(response.data.data);
      } else {
        setError('Failed to fetch menu data');
      }
    } catch (err) {
      console.error('Error fetching menu data:', err);
      setError('Failed to load menu. Please try again later.');
    } finally {
      setLoading(false);
    }
  };

  const filterItems = () => {
    let items = [...menuItems];

    // Filter by category (for now, we'll show all items since we don't have category filtering from DB yet)
    // TODO: Implement category filtering when categories are properly set up in database

    // Filter by search term
    if (searchTerm) {
      items = items.filter(item =>
        item.name.toLowerCase().includes(searchTerm.toLowerCase()) ||
        item.description.toLowerCase().includes(searchTerm.toLowerCase())
      );
    }

    // Sort items
    items.sort((a, b) => {
      switch (sortBy) {
        case 'price-low':
          return a.price - b.price;
        case 'price-high':
          return b.price - a.price;
        case 'rating':
          return b.rating - a.rating;
        case 'popular':
          return b.isPopular - a.isPopular;
        default:
          return a.name.localeCompare(b.name);
      }
    });

    setFilteredItems(items);
  };

  const handleItemClick = (item) => {
    setSelectedItem(item);
    setShowModal(true);
  };

  const addToCart = (item) => {
    setCart(prev => {
      const existingItem = prev.find(cartItem => cartItem.id === item.id);
      if (existingItem) {
        return prev.map(cartItem =>
          cartItem.id === item.id
            ? { ...cartItem, quantity: cartItem.quantity + 1 }
            : cartItem
        );
      }
      return [...prev, { ...item, quantity: 1 }];
    });
  };

  const getCartTotal = () => {
    return cart.reduce((total, item) => total + (item.price * item.quantity), 0);
  };

  const getCartItemCount = () => {
    return cart.reduce((total, item) => total + item.quantity, 0);
  };

  const handleImageError = (itemId) => {
    setImageError(prev => ({
      ...prev,
      [itemId]: true
    }));
  };

  const getImageUrl = (item) => {
    // First try to use the processed image URL from the API
    if (item.image && item.image.startsWith('http')) {
      return item.image;
    }

    // Fallback to gambar_url if available
    if (item.gambar_url && item.gambar_url.startsWith('http')) {
      return item.gambar_url;
    }

    // If we have a gambar field, construct the URL
    if (item.gambar) {
      if (item.gambar.startsWith('http')) {
        return item.gambar;
      }
      // Construct URL for local uploads
      return `http://localhost/project-react-resto/uploads/${item.gambar}`;
    }

    // Default fallback image
    return 'https://via.placeholder.com/400x300/f5f5f5/999999?text=No+Image';
  };

  // Show loading state
  if (loading) {
    return (
      <div className="menu-page">
        <Container className="py-5">
          <div className="text-center">
            <div className="spinner-border text-primary" role="status">
              <span className="visually-hidden">Loading...</span>
            </div>
            <p className="mt-3 text-white">Loading menu items...</p>
          </div>
        </Container>
      </div>
    );
  }

  // Show error state
  if (error) {
    return (
      <div className="menu-page">
        <Container className="py-5">
          <div className="text-center">
            <div className="alert alert-danger" role="alert">
              <h4 className="alert-heading">Error Loading Menu</h4>
              <p>{error}</p>
              <Button variant="primary" onClick={fetchMenuData}>
                Try Again
              </Button>
            </div>
          </div>
        </Container>
      </div>
    );
  }

  return (
    <div className="menu-page">
      {/* Hero Section */}
      <div className="menu-hero">
        <Container>
          <Row className="align-items-center min-vh-50">
            <Col lg={6}>
              <div className="hero-content">
                <h1 className="hero-title">
                  Culinary <span className="text-gradient">Excellence</span>
                </h1>
                <p className="hero-subtitle">
                  Discover our carefully crafted menu featuring the finest ingredients
                  and innovative culinary techniques from around the world.
                </p>
                <div className="hero-stats">
                  <div className="stat-item">
                    <span className="stat-number">35+</span>
                    <span className="stat-label">Menu Items</span>
                  </div>
                  <div className="stat-item">
                    <span className="stat-number">5</span>
                    <span className="stat-label">Categories</span>
                  </div>
                  <div className="stat-item">
                    <span className="stat-number">4.8</span>
                    <span className="stat-label">Avg Rating</span>
                  </div>
                </div>
              </div>
            </Col>
            <Col lg={6}>
              <div className="hero-image">
                <img
                  src="https://images.unsplash.com/photo-1414235077428-338989a2e8c0?w=600&h=400&fit=crop"
                  alt="Fine dining"
                  className="img-fluid rounded-4 shadow-lg"
                />
              </div>
            </Col>
          </Row>
        </Container>
      </div>

      <Container className="py-5">
        {/* Search and Filter Section */}
        <Row className="mb-5">
          <Col lg={8}>
            <InputGroup className="search-bar">
              <Form.Control
                type="text"
                placeholder="Search menu items..."
                value={searchTerm}
                onChange={(e) => setSearchTerm(e.target.value)}
                className="search-input"
              />
              <Button variant="outline-primary" className="search-btn">
                🔍
              </Button>
            </InputGroup>
          </Col>
          <Col lg={4}>
            <Form.Select
              value={sortBy}
              onChange={(e) => setSortBy(e.target.value)}
              className="sort-select"
            >
              <option value="name">Sort by Name</option>
              <option value="price-low">Price: Low to High</option>
              <option value="price-high">Price: High to Low</option>
              <option value="rating">Highest Rated</option>
              <option value="popular">Most Popular</option>
            </Form.Select>
          </Col>
        </Row>

        {/* Category Filter */}
        <Row className="mb-5">
          <Col>
            <div className="category-filter">
              <button
                className={`category-btn ${selectedCategory === 'all' ? 'active' : ''}`}
                onClick={() => setSelectedCategory('all')}
              >
                <span className="category-icon">🍽️</span>
                <span className="category-name">All Menu</span>
              </button>
              {menuCategories.map(category => (
                <button
                  key={category.id}
                  className={`category-btn ${selectedCategory === category.id.toString() ? 'active' : ''}`}
                  onClick={() => setSelectedCategory(category.id.toString())}
                >
                  <span className="category-icon">{category.icon}</span>
                  <span className="category-name">{category.name}</span>
                </button>
              ))}
            </div>
          </Col>
        </Row>

        {/* Menu Items Grid */}
        <Row>
          {filteredItems.length > 0 ? (
            filteredItems.map((item, index) => (
              <Col lg={4} md={6} key={item.id} className="mb-4">
                <Card className="menu-card h-100 fade-in" onClick={() => handleItemClick(item)} style={{animationDelay: `${index * 0.1}s`}}>
                  <div className="menu-card-image">
                    {!imageError[item.id] ? (
                      <img
                        src={getImageUrl(item)}
                        alt={item.name}
                        onError={() => handleImageError(item.id)}
                        loading="lazy"
                      />
                    ) : (
                      <div className="menu-card-image-fallback">
                        <span>{item.name[0].toUpperCase()}</span>
                      </div>
                    )}
                    <div className="menu-card-badges">
                      {item.isNew && <Badge bg="success" className="badge-new">New</Badge>}
                      {item.isPopular && <Badge bg="warning" className="badge-popular">Popular</Badge>}
                    </div>
                    <div className="menu-card-overlay">
                      <Button
                        variant="light"
                        className="btn-view-details"
                        onClick={(e) => {
                          e.stopPropagation();
                          handleItemClick(item);
                        }}
                      >
                        View Details
                      </Button>
                    </div>
                  </div>
                  <Card.Body className="menu-card-body">
                    <div className="menu-card-header">
                      <h5 className="menu-card-title gradient-text-pink">{item.name}</h5>
                      <div className="menu-card-rating">
                        <span className="rating-stars">⭐</span>
                        <span className="rating-value">{item.rating}</span>
                      </div>
                    </div>
                    <p className="menu-card-description">{item.description}</p>
                    <div className="menu-card-footer">
                      <div className="menu-card-price">{formatPrice(item.price)}</div>
                      <Button
                        variant="primary"
                        size="sm"
                        className="btn-add-cart btn-morph micro-bounce focus-ring"
                        onClick={(e) => {
                          e.stopPropagation();
                          addToCart(item);
                        }}
                      >
                        Add to Cart
                      </Button>
                    </div>
                    <div className="menu-card-meta">
                      <span className="prep-time">⏱️ {item.preparationTime}</span>
                      <span className="calories">🔥 {item.calories} cal</span>
                    </div>
                  </Card.Body>
                </Card>
              </Col>
            ))
          ) : (
            <Col xs={12}>
              <div className="no-results text-center">
                <h3>No Menu Items Found</h3>
                <p>Try adjusting your search or filter criteria</p>
              </div>
            </Col>
          )}
        </Row>

        {filteredItems.length === 0 && (
          <Row>
            <Col className="text-center py-5">
              <div className="no-results">
                <h3>No menu items found</h3>
                <p>Try adjusting your search or filter criteria</p>
              </div>
            </Col>
          </Row>
        )}
      </Container>

      {/* Floating Cart */}
      {cart.length > 0 && (
        <div className="floating-cart">
          <Button variant="primary" className="cart-toggle btn-morph micro-bounce focus-ring">
            🛒 {getCartItemCount()} items - {formatPrice(getCartTotal())}
          </Button>
        </div>
      )}

      {/* Item Detail Modal */}
      <Modal show={showModal} onHide={() => setShowModal(false)} size="lg" centered>
        {selectedItem && (
          <>
            <Modal.Header closeButton className="modal-header-custom">
              <Modal.Title>{selectedItem.name}</Modal.Title>
            </Modal.Header>
            <Modal.Body className="modal-body-custom">
              <Row>
                <Col md={6}>
                  <img
                    src={selectedItem.image}
                    alt={selectedItem.name}
                    className="img-fluid mb-3"
                    style={{
                      borderRadius: '20px',
                      boxShadow: '0 10px 30px rgba(0, 0, 0, 0.2)',
                      transition: 'transform 0.3s ease'
                    }}
                    onMouseEnter={(e) => e.target.style.transform = 'scale(1.02)'}
                    onMouseLeave={(e) => e.target.style.transform = 'scale(1)'}
                  />
                </Col>
                <Col md={6}>
                  <div className="item-details">
                    <div className="item-badges mb-3">
                      {selectedItem.isNew && <Badge bg="success" className="me-2">New</Badge>}
                      {selectedItem.isPopular && <Badge bg="warning" className="me-2">Popular</Badge>}
                      <Badge bg="info">{selectedItem.category}</Badge>
                    </div>

                    <p className="item-description">{selectedItem.description}</p>

                    <div className="item-meta mb-3">
                      <div className="meta-item">
                        <strong>Rating:</strong> ⭐ {selectedItem.rating}/5
                      </div>
                      <div className="meta-item">
                        <strong>Prep Time:</strong> ⏱️ {selectedItem.preparationTime}
                      </div>
                      <div className="meta-item">
                        <strong>Calories:</strong> 🔥 {selectedItem.calories}
                      </div>
                    </div>

                    <div className="item-ingredients mb-3">
                      <strong>Ingredients:</strong>
                      <div className="ingredients-list">
                        {selectedItem.ingredients.map((ingredient, index) => (
                          <Badge key={index} bg="light" text="dark" className="me-1 mb-1">
                            {ingredient}
                          </Badge>
                        ))}
                      </div>
                    </div>

                    {selectedItem.allergens.length > 0 && (
                      <div className="item-allergens mb-3">
                        <strong>Allergens:</strong>
                        <div className="allergens-list">
                          {selectedItem.allergens.map((allergen, index) => (
                            <Badge key={index} bg="danger" className="me-1 mb-1">
                              {allergen}
                            </Badge>
                          ))}
                        </div>
                      </div>
                    )}

                    <div className="item-price-section">
                      <div className="item-price">{formatPrice(selectedItem.price)}</div>
                      <Button
                        variant="primary"
                        size="lg"
                        onClick={() => {
                          addToCart(selectedItem);
                          setShowModal(false);
                        }}
                      >
                        Add to Cart
                      </Button>
                    </div>
                  </div>
                </Col>
              </Row>
            </Modal.Body>
          </>
        )}
      </Modal>
    </div>
  );
};

export default Menu;
