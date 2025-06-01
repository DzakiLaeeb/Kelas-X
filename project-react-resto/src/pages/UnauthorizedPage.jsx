import React from 'react';
import { Container, Card, Button } from 'react-bootstrap';
import { Link, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

/**
 * Halaman Unauthorized
 * Ditampilkan ketika user tidak memiliki akses ke halaman tertentu
 */
const UnauthorizedPage = () => {
  const navigate = useNavigate();
  const { user, isAdmin, isKoki, isKasir } = useAuth();

  const handleGoBack = () => {
    navigate(-1);
  };

  const getRedirectPath = () => {
    if (isAdmin()) return '/admin';
    if (isKoki()) return '/koki';
    if (isKasir()) return '/kasir';
    return '/';
  };

  return (
    <Container className="d-flex justify-content-center align-items-center" style={{ minHeight: '80vh' }}>
      <Card className="text-center shadow-lg" style={{ maxWidth: '500px', width: '100%' }}>
        <Card.Body className="p-5">
          {/* Icon */}
          <div className="mb-4">
            <div style={{ fontSize: '5rem' }}>🚫</div>
          </div>

          {/* Title */}
          <h2 className="text-danger mb-3">Akses Ditolak</h2>
          
          {/* Message */}
          <p className="text-muted mb-4">
            Maaf, Anda tidak memiliki izin untuk mengakses halaman ini.
          </p>

          {/* User Info */}
          {user && (
            <div className="mb-4 p-3 bg-light rounded">
              <small className="text-muted">Anda login sebagai:</small>
              <div className="fw-bold">{user.name}</div>
              <div className="text-primary">
                {user.role === 'admin' && '👤 Administrator'}
                {user.role === 'koki' && '👨‍🍳 Koki'}
                {user.role === 'kasir' && '💰 Kasir'}
                {user.role === 'user' && '👥 User'}
              </div>
            </div>
          )}

          {/* Action Buttons */}
          <div className="d-grid gap-2">
            <Button 
              variant="primary" 
              as={Link} 
              to={getRedirectPath()}
              className="mb-2"
            >
              🏠 Kembali ke Dashboard
            </Button>
            
            <Button 
              variant="outline-secondary" 
              onClick={handleGoBack}
              className="mb-2"
            >
              ← Kembali ke Halaman Sebelumnya
            </Button>

            <Button 
              variant="outline-info" 
              as={Link} 
              to="/"
              size="sm"
            >
              🏠 Halaman Utama
            </Button>
          </div>

          {/* Help Text */}
          <div className="mt-4 pt-3 border-top">
            <small className="text-muted">
              Jika Anda merasa ini adalah kesalahan, silakan hubungi administrator.
            </small>
          </div>
        </Card.Body>
      </Card>
    </Container>
  );
};

export default UnauthorizedPage;
