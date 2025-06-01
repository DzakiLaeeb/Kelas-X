import { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Row, Col, Form, Button, Alert, Spinner } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

const Register = () => {
  const [formData, setFormData] = useState({
    name: '',
    username: '',
    email: '',
    password: '',
    confirmPassword: ''
  });
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const navigate = useNavigate();
  const { register, loading, isAuthenticated } = useAuth();

  // Redirect if already authenticated
  useEffect(() => {
    if (isAuthenticated) {
      navigate('/');
    }
  }, [isAuthenticated, navigate]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const validateForm = () => {
    if (!formData.name.trim()) {
      setError('Nama lengkap harus diisi');
      return false;
    }
    if (!formData.username.trim()) {
      setError('Username harus diisi');
      return false;
    }
    if (formData.username.length < 3) {
      setError('Username minimal 3 karakter');
      return false;
    }
    if (!formData.email.trim()) {
      setError('Email harus diisi');
      return false;
    }
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(formData.email)) {
      setError('Format email tidak valid');
      return false;
    }
    if (!formData.password) {
      setError('Password harus diisi');
      return false;
    }
    if (formData.password.length < 6) {
      setError('Password minimal 6 karakter');
      return false;
    }
    if (formData.password !== formData.confirmPassword) {
      setError('Konfirmasi password tidak cocok');
      return false;
    }
    return true;
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    if (!validateForm()) {
      return;
    }

    try {
      const { confirmPassword, ...dataToSend } = formData;
      const result = await register(dataToSend);

      if (result.success) {
        setSuccess('Registrasi berhasil! Silakan login.');
        setTimeout(() => navigate('/login'), 2000);
      } else {
        setError(result.error || 'Registrasi gagal');
      }
    } catch (err) {
      console.error('Registration error:', err);
      setError('Terjadi kesalahan saat registrasi');
    }
  };

  return (
    <div style={{
      minHeight: '100vh',
      background: 'linear-gradient(135deg, #E55A2B 0%, #FF6B35 50%, #E55A2B 100%)',
      position: 'relative',
      overflow: 'hidden'
    }}>
      {/* Background Pattern */}
      <div style={{
        position: 'absolute',
        top: 0,
        left: 0,
        right: 0,
        bottom: 0,
        backgroundImage: `url("data:image/svg+xml,%3Csvg width='40' height='40' viewBox='0 0 40 40' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.04'%3E%3Cpath d='M20 20c0-5.5-4.5-10-10-10s-10 4.5-10 10 4.5 10 10 10 10-4.5 10-10zm10 0c0-5.5-4.5-10-10-10s-10 4.5-10 10 4.5 10 10 10 10-4.5 10-10z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
        opacity: 0.3
      }}></div>

      {/* Floating Elements */}
      <div style={{
        position: 'absolute',
        top: '15%',
        right: '10%',
        width: '120px',
        height: '120px',
        backgroundColor: 'rgba(255,255,255,0.08)',
        borderRadius: '50%',
        animation: 'float 7s ease-in-out infinite'
      }}></div>
      <div style={{
        position: 'absolute',
        bottom: '20%',
        left: '8%',
        width: '80px',
        height: '80px',
        backgroundColor: 'rgba(255,255,255,0.06)',
        borderRadius: '20px',
        transform: 'rotate(30deg)',
        animation: 'float 9s ease-in-out infinite reverse'
      }}></div>

      {/* Main Content */}
      <div style={{
        position: 'relative',
        zIndex: 2,
        minHeight: '100vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '40px 20px'
      }}>
        <div style={{
          width: '100%',
          maxWidth: '550px',
          backgroundColor: 'rgba(255,255,255,0.96)',
          backdropFilter: 'blur(20px)',
          borderRadius: '24px',
          padding: '50px 45px',
          boxShadow: '0 25px 70px rgba(0,0,0,0.12)',
          border: '1px solid rgba(255,255,255,0.3)'
        }}>
          {/* Header */}
          <div style={{ textAlign: 'center', marginBottom: '40px' }}>
            <div style={{
              width: '70px',
              height: '70px',
              backgroundColor: '#FF6B35',
              borderRadius: '50%',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              margin: '0 auto 20px',
              fontSize: '2rem',
              color: 'white',
              boxShadow: '0 8px 25px rgba(255,107,53,0.3)'
            }}>
              ✨
            </div>
            <h1 style={{
              fontSize: '2rem',
              fontWeight: '700',
              color: '#333',
              marginBottom: '8px',
              lineHeight: '1.2'
            }}>
              Bergabung dengan Kami
            </h1>
            <p style={{
              fontSize: '0.95rem',
              color: '#666',
              marginBottom: '0'
            }}>
              Buat akun RestoKalel dan nikmati pengalaman terbaik
            </p>
          </div>

          {/* Alerts */}
          {error && (
            <Alert variant="danger" style={{
              marginBottom: '25px',
              borderRadius: '12px',
              border: 'none',
              backgroundColor: '#fee',
              color: '#c33'
            }}>
              {error}
            </Alert>
          )}
          {success && (
            <Alert variant="success" style={{
              marginBottom: '25px',
              borderRadius: '12px',
              border: 'none',
              backgroundColor: '#efe',
              color: '#363'
            }}>
              {success}
            </Alert>
          )}

          {/* Registration Form */}
          <Form onSubmit={handleSubmit}>
            <Row>
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label style={{
                    fontWeight: '600',
                    color: '#333',
                    marginBottom: '8px',
                    fontSize: '0.9rem'
                  }}>
                    👤 Nama Lengkap
                  </Form.Label>
                  <Form.Control
                    type="text"
                    name="name"
                    value={formData.name}
                    onChange={handleChange}
                    placeholder="Masukkan nama lengkap"
                    style={{
                      padding: '14px 18px',
                      border: '2px solid #f0f0f0',
                      borderRadius: '10px',
                      fontSize: '0.95rem',
                      backgroundColor: '#fafafa',
                      transition: 'all 0.3s ease'
                    }}
                    onFocus={(e) => {
                      e.target.style.borderColor = '#FF6B35';
                      e.target.style.backgroundColor = 'white';
                      e.target.style.boxShadow = '0 0 0 3px rgba(255,107,53,0.1)';
                    }}
                    onBlur={(e) => {
                      e.target.style.borderColor = '#f0f0f0';
                      e.target.style.backgroundColor = '#fafafa';
                      e.target.style.boxShadow = 'none';
                    }}
                    required
                  />
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label style={{
                    fontWeight: '600',
                    color: '#333',
                    marginBottom: '8px',
                    fontSize: '0.9rem'
                  }}>
                    🏷️ Username
                  </Form.Label>
                  <Form.Control
                    type="text"
                    name="username"
                    value={formData.username}
                    onChange={handleChange}
                    placeholder="Pilih username unik"
                    style={{
                      padding: '14px 18px',
                      border: '2px solid #f0f0f0',
                      borderRadius: '10px',
                      fontSize: '0.95rem',
                      backgroundColor: '#fafafa',
                      transition: 'all 0.3s ease'
                    }}
                    onFocus={(e) => {
                      e.target.style.borderColor = '#FF6B35';
                      e.target.style.backgroundColor = 'white';
                      e.target.style.boxShadow = '0 0 0 3px rgba(255,107,53,0.1)';
                    }}
                    onBlur={(e) => {
                      e.target.style.borderColor = '#f0f0f0';
                      e.target.style.backgroundColor = '#fafafa';
                      e.target.style.boxShadow = 'none';
                    }}
                    required
                  />
                  <Form.Text style={{ fontSize: '0.8rem', color: '#999' }}>
                    Min 3 karakter, huruf, angka, dan underscore
                  </Form.Text>
                </Form.Group>
              </Col>
            </Row>

            <Form.Group className="mb-3">
              <Form.Label style={{
                fontWeight: '600',
                color: '#333',
                marginBottom: '8px',
                fontSize: '0.9rem'
              }}>
                📧 Email Address
              </Form.Label>
              <Form.Control
                type="email"
                name="email"
                value={formData.email}
                onChange={handleChange}
                placeholder="Masukkan alamat email"
                style={{
                  padding: '14px 18px',
                  border: '2px solid #f0f0f0',
                  borderRadius: '10px',
                  fontSize: '0.95rem',
                  backgroundColor: '#fafafa',
                  transition: 'all 0.3s ease'
                }}
                onFocus={(e) => {
                  e.target.style.borderColor = '#FF6B35';
                  e.target.style.backgroundColor = 'white';
                  e.target.style.boxShadow = '0 0 0 3px rgba(255,107,53,0.1)';
                }}
                onBlur={(e) => {
                  e.target.style.borderColor = '#f0f0f0';
                  e.target.style.backgroundColor = '#fafafa';
                  e.target.style.boxShadow = 'none';
                }}
                required
              />
            </Form.Group>

            <Row>
              <Col md={6}>
                <Form.Group className="mb-3">
                  <Form.Label style={{
                    fontWeight: '600',
                    color: '#333',
                    marginBottom: '8px',
                    fontSize: '0.9rem'
                  }}>
                    🔒 Password
                  </Form.Label>
                  <Form.Control
                    type="password"
                    name="password"
                    value={formData.password}
                    onChange={handleChange}
                    placeholder="Buat password"
                    style={{
                      padding: '14px 18px',
                      border: '2px solid #f0f0f0',
                      borderRadius: '10px',
                      fontSize: '0.95rem',
                      backgroundColor: '#fafafa',
                      transition: 'all 0.3s ease'
                    }}
                    onFocus={(e) => {
                      e.target.style.borderColor = '#FF6B35';
                      e.target.style.backgroundColor = 'white';
                      e.target.style.boxShadow = '0 0 0 3px rgba(255,107,53,0.1)';
                    }}
                    onBlur={(e) => {
                      e.target.style.borderColor = '#f0f0f0';
                      e.target.style.backgroundColor = '#fafafa';
                      e.target.style.boxShadow = 'none';
                    }}
                    required
                  />
                  <Form.Text style={{ fontSize: '0.8rem', color: '#999' }}>
                    Minimal 6 karakter
                  </Form.Text>
                </Form.Group>
              </Col>
              <Col md={6}>
                <Form.Group className="mb-4">
                  <Form.Label style={{
                    fontWeight: '600',
                    color: '#333',
                    marginBottom: '8px',
                    fontSize: '0.9rem'
                  }}>
                    🔐 Konfirmasi Password
                  </Form.Label>
                  <Form.Control
                    type="password"
                    name="confirmPassword"
                    value={formData.confirmPassword}
                    onChange={handleChange}
                    placeholder="Ulangi password"
                    style={{
                      padding: '14px 18px',
                      border: '2px solid #f0f0f0',
                      borderRadius: '10px',
                      fontSize: '0.95rem',
                      backgroundColor: '#fafafa',
                      transition: 'all 0.3s ease'
                    }}
                    onFocus={(e) => {
                      e.target.style.borderColor = '#FF6B35';
                      e.target.style.backgroundColor = 'white';
                      e.target.style.boxShadow = '0 0 0 3px rgba(255,107,53,0.1)';
                    }}
                    onBlur={(e) => {
                      e.target.style.borderColor = '#f0f0f0';
                      e.target.style.backgroundColor = '#fafafa';
                      e.target.style.boxShadow = 'none';
                    }}
                    required
                  />
                </Form.Group>
              </Col>
            </Row>

            <Button
              type="submit"
              style={{
                width: '100%',
                padding: '16px',
                backgroundColor: '#FF6B35',
                border: 'none',
                borderRadius: '12px',
                fontSize: '1.1rem',
                fontWeight: '600',
                marginBottom: '25px',
                boxShadow: '0 8px 25px rgba(255,107,53,0.3)',
                transition: 'all 0.3s ease'
              }}
              onMouseEnter={(e) => {
                e.target.style.backgroundColor = '#E55A2B';
                e.target.style.transform = 'translateY(-2px)';
                e.target.style.boxShadow = '0 12px 35px rgba(255,107,53,0.4)';
              }}
              onMouseLeave={(e) => {
                e.target.style.backgroundColor = '#FF6B35';
                e.target.style.transform = 'translateY(0)';
                e.target.style.boxShadow = '0 8px 25px rgba(255,107,53,0.3)';
              }}
              disabled={loading}
            >
              {loading ? (
                <>
                  <Spinner
                    as="span"
                    animation="border"
                    size="sm"
                    role="status"
                    aria-hidden="true"
                    className="me-2"
                  />
                  Membuat Akun...
                </>
              ) : (
                '🎉 Buat Akun Sekarang'
              )}
            </Button>
          </Form>

          {/* Info Note */}
          <div style={{
            backgroundColor: '#f8f9fa',
            padding: '18px',
            borderRadius: '12px',
            marginBottom: '25px',
            border: '1px solid #e9ecef'
          }}>
            <div style={{
              display: 'flex',
              alignItems: 'center',
              fontSize: '0.9rem',
              color: '#666'
            }}>
              <span style={{ fontSize: '1.2rem', marginRight: '10px' }}>ℹ️</span>
              <div>
                <strong style={{ color: '#FF6B35' }}>Info:</strong> Akun baru dibuat dengan role <strong>User</strong>.
                Hubungi admin untuk upgrade role.
              </div>
            </div>
          </div>

          {/* Login Link */}
          <div style={{ textAlign: 'center' }}>
            <p style={{ color: '#666', marginBottom: '15px', fontSize: '0.95rem' }}>
              Sudah punya akun?
            </p>
            <Link
              to="/login"
              style={{
                display: 'inline-block',
                padding: '12px 25px',
                color: '#FF6B35',
                textDecoration: 'none',
                border: '2px solid #FF6B35',
                borderRadius: '12px',
                fontWeight: '600',
                fontSize: '0.95rem',
                transition: 'all 0.3s ease'
              }}
              onMouseEnter={(e) => {
                e.target.style.backgroundColor = '#FF6B35';
                e.target.style.color = 'white';
                e.target.style.transform = 'translateY(-2px)';
              }}
              onMouseLeave={(e) => {
                e.target.style.backgroundColor = 'transparent';
                e.target.style.color = '#FF6B35';
                e.target.style.transform = 'translateY(0)';
              }}
            >
              🚀 Masuk Sekarang
            </Link>
          </div>
        </div>
      </div>

      {/* CSS Animation */}
      <style jsx>{`
        @keyframes float {
          0%, 100% { transform: translateY(0px); }
          50% { transform: translateY(-15px); }
        }
      `}</style>
    </div>
  );
};

export default Register;