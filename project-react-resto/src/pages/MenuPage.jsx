import React, { useState, useEffect } from 'react';
import { Container, Row, Col, Card, Badge, Button, Spinner, Alert, Form, InputGroup } from 'react-bootstrap';
import { FaSearch, FaFilter, FaShoppingCart, FaStar, FaHeart, FaEye, FaThLarge, FaList } from 'react-icons/fa';
import '../styles/Menu.css';

const MenuPage = () => {
  const [menus, setMenus] = useState([]);
  const [loading, setLoading] = useState(true);
  const [error, setError] = useState(null);
  const [activeCategory, setActiveCategory] = useState('all');
  const [categories, setCategories] = useState([]);
  const [buyingItems, setBuyingItems] = useState(new Set());
  const [searchTerm, setSearchTerm] = useState('');
  const [sortBy, setSortBy] = useState('name');
  const [favorites, setFavorites] = useState(new Set());
  const [viewMode, setViewMode] = useState('grid');
  const [priceFilter, setPriceFilter] = useState('all');

  useEffect(() => {
    fetch("/api/get_menu.php")
      .then((res) => res.json())
      .then((data) => {
        if (data.success) {
          setMenus(data.data);
          // Ambil kategori unik dari data menu
          const uniqueCategories = [
            { id: 'all', name: 'Semua Menu' },
            ...Array.from(new Set(data.data.map(item => item.kategori_id))).map(id => {
              const item = data.data.find(menu => menu.kategori_id === id);
              return { id, name: item ? item.kategori_nama || `Kategori ${id}` : `Kategori ${id}` };
            })
          ];
          setCategories(uniqueCategories);
        } else {
          setError(data.message || "Gagal mengambil data menu");
        }
      })
      .catch((err) => {
        setError("Gagal mengambil data menu: " + err.message);
      })
      .finally(() => setLoading(false));
  }, []);

  // Fungsi untuk handle pembelian item
  const handleBuyItem = async (menu) => {
    // Set loading state untuk item ini
    setBuyingItems(prev => new Set([...prev, menu.id]));

    try {
      const response = await fetch('/api/add_order.php', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify({
          menu_id: menu.id,
          nama_menu: menu.nama,
          harga: menu.harga,
          quantity: 1,
          customer_name: 'Guest User', // Bisa diganti dengan sistem login
          customer_phone: '', // Bisa ditambahkan form input
          notes: ''
        })
      });

      const result = await response.json();

      if (result.success) {
        alert(`✅ Berhasil menambahkan "${menu.nama}" ke pesanan!`);
      } else {
        alert(`❌ Gagal menambahkan pesanan: ${result.message}`);
      }
    } catch (error) {
      console.error('Error:', error);
      alert('❌ Terjadi kesalahan saat memproses pesanan');
    } finally {
      // Remove loading state
      setBuyingItems(prev => {
        const newSet = new Set(prev);
        newSet.delete(menu.id);
        return newSet;
      });
    }
  };

  // Enhanced filtering and sorting
  const filteredMenus = menus
    .filter(menu => {
      // Category filter
      const categoryMatch = activeCategory === 'all' || menu.kategori_id === activeCategory;

      // Search filter
      const searchMatch = menu.nama.toLowerCase().includes(searchTerm.toLowerCase()) ||
                         menu.deskripsi.toLowerCase().includes(searchTerm.toLowerCase());

      // Price filter
      const price = parseInt(menu.harga);
      let priceMatch = true;
      if (priceFilter === 'low') priceMatch = price < 50000;
      else if (priceFilter === 'medium') priceMatch = price >= 50000 && price < 100000;
      else if (priceFilter === 'high') priceMatch = price >= 100000;

      return categoryMatch && searchMatch && priceMatch;
    })
    .sort((a, b) => {
      switch (sortBy) {
        case 'name':
          return a.nama.localeCompare(b.nama);
        case 'price-low':
          return parseInt(a.harga) - parseInt(b.harga);
        case 'price-high':
          return parseInt(b.harga) - parseInt(a.harga);
        case 'rating':
          return (b.rating || 4.5) - (a.rating || 4.5);
        default:
          return 0;
      }
    });

  // Toggle favorite
  const toggleFavorite = (menuId) => {
    setFavorites(prev => {
      const newFavorites = new Set(prev);
      if (newFavorites.has(menuId)) {
        newFavorites.delete(menuId);
      } else {
        newFavorites.add(menuId);
      }
      return newFavorites;
    });
  };

  if (loading) {
    return (
      <div className="d-flex justify-content-center align-items-center" style={{ minHeight: '60vh' }}>
        <div className="text-center">
          <Spinner animation="border" variant="warning" style={{ width: '3rem', height: '3rem', color: '#FF6B35' }} />
          <h4 className="mt-3" style={{ color: '#FF6B35' }}>Memuat Menu RestoKalel...</h4>
          <p className="text-muted">Mohon tunggu sebentar</p>
        </div>
      </div>
    );
  }

  if (error) {
    return (
      <Container className="mt-5">
        <Alert variant="danger" className="text-center">
          <Alert.Heading>Oops! Terjadi Kesalahan</Alert.Heading>
          <p>{error}</p>
          <Button variant="outline-danger" onClick={() => window.location.reload()}>
            Coba Lagi
          </Button>
        </Alert>
      </Container>
    );
  }

  return (
    <div style={{
      background: 'linear-gradient(135deg, #FFFAF7 0%, #FFE4D6 100%)',
      minHeight: '100vh',
      paddingTop: '2rem',
      paddingBottom: '2rem'
    }}>
      <Container fluid>
        {/* Hero Section */}
        <div className="text-center mb-5" style={{
          background: 'linear-gradient(135deg, #FF6B35 0%, #FF8C42 100%)',
          color: 'white',
          padding: '4rem 2rem',
          borderRadius: '20px',
          marginBottom: '3rem',
          position: 'relative',
          overflow: 'hidden'
        }}>
          <div style={{
            position: 'absolute',
            top: 0,
            left: 0,
            right: 0,
            bottom: 0,
            background: 'radial-gradient(circle at 30% 70%, rgba(255, 255, 255, 0.1) 0%, transparent 50%)',
            pointerEvents: 'none'
          }} />
          <div style={{ position: 'relative', zIndex: 1 }}>
            <h1 style={{ fontSize: '3.5rem', fontWeight: '700', marginBottom: '1rem' }}>
              🍽️ Menu RestoKalel
            </h1>
            <p style={{ fontSize: '1.2rem', opacity: '0.9', marginBottom: '2rem' }}>
              Cita Rasa Autentik Indonesia dengan Sentuhan Modern
            </p>
            <div className="d-flex justify-content-center gap-4">
              <div className="text-center">
                <h3 style={{ fontSize: '2rem', fontWeight: '600' }}>{menus.length}+</h3>
                <p style={{ opacity: '0.8' }}>Menu Pilihan</p>
              </div>
              <div className="text-center">
                <h3 style={{ fontSize: '2rem', fontWeight: '600' }}>{categories.length - 1}</h3>
                <p style={{ opacity: '0.8' }}>Kategori</p>
              </div>
              <div className="text-center">
                <h3 style={{ fontSize: '2rem', fontWeight: '600' }}>4.8</h3>
                <p style={{ opacity: '0.8' }}>Rating</p>
              </div>
            </div>
          </div>
        </div>

        {/* Search and Filter Controls */}
        <Card className="mb-4" style={{
          borderRadius: '15px',
          border: 'none',
          boxShadow: '0 4px 20px rgba(255, 107, 53, 0.1)'
        }}>
          <Card.Body>
            <Row className="align-items-center">
              <Col md={4}>
                <InputGroup>
                  <InputGroup.Text style={{ backgroundColor: '#FF6B35', borderColor: '#FF6B35', color: 'white' }}>
                    <FaSearch />
                  </InputGroup.Text>
                  <Form.Control
                    type="text"
                    placeholder="Cari menu favorit Anda..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    style={{ borderColor: '#FF6B35' }}
                  />
                </InputGroup>
              </Col>
              <Col md={2}>
                <Form.Select
                  value={sortBy}
                  onChange={(e) => setSortBy(e.target.value)}
                  style={{ borderColor: '#FF6B35' }}
                >
                  <option value="name">Nama A-Z</option>
                  <option value="price-low">Harga Terendah</option>
                  <option value="price-high">Harga Tertinggi</option>
                  <option value="rating">Rating Tertinggi</option>
                </Form.Select>
              </Col>
              <Col md={2}>
                <Form.Select
                  value={priceFilter}
                  onChange={(e) => setPriceFilter(e.target.value)}
                  style={{ borderColor: '#FF6B35' }}
                >
                  <option value="all">Semua Harga</option>
                  <option value="low">&lt; Rp 50.000</option>
                  <option value="medium">Rp 50.000 - 100.000</option>
                  <option value="high">&gt; Rp 100.000</option>
                </Form.Select>
              </Col>
              <Col md={2}>
                <div className="d-flex gap-2">
                  <Button
                    variant={viewMode === 'grid' ? 'warning' : 'outline-warning'}
                    onClick={() => setViewMode('grid')}
                    style={{
                      backgroundColor: viewMode === 'grid' ? '#FF6B35' : 'transparent',
                      borderColor: '#FF6B35',
                      color: viewMode === 'grid' ? 'white' : '#FF6B35'
                    }}
                  >
                    <FaThLarge />
                  </Button>
                  <Button
                    variant={viewMode === 'list' ? 'warning' : 'outline-warning'}
                    onClick={() => setViewMode('list')}
                    style={{
                      backgroundColor: viewMode === 'list' ? '#FF6B35' : 'transparent',
                      borderColor: '#FF6B35',
                      color: viewMode === 'list' ? 'white' : '#FF6B35'
                    }}
                  >
                    <FaList />
                  </Button>
                </div>
              </Col>
              <Col md={2} className="text-end">
                <Badge bg="secondary" style={{ backgroundColor: '#FF6B35', fontSize: '0.9rem' }}>
                  {filteredMenus.length} Menu Ditemukan
                </Badge>
              </Col>
            </Row>
          </Card.Body>
        </Card>

        {/* Category Filter */}
        <div className="mb-4">
          <h5 style={{ color: '#FF6B35', marginBottom: '1rem' }}>
            <FaFilter className="me-2" />
            Kategori Menu
          </h5>
          <div className="d-flex flex-wrap gap-2">
            {categories.map(category => (
              <Button
                key={category.id}
                variant={activeCategory === category.id ? 'warning' : 'outline-warning'}
                onClick={() => setActiveCategory(category.id)}
                style={{
                  backgroundColor: activeCategory === category.id ? '#FF6B35' : 'transparent',
                  borderColor: '#FF6B35',
                  color: activeCategory === category.id ? 'white' : '#FF6B35',
                  borderRadius: '25px',
                  padding: '8px 20px'
                }}
              >
                {category.name}
              </Button>
            ))}
          </div>
        </div>

        {/* Menu Display */}
        {filteredMenus.length === 0 ? (
          <div className="text-center py-5">
            <div style={{ fontSize: '4rem', marginBottom: '1rem' }}>🍽️</div>
            <h4 style={{ color: '#FF6B35' }}>Tidak ada menu yang ditemukan</h4>
            <p className="text-muted">Coba ubah filter atau kata kunci pencarian Anda</p>
          </div>
        ) : (
          <Row>
            {filteredMenus.map(menu => (
              <Col
                key={menu.id}
                xs={12}
                sm={viewMode === 'grid' ? 6 : 12}
                md={viewMode === 'grid' ? 4 : 12}
                lg={viewMode === 'grid' ? 3 : 12}
                className="mb-4"
              >
                <Card
                  className="h-100 menu-card-hover"
                  style={{
                    borderRadius: '15px',
                    border: 'none',
                    boxShadow: '0 4px 20px rgba(255, 107, 53, 0.1)',
                    transition: 'all 0.3s ease',
                    overflow: 'hidden'
                  }}
                  onMouseEnter={(e) => {
                    e.currentTarget.style.transform = 'translateY(-8px)';
                    e.currentTarget.style.boxShadow = '0 8px 30px rgba(255, 107, 53, 0.2)';
                  }}
                  onMouseLeave={(e) => {
                    e.currentTarget.style.transform = 'translateY(0)';
                    e.currentTarget.style.boxShadow = '0 4px 20px rgba(255, 107, 53, 0.1)';
                  }}
                >
                  {viewMode === 'grid' ? (
                    <>
                      {/* Grid View */}
                      <div style={{ position: 'relative', overflow: 'hidden' }}>
                        <Card.Img
                          variant="top"
                          src={menu.gambar || menu.image || 'https://via.placeholder.com/400x250/FFE4D6/FF6B35?text=RestoKalel'}
                          style={{
                            height: '200px',
                            objectFit: 'cover',
                            transition: 'transform 0.3s ease'
                          }}
                          onError={(e) => {
                            e.target.onerror = null;
                            e.target.src = 'https://via.placeholder.com/400x250/FFE4D6/FF6B35?text=Menu+RestoKalel';
                          }}
                          onMouseEnter={(e) => {
                            e.target.style.transform = 'scale(1.05)';
                          }}
                          onMouseLeave={(e) => {
                            e.target.style.transform = 'scale(1)';
                          }}
                        />
                        <Button
                          variant="light"
                          size="sm"
                          className="position-absolute top-0 end-0 m-2"
                          onClick={() => toggleFavorite(menu.id)}
                          style={{
                            borderRadius: '50%',
                            width: '40px',
                            height: '40px',
                            backgroundColor: 'rgba(255, 255, 255, 0.9)',
                            border: 'none'
                          }}
                        >
                          <FaHeart
                            style={{
                              color: favorites.has(menu.id) ? '#FF6B35' : '#ccc',
                              fontSize: '1rem'
                            }}
                          />
                        </Button>
                        {menu.isNew && (
                          <Badge
                            bg="success"
                            className="position-absolute top-0 start-0 m-2"
                            style={{ backgroundColor: '#28a745' }}
                          >
                            Baru!
                          </Badge>
                        )}
                      </div>
                      <Card.Body className="d-flex flex-column">
                        <div className="mb-2">
                          <Card.Title style={{ color: '#333', fontSize: '1.1rem', fontWeight: '600' }}>
                            {menu.menu || menu.nama}
                          </Card.Title>
                          <Card.Text style={{ color: '#666', fontSize: '0.9rem', lineHeight: '1.4' }}>
                            {menu.deskripsi}
                          </Card.Text>
                        </div>
                        <div className="d-flex justify-content-between align-items-center mb-3">
                          <span style={{ fontSize: '1.2rem', fontWeight: '700', color: '#FF6B35' }}>
                            Rp {parseInt(menu.harga).toLocaleString('id-ID')}
                          </span>
                          <div className="d-flex align-items-center">
                            <FaStar style={{ color: '#ffc107', marginRight: '4px' }} />
                            <span style={{ fontSize: '0.9rem', color: '#666' }}>
                              {menu.rating || '4.5'}
                            </span>
                          </div>
                        </div>
                        <div className="mt-auto d-flex gap-2">
                          <Button
                            variant="outline-secondary"
                            size="sm"
                            style={{ borderColor: '#FF6B35', color: '#FF6B35' }}
                          >
                            <FaEye className="me-1" />
                            Detail
                          </Button>
                          <Button
                            onClick={() => handleBuyItem(menu)}
                            disabled={buyingItems.has(menu.id)}
                            style={{
                              backgroundColor: buyingItems.has(menu.id) ? '#6c757d' : '#FF6B35',
                              borderColor: buyingItems.has(menu.id) ? '#6c757d' : '#FF6B35',
                              flex: 1
                            }}
                          >
                            {buyingItems.has(menu.id) ? (
                              <>
                                <Spinner animation="border" size="sm" className="me-2" />
                                Memproses...
                              </>
                            ) : (
                              <>
                                <FaShoppingCart className="me-2" />
                                Beli Sekarang
                              </>
                            )}
                          </Button>
                        </div>
                      </Card.Body>
                    </>
                  ) : (
                    <>
                      {/* List View */}
                      <Row className="g-0">
                        <Col md={4}>
                          <Card.Img
                            src={menu.gambar || menu.image || 'https://via.placeholder.com/300x200/FFE4D6/FF6B35?text=RestoKalel'}
                            style={{
                              height: '200px',
                              objectFit: 'cover'
                            }}
                            onError={(e) => {
                              e.target.onerror = null;
                              e.target.src = 'https://via.placeholder.com/300x200/FFE4D6/FF6B35?text=Menu+RestoKalel';
                            }}
                          />
                        </Col>
                        <Col md={8}>
                          <Card.Body className="d-flex flex-column h-100">
                            <div className="d-flex justify-content-between align-items-start mb-2">
                              <Card.Title style={{ color: '#333', fontSize: '1.3rem', fontWeight: '600' }}>
                                {menu.menu || menu.nama}
                              </Card.Title>
                              <Button
                                variant="light"
                                size="sm"
                                onClick={() => toggleFavorite(menu.id)}
                                style={{ border: 'none' }}
                              >
                                <FaHeart
                                  style={{
                                    color: favorites.has(menu.id) ? '#FF6B35' : '#ccc'
                                  }}
                                />
                              </Button>
                            </div>
                            <Card.Text style={{ color: '#666', fontSize: '1rem', lineHeight: '1.5', flex: 1 }}>
                              {menu.deskripsi}
                            </Card.Text>
                            <div className="d-flex justify-content-between align-items-center mb-3">
                              <span style={{ fontSize: '1.4rem', fontWeight: '700', color: '#FF6B35' }}>
                                Rp {parseInt(menu.harga).toLocaleString('id-ID')}
                              </span>
                              <div className="d-flex align-items-center">
                                <FaStar style={{ color: '#ffc107', marginRight: '4px' }} />
                                <span style={{ fontSize: '1rem', color: '#666' }}>
                                  {menu.rating || '4.5'}
                                </span>
                              </div>
                            </div>
                            <div className="d-flex gap-2">
                              <Button
                                variant="outline-secondary"
                                style={{ borderColor: '#FF6B35', color: '#FF6B35' }}
                              >
                                <FaEye className="me-1" />
                                Detail
                              </Button>
                              <Button
                                onClick={() => handleBuyItem(menu)}
                                disabled={buyingItems.has(menu.id)}
                                style={{
                                  backgroundColor: buyingItems.has(menu.id) ? '#6c757d' : '#FF6B35',
                                  borderColor: buyingItems.has(menu.id) ? '#6c757d' : '#FF6B35',
                                  flex: 1
                                }}
                              >
                                {buyingItems.has(menu.id) ? (
                                  <>
                                    <Spinner animation="border" size="sm" className="me-2" />
                                    Memproses...
                                  </>
                                ) : (
                                  <>
                                    <FaShoppingCart className="me-2" />
                                    Beli Sekarang
                                  </>
                                )}
                              </Button>
                            </div>
                          </Card.Body>
                        </Col>
                      </Row>
                    </>
                  )}
                </Card>
              </Col>
            ))}
          </Row>
        )}
      </Container>
    </div>
  );
};

export default MenuPage;
