import React from 'react';
import { useAuth } from '../contexts/AuthContext';
import { Link } from 'react-router-dom';
import { Row, Col, Button, Container } from 'react-bootstrap';
import { getPopularItems, formatPrice } from '../data/menuData';

const Home = () => {
  const { user, isAuthenticated, isAdmin } = useAuth();
  const popularItems = getPopularItems().slice(0, 6); // Get top 6 popular items

  return (
    <div style={{ backgroundColor: 'white', minHeight: '100vh' }}>
      {/* Clean Minimal Hero Section */}
      <div style={{
        backgroundColor: 'white',
        padding: '100px 20px 80px',
        borderBottom: '1px solid #f0f0f0'
      }}>
        <Container>
          <Row className="align-items-center">
            <Col lg={6}>
              {/* User Status - Clean Design */}
              {isAuthenticated ? (
                <div style={{
                  backgroundColor: '#f8f9fa',
                  padding: '16px 20px',
                  borderRadius: '12px',
                  marginBottom: '30px',
                  border: '1px solid #e9ecef'
                }}>
                  <div style={{ display: 'flex', alignItems: 'center' }}>
                    <div style={{
                      width: '32px',
                      height: '32px',
                      backgroundColor: '#FF6B35',
                      borderRadius: '50%',
                      display: 'flex',
                      alignItems: 'center',
                      justifyContent: 'center',
                      marginRight: '12px',
                      fontSize: '0.9rem',
                      color: 'white'
                    }}>
                      👋
                    </div>
                    <div>
                      <div style={{ fontWeight: '600', fontSize: '0.95rem', color: '#333' }}>
                        Selamat datang, {user?.name}
                      </div>
                      <div style={{ fontSize: '0.85rem', color: '#666' }}>
                        {user?.role}
                      </div>
                    </div>
                    {isAdmin() && (
                      <Link to="/admin" style={{
                        marginLeft: 'auto',
                        padding: '6px 12px',
                        backgroundColor: '#FF6B35',
                        color: 'white',
                        textDecoration: 'none',
                        borderRadius: '6px',
                        fontSize: '0.8rem',
                        fontWeight: '500'
                      }}>
                        Admin
                      </Link>
                    )}
                  </div>
                </div>
              ) : (
                <div style={{
                  backgroundColor: '#fff7f4',
                  padding: '16px 20px',
                  borderRadius: '12px',
                  marginBottom: '30px',
                  border: '1px solid #ffe4d6'
                }}>
                  <div style={{ fontSize: '0.9rem', color: '#666', textAlign: 'center' }}>
                    <Link to="/login" style={{
                      color: '#FF6B35',
                      textDecoration: 'none',
                      fontWeight: '500',
                      marginRight: '15px'
                    }}>
                      Masuk
                    </Link>
                    atau
                    <Link to="/register" style={{
                      color: '#FF6B35',
                      textDecoration: 'none',
                      fontWeight: '500',
                      marginLeft: '15px'
                    }}>
                      Daftar
                    </Link>
                  </div>
                </div>
              )}

              <h1 style={{
                fontSize: '3.5rem',
                fontWeight: '800',
                color: '#333',
                marginBottom: '20px',
                lineHeight: '1.1',
                letterSpacing: '-0.02em'
              }}>
                RestoKalel
              </h1>

              <p style={{
                fontSize: '1.25rem',
                color: '#666',
                marginBottom: '40px',
                lineHeight: '1.6',
                maxWidth: '500px'
              }}>
                Cita rasa autentik dengan pelayanan istimewa.
                Nikmati pengalaman kuliner tak terlupakan bersama kami.
              </p>

              <div style={{ display: 'flex', gap: '15px', flexWrap: 'wrap' }}>
                <Link to="/menu" style={{
                  backgroundColor: '#FF6B35',
                  color: 'white',
                  padding: '16px 32px',
                  borderRadius: '8px',
                  textDecoration: 'none',
                  fontWeight: '600',
                  fontSize: '1rem',
                  transition: 'all 0.2s ease',
                  display: 'inline-block'
                }}
                onMouseEnter={(e) => {
                  e.target.style.backgroundColor = '#E55A2B';
                  e.target.style.transform = 'translateY(-1px)';
                }}
                onMouseLeave={(e) => {
                  e.target.style.backgroundColor = '#FF6B35';
                  e.target.style.transform = 'translateY(0)';
                }}>
                  Lihat Menu
                </Link>
                <Button style={{
                  backgroundColor: 'white',
                  border: '2px solid #FF6B35',
                  color: '#FF6B35',
                  padding: '14px 32px',
                  borderRadius: '8px',
                  fontWeight: '600',
                  fontSize: '1rem'
                }}>
                  Reservasi
                </Button>
              </div>
            </Col>

            <Col lg={6}>
              <div style={{
                display: 'flex',
                justifyContent: 'center',
                alignItems: 'center',
                position: 'relative'
              }}>
                {/* Stats Cards */}
                <div style={{
                  position: 'absolute',
                  top: '20px',
                  right: '20px',
                  backgroundColor: 'white',
                  padding: '20px',
                  borderRadius: '16px',
                  boxShadow: '0 4px 20px rgba(0,0,0,0.08)',
                  textAlign: 'center',
                  minWidth: '120px'
                }}>
                  <div style={{ fontSize: '2rem', fontWeight: '700', color: '#FF6B35', marginBottom: '5px' }}>500+</div>
                  <div style={{ fontSize: '0.85rem', color: '#666' }}>Pelanggan</div>
                </div>

                <div style={{
                  position: 'absolute',
                  bottom: '20px',
                  left: '20px',
                  backgroundColor: 'white',
                  padding: '20px',
                  borderRadius: '16px',
                  boxShadow: '0 4px 20px rgba(0,0,0,0.08)',
                  textAlign: 'center',
                  minWidth: '120px'
                }}>
                  <div style={{ fontSize: '2rem', fontWeight: '700', color: '#FF6B35', marginBottom: '5px' }}>4.8⭐</div>
                  <div style={{ fontSize: '0.85rem', color: '#666' }}>Rating</div>
                </div>

                {/* Main Image Placeholder */}
                <div style={{
                  width: '350px',
                  height: '350px',
                  backgroundColor: '#f8f9fa',
                  borderRadius: '20px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  fontSize: '6rem',
                  color: '#FF6B35'
                }}>
                  🍽️
                </div>
              </div>
            </Col>
          </Row>
        </Container>
      </div>

      {/* Clean Menu Section */}
      <div style={{ padding: '80px 20px', backgroundColor: '#fafafa' }}>
        <Container>
          {/* Section Header */}
          <div style={{ textAlign: 'center', marginBottom: '50px' }}>
            <h2 style={{
              fontSize: '2.5rem',
              fontWeight: '700',
              color: '#333',
              marginBottom: '15px',
              letterSpacing: '-0.01em'
            }}>
              Menu Populer
            </h2>
            <p style={{
              fontSize: '1.1rem',
              color: '#666',
              maxWidth: '500px',
              margin: '0 auto'
            }}>
              Hidangan terbaik pilihan chef kami
            </p>
          </div>

          {/* Clean Grid Layout */}
          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fit, minmax(320px, 1fr))',
            gap: '20px',
            marginBottom: '50px'
          }}>
            {popularItems.map((item) => (
              <div key={item.id} style={{
                backgroundColor: 'white',
                borderRadius: '12px',
                overflow: 'hidden',
                border: '1px solid #e9ecef',
                transition: 'all 0.2s ease',
                cursor: 'pointer'
              }}
              onMouseEnter={(e) => {
                e.currentTarget.style.transform = 'translateY(-2px)';
                e.currentTarget.style.boxShadow = '0 8px 25px rgba(0,0,0,0.1)';
              }}
              onMouseLeave={(e) => {
                e.currentTarget.style.transform = 'translateY(0)';
                e.currentTarget.style.boxShadow = 'none';
              }}>
                {/* Image Section */}
                <div style={{
                  position: 'relative',
                  height: '200px',
                  overflow: 'hidden'
                }}>
                  <img
                    src={item.image}
                    alt={item.name}
                    style={{
                      width: '100%',
                      height: '100%',
                      objectFit: 'cover'
                    }}
                  />
                  {/* Simple Rating Badge */}
                  <div style={{
                    position: 'absolute',
                    top: '12px',
                    right: '12px',
                    backgroundColor: 'white',
                    padding: '6px 10px',
                    borderRadius: '6px',
                    fontSize: '0.8rem',
                    fontWeight: '600',
                    color: '#333',
                    boxShadow: '0 2px 8px rgba(0,0,0,0.1)'
                  }}>
                    ⭐ {item.rating}
                  </div>
                  {/* Category Badge */}
                  <div style={{
                    position: 'absolute',
                    top: '12px',
                    left: '12px',
                    backgroundColor: '#FF6B35',
                    color: 'white',
                    padding: '4px 8px',
                    borderRadius: '4px',
                    fontSize: '0.75rem',
                    fontWeight: '500'
                  }}>
                    {item.category}
                  </div>
                </div>

                {/* Content Section */}
                <div style={{ padding: '20px' }}>
                  <h3 style={{
                    fontSize: '1.2rem',
                    fontWeight: '600',
                    color: '#333',
                    marginBottom: '8px',
                    lineHeight: '1.3'
                  }}>
                    {item.name}
                  </h3>
                  <p style={{
                    color: '#666',
                    fontSize: '0.9rem',
                    marginBottom: '16px',
                    lineHeight: '1.4',
                    display: '-webkit-box',
                    WebkitLineClamp: 2,
                    WebkitBoxOrient: 'vertical',
                    overflow: 'hidden'
                  }}>
                    {item.description}
                  </p>

                  {/* Price and Action */}
                  <div style={{
                    display: 'flex',
                    justifyContent: 'space-between',
                    alignItems: 'center'
                  }}>
                    <div>
                      <div style={{
                        fontSize: '1.3rem',
                        fontWeight: '700',
                        color: '#FF6B35',
                        marginBottom: '4px'
                      }}>
                        {formatPrice(item.price)}
                      </div>
                      <div style={{
                        fontSize: '0.8rem',
                        color: '#999'
                      }}>
                        {item.preparationTime}
                      </div>
                    </div>
                    <Button style={{
                      backgroundColor: '#FF6B35',
                      border: 'none',
                      borderRadius: '6px',
                      padding: '10px 16px',
                      fontWeight: '600',
                      fontSize: '0.85rem',
                      transition: 'all 0.2s ease'
                    }}
                    onMouseEnter={(e) => {
                      e.currentTarget.style.backgroundColor = '#E55A2B';
                    }}
                    onMouseLeave={(e) => {
                      e.currentTarget.style.backgroundColor = '#FF6B35';
                    }}>
                      Pesan
                    </Button>
                  </div>
                </div>
              </div>
            ))}
          </div>

          {/* Call to Action */}
          <div style={{ textAlign: 'center' }}>
            <Link to="/menu" style={{
              display: 'inline-block',
              backgroundColor: 'white',
              border: '2px solid #FF6B35',
              color: '#FF6B35',
              padding: '16px 32px',
              borderRadius: '8px',
              textDecoration: 'none',
              fontWeight: '600',
              fontSize: '1rem',
              transition: 'all 0.2s ease'
            }}
            onMouseEnter={(e) => {
              e.currentTarget.style.backgroundColor = '#FF6B35';
              e.currentTarget.style.color = 'white';
            }}
            onMouseLeave={(e) => {
              e.currentTarget.style.backgroundColor = 'white';
              e.currentTarget.style.color = '#FF6B35';
            }}>
              Lihat Semua Menu
            </Link>
          </div>
        </Container>
      </div>

      {/* Clean Features Section */}
      <div style={{
        backgroundColor: 'white',
        padding: '80px 20px',
        borderTop: '1px solid #f0f0f0'
      }}>
        <Container>
          <div style={{ textAlign: 'center', marginBottom: '50px' }}>
            <h2 style={{
              fontSize: '2.5rem',
              fontWeight: '700',
              color: '#333',
              marginBottom: '15px',
              letterSpacing: '-0.01em'
            }}>
              Mengapa Memilih Kami
            </h2>
            <p style={{
              fontSize: '1.1rem',
              color: '#666',
              maxWidth: '500px',
              margin: '0 auto'
            }}>
              Komitmen kami untuk memberikan yang terbaik
            </p>
          </div>

          {/* Simple Stats Grid */}
          <div style={{
            display: 'grid',
            gridTemplateColumns: 'repeat(auto-fit, minmax(200px, 1fr))',
            gap: '40px',
            marginBottom: '60px'
          }}>
            <div style={{ textAlign: 'center' }}>
              <div style={{
                fontSize: '3rem',
                fontWeight: '700',
                color: '#FF6B35',
                marginBottom: '10px'
              }}>
                500+
              </div>
              <div style={{
                fontSize: '1.1rem',
                fontWeight: '600',
                color: '#333',
                marginBottom: '5px'
              }}>
                Pelanggan Puas
              </div>
              <div style={{
                fontSize: '0.9rem',
                color: '#666'
              }}>
                Kepuasan terjamin
              </div>
            </div>

            <div style={{ textAlign: 'center' }}>
              <div style={{
                fontSize: '3rem',
                fontWeight: '700',
                color: '#FF6B35',
                marginBottom: '10px'
              }}>
                4.8⭐
              </div>
              <div style={{
                fontSize: '1.1rem',
                fontWeight: '600',
                color: '#333',
                marginBottom: '5px'
              }}>
                Rating Rata-rata
              </div>
              <div style={{
                fontSize: '0.9rem',
                color: '#666'
              }}>
                Kualitas terbaik
              </div>
            </div>

            <div style={{ textAlign: 'center' }}>
              <div style={{
                fontSize: '3rem',
                fontWeight: '700',
                color: '#FF6B35',
                marginBottom: '10px'
              }}>
                35+
              </div>
              <div style={{
                fontSize: '1.1rem',
                fontWeight: '600',
                color: '#333',
                marginBottom: '5px'
              }}>
                Menu Pilihan
              </div>
              <div style={{
                fontSize: '0.9rem',
                color: '#666'
              }}>
                Variasi lengkap
              </div>
            </div>

            <div style={{ textAlign: 'center' }}>
              <div style={{
                fontSize: '3rem',
                fontWeight: '700',
                color: '#FF6B35',
                marginBottom: '10px'
              }}>
                5
              </div>
              <div style={{
                fontSize: '1.1rem',
                fontWeight: '600',
                color: '#333',
                marginBottom: '5px'
              }}>
                Kategori
              </div>
              <div style={{
                fontSize: '0.9rem',
                color: '#666'
              }}>
                Beragam pilihan
              </div>
            </div>
          </div>

          {/* Features Grid */}
          <div style={{
            backgroundColor: '#fafafa',
            padding: '50px 40px',
            borderRadius: '12px',
            border: '1px solid #f0f0f0'
          }}>
            <div style={{
              display: 'grid',
              gridTemplateColumns: 'repeat(auto-fit, minmax(250px, 1fr))',
              gap: '40px'
            }}>
              <div style={{ textAlign: 'center' }}>
                <div style={{
                  width: '60px',
                  height: '60px',
                  backgroundColor: '#FF6B35',
                  borderRadius: '12px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 20px',
                  fontSize: '1.5rem',
                  color: 'white'
                }}>
                  �
                </div>
                <div style={{
                  fontSize: '1.1rem',
                  fontWeight: '600',
                  color: '#333',
                  marginBottom: '8px'
                }}>
                  Pelayanan Cepat
                </div>
                <div style={{
                  fontSize: '0.9rem',
                  color: '#666',
                  lineHeight: '1.5'
                }}>
                  Maksimal 30 menit siap saji
                </div>
              </div>

              <div style={{ textAlign: 'center' }}>
                <div style={{
                  width: '60px',
                  height: '60px',
                  backgroundColor: '#FF6B35',
                  borderRadius: '12px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 20px',
                  fontSize: '1.5rem',
                  color: 'white'
                }}>
                  🌟
                </div>
                <div style={{
                  fontSize: '1.1rem',
                  fontWeight: '600',
                  color: '#333',
                  marginBottom: '8px'
                }}>
                  Bahan Premium
                </div>
                <div style={{
                  fontSize: '0.9rem',
                  color: '#666',
                  lineHeight: '1.5'
                }}>
                  Kualitas terjamin dan fresh
                </div>
              </div>

              <div style={{ textAlign: 'center' }}>
                <div style={{
                  width: '60px',
                  height: '60px',
                  backgroundColor: '#FF6B35',
                  borderRadius: '12px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 20px',
                  fontSize: '1.5rem',
                  color: 'white'
                }}>
                  👨‍🍳
                </div>
                <div style={{
                  fontSize: '1.1rem',
                  fontWeight: '600',
                  color: '#333',
                  marginBottom: '8px'
                }}>
                  Chef Berpengalaman
                </div>
                <div style={{
                  fontSize: '0.9rem',
                  color: '#666',
                  lineHeight: '1.5'
                }}>
                  10+ tahun pengalaman kuliner
                </div>
              </div>

              <div style={{ textAlign: 'center' }}>
                <div style={{
                  width: '60px',
                  height: '60px',
                  backgroundColor: '#FF6B35',
                  borderRadius: '12px',
                  display: 'flex',
                  alignItems: 'center',
                  justifyContent: 'center',
                  margin: '0 auto 20px',
                  fontSize: '1.5rem',
                  color: 'white'
                }}>
                  🏆
                </div>
                <div style={{
                  fontSize: '1.1rem',
                  fontWeight: '600',
                  color: '#333',
                  marginBottom: '8px'
                }}>
                  Award Winner
                </div>
                <div style={{
                  fontSize: '0.9rem',
                  color: '#666',
                  lineHeight: '1.5'
                }}>
                  Best Restaurant 2023
                </div>
              </div>
            </div>
          </div>
        </Container>
      </div>
    </div>
  );
};

export default Home;
