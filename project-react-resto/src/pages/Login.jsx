import { useState, useEffect } from 'react';
import { useNavigate, Link } from 'react-router-dom';
import { Form, Button, Alert, Spinner } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

const Login = () => {
  const [formData, setFormData] = useState({ username: '', password: '' });
  const [error, setError] = useState('');
  const [success, setSuccess] = useState('');
  const navigate = useNavigate();
  const { login, loading, isAuthenticated, user } = useAuth();

  // Redirect if already authenticated
  useEffect(() => {
    if (isAuthenticated && user) {
      if (user.role === 'admin') {
        navigate('/admin');
      } else {
        navigate('/');
      }
    }
  }, [isAuthenticated, user, navigate]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData(prev => ({
      ...prev,
      [name]: value
    }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setError('');
    setSuccess('');

    console.log('🚀 Form submitted with data:', formData);

    // Validation
    if (!formData.username.trim() || !formData.password.trim()) {
      console.log('❌ Validation failed: empty fields');
      setError('Username dan password harus diisi');
      return;
    }

    console.log('✅ Validation passed, calling login...');

    try {
      const result = await login(formData);
      console.log('📨 Login result:', result);

      if (result.success) {
        console.log('🎉 Login successful!');
        setSuccess('Login berhasil! Mengalihkan...');

        // Redirect based on role
        setTimeout(() => {
          const userRole = result.user.role;
          console.log('🔄 Redirecting user with role:', userRole);

          switch (userRole) {
            case 'admin':
              console.log('➡️ Navigating to /admin');
              navigate('/admin');
              break;
            case 'koki':
              console.log('➡️ Navigating to /koki');
              navigate('/koki');
              break;
            case 'kasir':
              console.log('➡️ Navigating to /kasir');
              navigate('/kasir');
              break;
            case 'user':
            default:
              console.log('➡️ Navigating to /');
              navigate('/');
              break;
          }
        }, 1000);
      } else {
        console.log('❌ Login failed:', result.error);
        setError(result.error || 'Login gagal');
      }
    } catch (err) {
      console.error('💥 Login exception:', err);
      setError('Terjadi kesalahan saat login');
    }
  };

  return (
    <div style={{
      minHeight: '100vh',
      background: 'linear-gradient(135deg, #FF6B35 0%, #E55A2B 50%, #FF6B35 100%)',
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
        backgroundImage: `url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23ffffff' fill-opacity='0.03'%3E%3Ccircle cx='30' cy='30' r='2'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E")`,
        opacity: 0.5
      }}></div>

      {/* Floating Shapes */}
      <div style={{
        position: 'absolute',
        top: '10%',
        left: '10%',
        width: '100px',
        height: '100px',
        backgroundColor: 'rgba(255,255,255,0.1)',
        borderRadius: '50%',
        animation: 'float 6s ease-in-out infinite'
      }}></div>
      <div style={{
        position: 'absolute',
        top: '60%',
        right: '15%',
        width: '150px',
        height: '150px',
        backgroundColor: 'rgba(255,255,255,0.05)',
        borderRadius: '30px',
        transform: 'rotate(45deg)',
        animation: 'float 8s ease-in-out infinite reverse'
      }}></div>

      {/* Main Content */}
      <div style={{
        position: 'relative',
        zIndex: 2,
        minHeight: '100vh',
        display: 'flex',
        alignItems: 'center',
        justifyContent: 'center',
        padding: '20px'
      }}>
        <div style={{
          width: '100%',
          maxWidth: '450px',
          backgroundColor: 'rgba(255,255,255,0.95)',
          backdropFilter: 'blur(20px)',
          borderRadius: '24px',
          padding: '50px 40px',
          boxShadow: '0 20px 60px rgba(0,0,0,0.1)',
          border: '1px solid rgba(255,255,255,0.2)'
        }}>
          {/* Header */}
          <div style={{ textAlign: 'center', marginBottom: '40px' }}>
            <div style={{
              width: '80px',
              height: '80px',
              backgroundColor: '#FF6B35',
              borderRadius: '50%',
              display: 'flex',
              alignItems: 'center',
              justifyContent: 'center',
              margin: '0 auto 20px',
              fontSize: '2.5rem',
              color: 'white',
              boxShadow: '0 10px 30px rgba(255,107,53,0.3)'
            }}>
              🍽️
            </div>
            <h1 style={{
              fontSize: '2.2rem',
              fontWeight: '700',
              color: '#333',
              marginBottom: '10px',
              lineHeight: '1.2'
            }}>
              Selamat Datang!
            </h1>
            <p style={{
              fontSize: '1rem',
              color: '#666',
              marginBottom: '0'
            }}>
              Masuk ke akun RestoKalel Anda
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

          {/* Login Form */}
          <Form onSubmit={handleSubmit}>
            <Form.Group className="mb-4">
              <Form.Label style={{
                fontWeight: '600',
                color: '#333',
                marginBottom: '10px',
                fontSize: '0.95rem'
              }}>
                👤 Username
              </Form.Label>
              <Form.Control
                type="text"
                name="username"
                value={formData.username}
                onChange={handleChange}
                placeholder="Masukkan username Anda"
                style={{
                  padding: '16px 20px',
                  border: '2px solid #f0f0f0',
                  borderRadius: '12px',
                  fontSize: '1rem',
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

            <Form.Group className="mb-4">
              <Form.Label style={{
                fontWeight: '600',
                color: '#333',
                marginBottom: '10px',
                fontSize: '0.95rem'
              }}>
                🔒 Password
              </Form.Label>
              <Form.Control
                type="password"
                name="password"
                value={formData.password}
                onChange={handleChange}
                placeholder="Masukkan password Anda"
                style={{
                  padding: '16px 20px',
                  border: '2px solid #f0f0f0',
                  borderRadius: '12px',
                  fontSize: '1rem',
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
                  Masuk...
                </>
              ) : (
                '🚀 Masuk Sekarang'
              )}
            </Button>
          </Form>

          {/* Demo Accounts */}
          <div style={{
            marginBottom: '25px',
            padding: '20px',
            backgroundColor: '#f8f9fa',
            borderRadius: '16px',
            border: '1px solid #e9ecef'
          }}>
            <h6 style={{
              color: '#FF6B35',
              marginBottom: '15px',
              fontWeight: '700',
              textAlign: 'center'
            }}>
              🎯 Akun Demo Tersedia
            </h6>

            <div style={{
              display: 'grid',
              gridTemplateColumns: '1fr 1fr',
              gap: '12px',
              fontSize: '0.85rem'
            }}>
              <div style={{
                padding: '10px',
                backgroundColor: 'white',
                borderRadius: '8px',
                textAlign: 'center'
              }}>
                <div style={{ fontWeight: '600', color: '#333', marginBottom: '4px' }}>👑 Admin</div>
                <div style={{ color: '#666' }}>admin / admin123</div>
              </div>
              <div style={{
                padding: '10px',
                backgroundColor: 'white',
                borderRadius: '8px',
                textAlign: 'center'
              }}>
                <div style={{ fontWeight: '600', color: '#333', marginBottom: '4px' }}>💰 Kasir</div>
                <div style={{ color: '#666' }}>kasir / kasir123</div>
              </div>
              <div style={{
                padding: '10px',
                backgroundColor: 'white',
                borderRadius: '8px',
                textAlign: 'center'
              }}>
                <div style={{ fontWeight: '600', color: '#333', marginBottom: '4px' }}>👨‍🍳 Koki</div>
                <div style={{ color: '#666' }}>koki / koki123</div>
              </div>
              <div style={{
                padding: '10px',
                backgroundColor: 'white',
                borderRadius: '8px',
                textAlign: 'center'
              }}>
                <div style={{ fontWeight: '600', color: '#333', marginBottom: '4px' }}>👤 User</div>
                <div style={{ color: '#666' }}>user / user123</div>
              </div>
            </div>
          </div>

          {/* Register Link */}
          <div style={{ textAlign: 'center' }}>
            <p style={{ color: '#666', marginBottom: '15px', fontSize: '0.95rem' }}>
              Belum punya akun?
            </p>
            <Link
              to="/register"
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
              ✨ Buat Akun Baru
            </Link>
          </div>
        </div>
      </div>

      {/* CSS Animation */}
      <style jsx>{`
        @keyframes float {
          0%, 100% { transform: translateY(0px); }
          50% { transform: translateY(-20px); }
        }
      `}</style>
    </div>
  );
};

export default Login;