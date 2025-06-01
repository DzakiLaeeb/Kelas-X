import React from 'react';
import { Outlet, Link, useLocation, useNavigate } from 'react-router-dom';
import { useAuth } from '../contexts/AuthContext';

/**
 * Layout untuk halaman admin
 * Menyediakan sidebar navigasi dan area konten utama
 */
const AdminLayout = () => {
  const location = useLocation();
  const navigate = useNavigate();
  const { user, logout, isAdmin } = useAuth();

  // Menu items untuk admin dengan prefix /admin
  const adminMenuItems = [
    { path: '.', label: 'Dashboard', icon: '🏠' },
    { path: 'kategori', label: 'Kategori', icon: '📊' },
    { path: 'menu', label: 'Menu', icon: '🍽️' },
    { path: 'pelanggan', label: 'Pelanggan', icon: '👥' },
    { path: 'order', label: 'Order', icon: '📝' },
    { path: 'order-detail', label: 'Order Detail', icon: '📋' },
    // Only show Users menu for admin
    ...(isAdmin() ? [{ path: 'users', label: 'Pengguna', icon: '👤' }] : [])
  ];

  const handleLogout = () => {
    logout();
    navigate('/login');
  };

  return (
    <div style={{
      display: 'flex',
      minHeight: '100vh',
      width: '100vw',
      margin: 0,
      padding: 0,
      overflow: 'hidden'
    }}>
      {/* Simple Sidebar */}
      <div style={{
        width: '250px',
        backgroundColor: 'white',
        borderRight: '1px solid #e9ecef',
        position: 'fixed',
        height: '100vh',
        overflowY: 'auto',
        zIndex: 1000
      }}>
        {/* Brand */}
        <div style={{ padding: '20px', textAlign: 'center', borderBottom: '1px solid #e9ecef' }}>
          <Link to="/" style={{ textDecoration: 'none' }}>
            <h4 style={{ color: '#FF6B35', margin: 0, fontWeight: '300' }}>
              RestoKalel Admin
            </h4>
          </Link>
        </div>

        {/* Navigation */}
        <div style={{ padding: '10px 0' }}>
          <div style={{
            padding: '10px 20px',
            fontSize: '0.9rem',
            color: '#666',
            fontWeight: '500',
            borderBottom: '1px solid #f8f9fa',
            marginBottom: '10px'
          }}>
            Menu Aplikasi
          </div>

          {adminMenuItems.map((item) => {
            const isActive = location.pathname === `/admin/${item.path}` ||
                           (item.path === '.' && location.pathname === '/admin');

            return (
              <Link
                key={item.path}
                to={item.path}
                style={{
                  display: 'flex',
                  alignItems: 'center',
                  padding: '12px 20px',
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
                <span style={{ fontSize: '16px', marginRight: '10px' }}>
                  {item.icon}
                </span>
                <span style={{ fontSize: '14px' }}>{item.label}</span>
              </Link>
            );
          })}
        </div>

        {/* Bottom Section - User Profile */}
        <div style={{
          marginTop: 'auto',
          padding: '20px',
          borderTop: '1px solid #e9ecef'
        }}>
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

          <Link
            to="/"
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
            Back to Public
          </Link>

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
      </div>

      {/* Main Content */}
      <div style={{
        marginLeft: '250px',
        backgroundColor: '#f8f9fa',
        minHeight: '100vh',
        padding: '20px',
        width: 'calc(100vw - 250px)',
        flex: 1,
        overflowX: 'auto',
        overflowY: 'auto',
        position: 'relative'
      }}>
        <Outlet />
      </div>
    </div>
  );
};

export default AdminLayout;
