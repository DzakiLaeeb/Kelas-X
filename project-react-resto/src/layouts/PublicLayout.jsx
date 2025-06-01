import React, { useState } from 'react';
import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom';
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
    { path: 'order-detail', label: 'Order Detail', icon: '📋' }
  ];

  const handleLogout = () => {
    logout();
    navigate('/');
  };

  return (
    <div style={{ display: 'flex', minHeight: '100vh' }}>
      {/* Simple Sidebar */}
      <div style={{
        width: isSidebarCollapsed ? '60px' : '250px',
        backgroundColor: 'white',
        borderRight: '1px solid #e9ecef',
        transition: 'width 0.3s ease',
        position: 'fixed',
        height: '100vh',
        overflowY: 'auto',
        zIndex: 1000
      }}>
        {/* Toggle Button */}
        <div style={{ padding: '15px', borderBottom: '1px solid #e9ecef' }}>
          <button
            onClick={() => setIsSidebarCollapsed(!isSidebarCollapsed)}
            style={{
              background: 'none',
              border: 'none',
              fontSize: '18px',
              cursor: 'pointer',
              color: '#666'
            }}
          >
            {isSidebarCollapsed ? '→' : '←'}
          </button>
        </div>

        {/* Brand */}
        {!isSidebarCollapsed && (
          <div style={{ padding: '20px', textAlign: 'center', borderBottom: '1px solid #e9ecef' }}>
            <Link to="/" style={{ textDecoration: 'none' }}>
              <h4 style={{ color: '#FF6B35', margin: 0, fontWeight: '300' }}>
                RestoKalel
              </h4>
            </Link>
          </div>
        )}

        {/* Navigation */}
        <div style={{ padding: '10px 0' }}>
          {!isSidebarCollapsed && (
            <div style={{
              padding: '10px 20px',
              fontSize: '0.9rem',
              color: '#666',
              fontWeight: '500',
              borderBottom: '1px solid #f8f9fa',
              marginBottom: '10px'
            }}>
              Menu Navigasi
            </div>
          )}

          {publicMenuItems.map((item) => {
            const isActive = location.pathname === '/' + item.path ||
                           (item.path === '.' && location.pathname === '/');

            return (
              <Link
                key={item.path}
                to={item.path}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  padding: isSidebarCollapsed ? '15px 20px' : '12px 20px',
                  textDecoration: 'none',
                  color: isActive ? '#FF6B35' : '#666',
                  backgroundColor: isActive ? '#FFFAF7' : 'transparent',
                  borderLeft: isActive ? '3px solid #FF6B35' : '3px solid transparent',
                  transition: 'all 0.2s ease'
                }}
                onMouseEnter={(e) => {
                  if (!isActive) {
                    e.target.style.backgroundColor = '#f8f9fa';
                  }
                }}
                onMouseLeave={(e) => {
                  if (!isActive) {
                    e.target.style.backgroundColor = 'transparent';
                  }
                }}
              >
                <span style={{ fontSize: '16px', marginRight: isSidebarCollapsed ? '0' : '10px' }}>
                  {item.icon}
                </span>
                {!isSidebarCollapsed && (
                  <span style={{ fontSize: '14px' }}>{item.label}</span>
                )}
              </Link>
            );
          })}
        </div>

        {/* Bottom Section - User Profile */}
        {!isSidebarCollapsed && (
          <div style={{
            marginTop: 'auto',
            padding: '20px',
            borderTop: '1px solid #e9ecef'
          }}>
            {isAuthenticated ? (
              <div>
                <div style={{
                  display: 'flex',
                  alignItems: 'center',
                  marginBottom: '15px',
                  padding: '10px',
                  backgroundColor: '#f8f9fa',
                  borderRadius: '8px'
                }}>
                  <span style={{ fontSize: '20px', marginRight: '10px' }}>👤</span>
                  <div>
                    <div style={{ fontSize: '14px', fontWeight: '500', color: '#333' }}>
                      {user?.name}
                    </div>
                    <div style={{ fontSize: '12px', color: '#666' }}>
                      {user?.role}
                    </div>
                  </div>
                </div>
                {isAdmin() && (
                  <Link
                    to="/admin"
                    style={{
                      display: 'block',
                      padding: '8px 12px',
                      backgroundColor: '#6c757d',
                      color: 'white',
                      textDecoration: 'none',
                      borderRadius: '4px',
                      fontSize: '12px',
                      textAlign: 'center',
                      marginBottom: '8px'
                    }}
                  >
                    Admin Panel
                  </Link>
                )}
                <button
                  onClick={handleLogout}
                  style={{
                    width: '100%',
                    padding: '8px 12px',
                    backgroundColor: 'transparent',
                    color: '#dc3545',
                    border: '1px solid #dc3545',
                    borderRadius: '4px',
                    fontSize: '12px',
                    cursor: 'pointer'
                  }}
                >
                  Logout
                </button>
              </div>
            ) : (
              <div>
                <Link
                  to="/login"
                  style={{
                    display: 'block',
                    padding: '8px 12px',
                    backgroundColor: 'transparent',
                    color: '#FF6B35',
                    border: '1px solid #FF6B35',
                    textDecoration: 'none',
                    borderRadius: '4px',
                    fontSize: '12px',
                    textAlign: 'center',
                    marginBottom: '8px'
                  }}
                >
                  Masuk
                </Link>
                <Link
                  to="/register"
                  style={{
                    display: 'block',
                    padding: '8px 12px',
                    backgroundColor: 'transparent',
                    color: '#FF8C42',
                    border: '1px solid #FF8C42',
                    textDecoration: 'none',
                    borderRadius: '4px',
                    fontSize: '12px',
                    textAlign: 'center'
                  }}
                >
                  Daftar
                </Link>
              </div>
            )}
          </div>
        )}
      </div>

      {/* Main Content */}
      <div style={{
        marginLeft: isSidebarCollapsed ? '60px' : '250px',
        transition: 'margin-left 0.3s ease',
        backgroundColor: '#f8f9fa',
        minHeight: '100vh',
        width: `calc(100% - ${isSidebarCollapsed ? '60px' : '250px'})`
      }}>
        <Outlet />
      </div>
    </div>
  );
};

export default PublicLayout;
