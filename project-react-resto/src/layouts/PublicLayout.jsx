import React, { useState } from 'react';
import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom';
import { Container, Button } from 'react-bootstrap';
import { useAuth } from '../contexts/AuthContext';

/**
 * Layout untuk halaman publik
 * Menyediakan sidebar navigasi dan area konten utama
 */
const PublicLayout = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const { user, isAuthenticated, logout, isAdmin } = useAuth();
  const [isSidebarCollapsed, setIsSidebarCollapsed] = useState(false);

  // Menu items untuk pengguna publik (tanpa prefix /admin)
  const publicMenuItems = [
    { path: '.', label: 'Home', icon: '🏠' },
    { path: 'kategori', label: 'Kategori', icon: '📊' },
    { path: 'menu', label: 'Menu', icon: '🍽️' },
    { path: 'pelanggan', label: 'Pelanggan', icon: '👥' },
    { path: 'order', label: 'Order', icon: '📝' },
    { path: 'order-detail', label: 'Order Detail', icon: '📋' },
    { path: 'orders', label: 'Daftar Pesanan', icon: '🛒' }
  ];

  const handleLogout = () => {
    logout();
    navigate('/');
  };

  return (
    <div className="App">

      {/* Sidebar Toggle Button */}
      <button
        className={`sidebar-toggle ${isSidebarCollapsed ? 'collapsed' : ''}`}
        onClick={() => setIsSidebarCollapsed(!isSidebarCollapsed)}
        aria-label="Toggle Sidebar"
      >
        {isSidebarCollapsed ? '→' : '←'}
      </button>

      {/* Sidebar */}
      <div className={`sidebar ${isSidebarCollapsed ? 'collapsed' : ''}`}>
        <div className="sidebar-content">
          {/* Top Section */}
          <div className="sidebar-top">
            <h3 className="mb-4 text-center">
              <Link to="/" style={{ textDecoration: 'none' }}>
                <span className="brand-gradient">Resto Prismatic</span>
              </Link>
            </h3>

            <div className="nav-container">
              <div className="nav-header">
                Navigation Menu
              </div>
              <div className="nav-body">
                <ul className="nav flex-column">
                  {publicMenuItems.map((item) => (
                    <li className="nav-item" key={item.path}>
                      <Link
                        to={item.path}
                        className={`nav-link ${location.pathname === '/' + item.path ? 'active' : ''}`}
                      >
                        <span className="nav-icon">{item.icon}</span>
                        <span className="nav-label">{item.label}</span>
                      </Link>
                    </li>
                  ))}
                </ul>
              </div>
            </div>
          </div>

          {/* Bottom Section - User Profile */}
          <div className="sidebar-bottom">
            <div className="user-profile">
              {isAuthenticated ? (
                <div className="profile-card">
                  <div className="profile-info">
                    <div className="profile-avatar">👤</div>
                    <div className="profile-details">
                      <div className="profile-name">{user?.name}</div>
                      <div className="profile-role">{user?.role}</div>
                    </div>
                  </div>
                  <div className="profile-actions">
                    {isAdmin() && (
                      <Link to="/admin" className="btn btn-secondary btn-sm w-100 mb-2">
                        <i className="fas fa-cog me-1"></i> Admin Panel
                      </Link>
                    )}
                    <Button
                      variant="outline-danger"
                      size="sm"
                      className="w-100"
                      onClick={handleLogout}
                    >
                      Logout
                    </Button>
                  </div>
                </div>
              ) : (
                <div className="auth-buttons">
                  <Link to="/login" className="btn btn-outline-primary btn-sm w-100 mb-2">
                    Login
                  </Link>
                  <Link to="/register" className="btn btn-outline-success btn-sm w-100">
                    Register
                  </Link>
                </div>
              )}
            </div>
          </div>
        </div>
      </div>

      {/* Main Content */}
      <div className={`main-content ${isSidebarCollapsed ? 'expanded' : ''}`}>
        <Container fluid className="p-4">
          <Outlet />
        </Container>
      </div>
    </div>
  );
};

export default PublicLayout;
